<!DOCTYPE html>
<html lang="en">
	<head>
		<title>three.js canvas - geometry - birds</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<style>
			#info {
				position: absolute;
				top: 0px; width: 100%;
				padding: 5px;
			}
		</style>
		<link rel="stylesheet" href="css/sample5.css" type="text/css">
	</head>
	<body>

		<div id="container"></div>
		<div id="info">
			<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">  
   				<p>    
   	検索キーワード：<input type="text" name="keyword" size=20 /><br>
   	写真中の人の数：<input type="text" name="number" size=20  placeholder="1~3人までね"/><br>
   				<input type="submit" value="Search!" /> 
   				</p>
			</form>
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
$number_han = mb_convert_kana($_POST["number"], 'ran', "UTF-8");
$num;
$search_result; //[][0:ファイル名 1:キーワード頻度 2:人数]

if (isset($_POST["keyword"]) && isset($number_han)) {  
  if(array_key_exists($_POST["keyword"], $tf_data) && $_POST["keyword"] <>null ){
    
    if($number_han==null){
      echo "キーワード「".$_POST["keyword"]."」　人数「指定なし」での検索結果<br>\n";
      $num = -1;
    }elseif (!preg_match("/^[0-9]+$/", $number_han)) {
      echo "人数を正しく入力して下さい。";
    } else {
      echo "キーワード「".$_POST["keyword"]."」　人数「";
      echo $number_han."人」での検索結果<br>\n";
      $num = 0;
    }
    echo "<hr><br>\n";
    $search_result = array( array() );
    foreach($tf_data[@$_POST["keyword"]] as $key => $val ) {
      if ((($number_han == $fc_data[$key] && $number_han!=null) || $num == -1) && isset($fc_data[$key])) {
        // $search_result[$result_num] = array(){$key, $val, $fc_data[$key]};
        $search_result[$result_num][0] = $key;
        $search_result[$result_num][1] = $val;
        $search_result[$result_num][2] = $fc_data[$key];
    // echo "<img src='$key'><br>\n";
    // echo "キーワード出現回数＝".$val."回<br>\n";
    // echo "写真中の人の数＝".@$fc_data[$key]."人<br>\n";
    // echo "$key<br><br><br>\n";
        $result_num++;
      }
    }
  } elseif (@$_POST["keyword"]==null) {
    echo '検索キーワードを入力して下さい。';
  } else {
    echo '検索キーワード「'.@$_POST["keyword"].'」に合致する写真はありません。';
  }
}

if(isset($search_result)) {
  foreach ($search_result as $value) {
	  echo "<a href='$value[0]' rel='lightbox'><img src='$value[0]'></a><br>\n";
      echo "キーワード出現回数＝".$value[1]."回<br>\n";
      echo "写真中の人の数＝".$value[2]."人<br>\n";
      echo "$value[0]<br><br><br>\n";
  }
}
if(isset($_POST["keyword"]))
echo "検索結果は".$result_num."件でした。";

?>

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
		</div>

	</body>
</html>
