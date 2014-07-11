<?php
    session_start();
    
    $life = 60 * 60 * 24 ;
    $cookieValue =array(($_POST["keyword"]),($_POST["number"]));
    $val=$_COOKIE["cdata"];
    $count=count($val);
    
    if($cookieValue[0]!==null){
        if(isset($_COOKIE["cdata"])){
            $string=$cookieValue[0]."&nbsp;&nbsp;&nbsp;&nbsp;";
            $string=$string.$cookieValue[1];
            
            if($count<4){
                setcookie("cdata[$count]",$string,time()+$life);
            }
            else{
                
                setcookie("cdata[0]",$val[1],time()+$life);
                setcookie("cdata[1]",$val[2],time()+$life);
                setcookie("cdata[2]",$val[3],time()+$life);
                setcookie("cdata[3]",$string,time()+$life);
            }
            
        }
        else{
            $cookieValue =array(($_POST["keyword"]),($_POST["number"]));
            $string=$cookieValue[0]."&nbsp;&nbsp;&nbsp;&nbsp;";
            $string=$string.$cookieValue[1];
            setcookie("cdata[0]",$string,time()+$life);
        }
    }
    else{}
    ?>


<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/sample5.css" type="text/css">
<link href="css/lightbox.css" rel="stylesheet" />
<link rel="stylesheet" href="css/introjs.css" type="text/css">

<script src="js/jquery-1.11.0.min.js"></script>
<script src="js/lightbox.min.js"></script>
<script src="js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="js/waypoints.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript" ></script>
<script src="./js/waypoints.min.js"></script>
<script src="./js/waypoints-sticky.min.js"></script>
<script type="text/javascript" src="js/jquery.MyThumbnail.js"></script>
<script src="js/intro.js"></script>

<script type="text/javascript">
			
$(document).ready(function() {
				  $('.my-sticky-element').waypoint('sticky');
				  });

$(document).ready(function(){
				  $("#thumbnails img").MyThumbnail({
												   thumbWidth:  200,
												   thumbHeight: 150,
												   backgroundColor:"#ccc",
												   imageDivClass:"myPic"
												   });
				  });

$(function() {
  $('div #pic').lightBox();
  });
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>集合写真検索システム</title>
</head>

<body>
		<div id="info">
<?php
    echo "検索履歴：" ;
    for($i=0;$i<$count;$i++){
        $word=explode("&nbsp;&nbsp;&nbsp;&nbsp;",$val[$count-$i-1]);
        
        echo "「"."<a href='g_03.php?keyword=$word[0]&number=$word[1]'>".$val[$count-$i-1]."」</a>";
    }
    if(isset($_GET['keyword']) && isset($_GET['number'])){
        $_POST["keyword"]=$_GET['keyword'];
        $_POST["number"]=$_GET['number'];
    }
    ?>
<h1>集合写真検索システムサービス</h1>
<h2>説明</h2>
<p>下のチュートリアルスタートのボタンを押すとこのページの使い方を学ぶことができます。</p>
<button onClick="introJs().start()">チュートリアルスタート</button>
<div class="my-sticky-element">
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<p>
<span data-intro="ここに検索のキーワードを入力して下さい。　例: 大学" data-step="1">
<span data-intro="もしくは検索のキーワードだけを入力して、" data-step="4">
検索キーワード：<input type="text" placeholder="input word" name="keyword" size=20/>
</span>
</span>
<span data-intro="ここに人数を入力して下さい。 例: 4<br>人数の範囲指定をする場合<br>一人から四人を範囲指定する例: 1-4" data-step="2">
写真中の人の数：<input type="text" placeholder="input person number"name="number" size=20 />
</span>
<span data-intro="検索のキーワードと人数を入力し終えたらこのボタンを押して下さい。" data-step="3">
<span data-intro="こちらのボタンを押して下さい。" data-step="5">
<input type="submit" value="Search!" />
</span>
</span>
</p>
</form>
</div>

