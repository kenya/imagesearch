<<<<<<< HEAD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
  <head>
=======
<!DOCTYPE html>
<html>
  <head>
  <link rel="stylesheet" href="css/sample5.css" type="text/css">
  <script src="js/jquery-1.11.0.min.js"></script>
  <script src="js/lightbox.min.js"></script>
  <link href="css/lightbox.css" rel="stylesheet" />
>>>>>>> d3e80798a7aae34e996c427b0dc214d0f3634137
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>集合写真検索システム</title>
  </head>
  <body>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">  
   <p>    
   検索キーワード：<input type="text" name="keyword" size=20/><br>
   写真中の人の数：<input type="text" name="number" size=20 /><br>
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
<<<<<<< HEAD
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
      if ((@$number_han == @$fc_data[$key] && @$number_han!=null) || $num == -1){
        // $search_result[$result_num] = array(){$key, $val, $fc_data[$key]};
        $search_result[$result_num][0] = $key;
        $search_result[$result_num][1] = $val;
        $search_result[$result_num][2] = $fc_data[$key];
        if(isset($search_result[2])) {
          $search_result[$result_num][2] = 0;
        }
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
      echo "<img src=".$value[0]."><br>\n";
      echo "キーワード出現回数＝".$value[1]."回<br>\n";
      echo "写真中の人の数＝".$value[2]."人<br>\n";
      echo "$key<br><br><br>\n";
  }
}
if(isset($_POST["keyword"]))
=======

if (isset($_POST["keyword"]) && isset($_POST["number"])) {  
  if(array_key_exists($_POST["keyword"], $tf_data) && $_POST["keyword"] <>null ){
    
	if ($_POST["number"]==null || !preg_match("/^[0-9]+$/", $_POST["number"])) {
		echo "人数を正しく入力して下さい。";
	} else {
    	echo "キーワード「".$_POST["keyword"]."」　人数「";
    	echo $_POST["number"]."人」での検索結果<br>\n";
    }
    echo "<hr><br>\n";
    
    foreach($tf_data[@$_POST["keyword"]] as $key => $val ) {
      if (@$_POST["number"] == @$fc_data[$key] && @$_POST["number"]<>null){
		echo "<a href='$key' rel='lightbox'><img src='$key'></a><br>\n";
		echo "キーワード出現回数＝".$val."回<br>\n";
		echo "写真中の人の数＝".@$fc_data[$key]."人<br>\n";
		echo "$key<br><br><br>\n";
		$result_num++;
      }
    }
    
  } elseif (@$_POST["keyword"]==null) {
    echo '検索キーワードを入力して下さい。';
  } else {
    echo '検索キーワードに合致する写真はありません。';
  }
}

>>>>>>> d3e80798a7aae34e996c427b0dc214d0f3634137
echo "検索結果は".$result_num."件でした。";

?>
</body>
</html>
