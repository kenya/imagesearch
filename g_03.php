<?php
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

</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>集合写真検索システム</title>
</head>

<body>

<?php
    echo "検索履歴：" ;
    for($i=0;$i<$count;$i++){
        $word=explode("&nbsp;&nbsp;&nbsp;&nbsp;",$val[$count-$i-1]);
        
        echo "「"."<a href='sample5.php?keyword=$word[0]&number=$word[1]'>".$val[$count-$i-1]."」</a>";
    }
    if(isset($_GET['keyword']) && isset($_GET['number'])){
        $_POST["keyword"]=$_GET['keyword'];
        $_POST["number"]=$_GET['number'];
    }
    ?>

<h2>説明</h2>
<p>下のチュートリアルスタートのボタンを押すとこのページの使い方を学ぶことができます。</p>
<button onClick="introJs().start()">チュートリアルスタート</button>
<div class="my-sticky-element">
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<p>
<span data-intro="ここに検索のキーワードを入力して下さい。" data-step="1">
<span data-intro="もしくは検索のキーワードだけを入力して、" data-step="4">
検索キーワード：<input type="text" placeholder="input word" name="keyword" size=20/>
</span>
</span>
<span data-intro="ここに人数を入力して下さい。" data-step="2">
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
		echo "<div id='thumbnails'>\n";
		echo "<ul class='pic'>";
		foreach ($search_result as $value) {
			echo "<li><a href='$value[0]' rel='lightbox'><img src='$value[0]'></a><br>\n";
			echo "キーワード出現回数＝".$value[1]."回<br>\n";
			echo "写真中の人の数＝".$value[2]."人<br>\n";
			echo "$value[0]</li>\n";
		}
		echo "</ul></div>\n";
	}
	if(isset($_POST["keyword"]))
	echo "<p>検索結果は".$result_num."件でした。</p>";
	
	?>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<footer>
<hr>
<p align="center">
Copyright(C) 2014 G_03. All rights reserved.
</p>
</footer>
</body>
</html>