<?php
		// tfファイルの読み込み　１行ずつ　最後まで
	$tf_data = array( array());
	$tffile = "tfimg.all";
	$f1 = fopen($tffile, "r");
	while (! feof ($f1)) {
		$line = fgets($f1);
		$tf_line = preg_split( "/\t/" , $line );
		@$tf_line[2] = preg_replace("/\r|\n/","",$tf_line[2]);
		@$tf_data[$tf_line[0]][$tf_line[2]] = $tf_line[1];
	}
	fclose($f1);
		// tfファイルの読み込み　ここまで
	
	
		// fcファイルの読み込み　１行ずつ　最後まで
	$fc_data = array();
	$fcfile = "fcimg.all";
	$f2 = fopen($fcfile, "r");
	while (! feof ($f2)) {
		$line = fgets($f2);
		$fc_line = preg_split( "/\t/" , $line );
		@$fc_line[1] = preg_replace("/\r|\n/","",$fc_line[1]);
		$fc_data[$fc_line[1]] = $fc_line[0];
	}
	fclose($f2);
		// fcファイルの読み込み　ここまで
	
	
		// 以下、検索処理
	$result_num = 0;
	$num;
	$search_result; //[][0:ファイル名 1:キーワード頻度 2:人数]
	$searching_number = array(); //人数の範囲指定用配列
	$number_han = mb_convert_kana($_POST["number"], 'ran', "UTF-8");
	$number_han = explode("-", $number_han);
	
	if (isset($_POST["keyword"]) && isset($number_han)) {
		if(check_number($number_han) == 0) {
			echo "人数を正しく入力して下さい。";
		} elseif(array_key_exists($_POST["keyword"], $tf_data) && $_POST["keyword"] <>null ){
			if($number_han[0]==null){
				echo "キーワード「".$_POST["keyword"]."」　人数「指定なし」での検索結果<br>\n";
				$num = -1;
				array_push($searching_number, $number_han);
			} elseif(count($number_han) == 2) {
				echo "キーワード「".$_POST["keyword"]."」　人数「$number_han[0]人から $number_han[1]人」での検索結果<br>\n";
				foreach($number_han as $val) {
					array_push($searching_number, $val);
				}
			} else {
				echo "キーワード「".$_POST["keyword"]."」　人数「";
				echo $number_han[0]."人」での検索結果<br>\n";
				$num = 0;
				array_push($searching_number, $number_han[0]);
			}
			
			echo "<hr><br>\n";
			$search_result = array( array() );
			foreach($searching_number as $val_number){
				foreach($tf_data[@$_POST["keyword"]] as $key => $val ) {
					if ((($val_number == $fc_data[$key] && $val_number!=null) || $num == -1) && isset($fc_data[$key])) {
						$search_result[$result_num][0] = $key;
						$search_result[$result_num][1] = $val;
						$search_result[$result_num][2] = $fc_data[$key];
						$result_num++;
					}
				}
			}
		} elseif (@$_POST["keyword"]==null) {
			echo '検索キーワードを入力して下さい。';
		} else {
			echo '検索キーワード「'.@$_POST["keyword"].'」に合致する写真はありません。';
		}
	}
	
	if(isset($_POST["keyword"]))
		echo "検索結果は".$result_num."件でした。";
	
	if(isset($search_result)) {
		echo "<div id='thumbnails'>\n";
		echo "<ul class='pic' id='pic'>";
		foreach ($search_result as $value) {
			echo "<li><a href='$value[0]' rel='lightbox' data-lightbox='pics' data-title='キーワード出現回数＝$value[1]回<br>写真中の人の数＝$value[2]人<br>画像へのパス:$value[0]'><img src='$value[0]'></a><br>\n";
			echo "</li>\n";
		}
		echo "</ul></div>\n";
	}
	
	
    function check_number($number){
		if(@count($number) == 2 && preg_match("/^[0-9]+$/", $number[0]) && preg_match("/^[0-9]+$/", $number[1])) {
			if($number[0] == $number[1] || $number[0] < 1 || $number[1] < 1) {
				return 0;
			}else {
				return 1;
			}
		} elseif(@preg_match("/^[0-9]+$/", $number[0]) && !isset($number[1]) || isset($number[0])) {
			return 1;
		} else {
			return 0;
		}
	}
    
	?>
