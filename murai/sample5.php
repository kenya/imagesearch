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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
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
                    echo "<img src='$key'><br>\n";
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
    
    echo "検索結果は".$result_num."件でした。";
    
    ?>
</body>
</html>
