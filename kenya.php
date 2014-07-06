<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/sample5.css" type="text/css">
<script src="js/jquery-1.11.0.min.js"></script>
<script src="js/lightbox.min.js"></script>
<link href="css/lightbox.css" rel="stylesheet" />
<script src="js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="js/waypoints.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript" ></script>
<script src="./js/waypoints.min.js"></script>
<script src="./js/waypoints-sticky.min.js"></script>
<script src="js/intro.js"></script>
<link rel="stylesheet" href="css/introjs.css" type="text/css">
<script type="text/javascript">

$(document).ready(function() {
				  $('.my-sticky-element').waypoint('sticky');
				  });
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>集合写真検索システム</title>
</head>

<body>
<div class="my-sticky-element">
<span>
<button onClick="introJs().start()">チュートリアルスタート</button>
</span>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<p>
<span data-intro="ここに検索のキーワードを入力して下さい。" data-step="1">
検索キーワード：<input type="text" placeholder="input word" name="keyword" size=20/>
</span>
<span data-intro="ここに人数を入力して下さい。" data-step="2">
写真中の人の数：<input type="text" placeholder="input person number"name="number" size=20 />
</span>
<span data-intro="検索のキーワードと人数を入力し終えたらこのボタンを押して下さい。" data-step="3">
<input type="submit" value="Search!" />
</step>
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