</div>

		<script src="js/three.min.js"></script>
		<script src="obj/Bird.js"></script>

		<script src="js/libs/stats.min.js"></script>

		<script>

			// Based on http://www.openprocessing.org/visuals/?visualID=6910

			var Boid = function() {

				var vector = new THREE.Vector3(),
				_acceleration, _width = 500, _height = 500, _depth = 200, _goal, _neighborhoodRadius = 100,
				_maxSpeed = 4, _maxSteerForce = 0.1, _avoidWalls = false;

				this.position = new THREE.Vector3();
				this.velocity = new THREE.Vector3();
				_acceleration = new THREE.Vector3();

				this.setGoal = function ( target ) {

					_goal = target;

				}

				this.setAvoidWalls = function ( value ) {

					_avoidWalls = value;

				}

				this.setWorldSize = function ( width, height, depth ) {

					_width = width;
					_height = height;
					_depth = depth;

				}

				this.run = function ( boids ) {

					if ( _avoidWalls ) {

						vector.set( - _width, this.position.y, this.position.z );
						vector = this.avoid( vector );
						vector.multiplyScalar( 5 );
						_acceleration.add( vector );

						vector.set( _width, this.position.y, this.position.z );
						vector = this.avoid( vector );
						vector.multiplyScalar( 5 );
						_acceleration.add( vector );

						vector.set( this.position.x, - _height, this.position.z );
						vector = this.avoid( vector );
						vector.multiplyScalar( 5 );
						_acceleration.add( vector );

						vector.set( this.position.x, _height, this.position.z );
						vector = this.avoid( vector );
						vector.multiplyScalar( 5 );
						_acceleration.add( vector );

						vector.set( this.position.x, this.position.y, - _depth );
						vector = this.avoid( vector );
						vector.multiplyScalar( 5 );
						_acceleration.add( vector );

						vector.set( this.position.x, this.position.y, _depth );
						vector = this.avoid( vector );
						vector.multiplyScalar( 5 );
						_acceleration.add( vector );

					}/* else {

						this.checkBounds();

					}
					*/

					if ( Math.random() > 0.5 ) {

						this.flock( boids );

					}

					this.move();

				}

				this.flock = function ( boids ) {

					if ( _goal ) {

						_acceleration.add( this.reach( _goal, 0.005 ) );

					}

					_acceleration.add( this.alignment( boids ) );
					_acceleration.add( this.cohesion( boids ) );
					_acceleration.add( this.separation( boids ) );

				}

				this.move = function () {

					this.velocity.add( _acceleration );

					var l = this.velocity.length();

					if ( l > _maxSpeed ) {

						this.velocity.divideScalar( l / _maxSpeed );

					}

					this.position.add( this.velocity );
					_acceleration.set( 0, 0, 0 );

				}

				this.checkBounds = function () {

					if ( this.position.x >   _width ) this.position.x = - _width;
					if ( this.position.x < - _width ) this.position.x =   _width;
					if ( this.position.y >   _height ) this.position.y = - _height;
					if ( this.position.y < - _height ) this.position.y =  _height;
					if ( this.position.z >  _depth ) this.position.z = - _depth;
					if ( this.position.z < - _depth ) this.position.z =  _depth;

				}

				//

				this.avoid = function ( target ) {

					var steer = new THREE.Vector3();

					steer.copy( this.position );
					steer.sub( target );

					steer.multiplyScalar( 1 / this.position.distanceToSquared( target ) );

					return steer;

				}

				this.repulse = function ( target ) {

					var distance = this.position.distanceTo( target );

					if ( distance < 150 ) {

						var steer = new THREE.Vector3();

						steer.subVectors( this.position, target );
						steer.multiplyScalar( 0.5 / distance );

						_acceleration.add( steer );

					}

				}

				this.reach = function ( target, amount ) {

					var steer = new THREE.Vector3();

					steer.subVectors( target, this.position );
					steer.multiplyScalar( amount );

					return steer;

				}

				this.alignment = function ( boids ) {

					var boid, velSum = new THREE.Vector3(),
					count = 0;

					for ( var i = 0, il = boids.length; i < il; i++ ) {

						if ( Math.random() > 0.6 ) continue;

						boid = boids[ i ];

						distance = boid.position.distanceTo( this.position );

						if ( distance > 0 && distance <= _neighborhoodRadius ) {

							velSum.add( boid.velocity );
							count++;

						}

					}

					if ( count > 0 ) {

						velSum.divideScalar( count );

						var l = velSum.length();

						if ( l > _maxSteerForce ) {

							velSum.divideScalar( l / _maxSteerForce );

						}

					}

					return velSum;

				}

				this.cohesion = function ( boids ) {

					var boid, distance,
					posSum = new THREE.Vector3(),
					steer = new THREE.Vector3(),
					count = 0;

					for ( var i = 0, il = boids.length; i < il; i ++ ) {

						if ( Math.random() > 0.6 ) continue;

						boid = boids[ i ];
						distance = boid.position.distanceTo( this.position );

						if ( distance > 0 && distance <= _neighborhoodRadius ) {

							posSum.add( boid.position );
							count++;

						}

					}

					if ( count > 0 ) {

						posSum.divideScalar( count );

					}

					steer.subVectors( posSum, this.position );

					var l = steer.length();

					if ( l > _maxSteerForce ) {

						steer.divideScalar( l / _maxSteerForce );

					}

					return steer;

				}

				this.separation = function ( boids ) {

					var boid, distance,
					posSum = new THREE.Vector3(),
					repulse = new THREE.Vector3();

					for ( var i = 0, il = boids.length; i < il; i ++ ) {

						if ( Math.random() > 0.6 ) continue;

						boid = boids[ i ];
						distance = boid.position.distanceTo( this.position );

						if ( distance > 0 && distance <= _neighborhoodRadius ) {

							repulse.subVectors( this.position, boid.position );
							repulse.normalize();
							repulse.divideScalar( distance );
							posSum.add( repulse );

						}

					}

					return posSum;

				}

			}

		</script>

		<script>

			var SCREEN_WIDTH = window.innerWidth,
			SCREEN_HEIGHT = window.innerHeight,
			SCREEN_WIDTH_HALF = SCREEN_WIDTH  / 2,
			SCREEN_HEIGHT_HALF = SCREEN_HEIGHT / 2;

			var camera, scene, renderer,
			birds, bird;

			var boid, boids;

			var stats;

			init();
			animate();

			function init() {

				camera = new THREE.PerspectiveCamera( 75, SCREEN_WIDTH / SCREEN_HEIGHT, 1, 10000 );
				camera.position.z = 450;

				scene = new THREE.Scene();

				birds = [];
				boids = [];

				for ( var i = 0; i < 200; i ++ ) {

					boid = boids[ i ] = new Boid();
					boid.position.x = Math.random() * 400 - 200;
					boid.position.y = Math.random() * 400 - 200;
					boid.position.z = Math.random() * 400 - 200;
					boid.velocity.x = Math.random() * 2 - 1;
					boid.velocity.y = Math.random() * 2 - 1;
					boid.velocity.z = Math.random() * 2 - 1;
					boid.setAvoidWalls( true );
					boid.setWorldSize( 500, 500, 400 );

					bird = birds[ i ] = new THREE.Mesh( new Bird(), new THREE.MeshBasicMaterial( { color:Math.random() * 0xffffff, side: THREE.DoubleSide } ) );
					bird.phase = Math.floor( Math.random() * 62.83 );
					bird.position = boids[ i ].position;
					scene.add( bird );


				}

				renderer = new THREE.CanvasRenderer();
				renderer.setClearColor( 0xffffff );
				renderer.setSize( SCREEN_WIDTH, SCREEN_HEIGHT );

				document.addEventListener( 'mousemove', onDocumentMouseMove, false );
				document.body.appendChild( renderer.domElement );

				stats = new Stats();
				stats.domElement.style.position = 'absolute';
				stats.domElement.style.left = '0px';
				stats.domElement.style.top = '0px';

				//document.getElementById( 'container' ).appendChild(stats.domElement);
				//show fps
				//

				window.addEventListener( 'resize', onWindowResize, false );

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

			}

			function onDocumentMouseMove( event ) {

				var vector = new THREE.Vector3( event.clientX - SCREEN_WIDTH_HALF, - event.clientY + SCREEN_HEIGHT_HALF, 0 );

				for ( var i = 0, il = boids.length; i < il; i++ ) {

					boid = boids[ i ];

					vector.z = boid.position.z;

					boid.repulse( vector );

				}

			}

			//

			function animate() {

				requestAnimationFrame( animate );

				render();
				stats.update();

			}

			function render() {

				for ( var i = 0, il = birds.length; i < il; i++ ) {

					boid = boids[ i ];
					boid.run( boids );

					bird = birds[ i ];

					color = bird.material.color;
					color.r = color.g = color.b = ( 500 - bird.position.z ) / 1000;

					bird.rotation.y = Math.atan2( - boid.velocity.z, boid.velocity.x );
					bird.rotation.z = Math.asin( boid.velocity.y / boid.velocity.length() );

					bird.phase = ( bird.phase + ( Math.max( 0, bird.rotation.z ) + 0.1 )  ) % 62.83;
					bird.geometry.vertices[ 5 ].y = bird.geometry.vertices[ 4 ].y = Math.sin( bird.phase ) * 5;

				}

				renderer.render( scene, camera );

			}

		</script>

<footer>
<p align="center">
Copyright(C) 2014 G_03. All rights reserved.
</p>
</footer>
</body>
</html>