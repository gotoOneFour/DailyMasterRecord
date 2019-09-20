<?php

require("../setting.php")

header('Content-Type: text/html; charset=UTF-8');
$content = "undefined";
$genre = "undefined";
try{
	$options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	];

	$pdo = new PDO($dsn, $username, $password, $options);
	
	$s = $pdo->prepare("INSERT INTO {$tablename} ( content,genre ) VALUES ( :content,:genre )");
    $content = htmlspecialchars(str_replace("'",'"',$_GET['content']));
    $genre   = htmlspecialchars(str_replace("'",'"',$_GET['genre']));
	$s->bindParam(':content', $content, PDO::PARAM_STR);
	$s->bindParam(':genre'  , $genre  , PDO::PARAM_STR);
	
	$s->execute();
	
} catch (PDOException $e) {
	exit($e->getMessage());
}
date_default_timezone_set('Asia/Tokyo');
echo(date("Ymd H:i"));
echo("<br>");
echo($genre);
echo("　");
echo($content);

echo('<br><a href="index.html">戻る</a>')
?>