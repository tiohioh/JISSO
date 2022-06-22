<?php
$txt = $_GET["evalution"];
$id = $_GET["image_id"];

function kill($error_content) {
	echo <<< EOF
{
	"jadge":false,
	"text":{$error_content}
}
EOF;
	exit(1);
}

function success_resp($response) {
	echo <<< EOF
{
	"jadge":true,
	"text":{$response}
}
EOF;
}
header('Content-Type: application/json; charset=utf-8');

try{
	$dsn = 'mysql:dbname=********;host=*******************;charset=************';
	$username = '*************';
	$password = '*************************';
	$pdo = new PDO($dsn, $username, $password);
}catch(PDOException $e){
	header('Content-Type: text/plain; charset=UTF-8', true, 500);
	echo $e->getMessage();
	exit();
}

$sql = <<< EOF
CREATE TABLE IF NOT EXISTS imgid (
id INT PRIMARY KEY,
states INT,
ex1 INT,
ex2 INT,
ex3 INT,
ex4 INT,
registry_datetime DATETIME
) engine=innodb default charset=utf8
EOF;

$res = $pdo->query($sql);

$res = $pdo->query("SELECT * FROM imgid WHERE id={id} AND states=2");
$co = count($res);
if($co == 1){
}else{
	kill("対象の画像IDがありません。 or id = {$id}のstatusが2ではありません。");
}


$try_s = true;
$err_count = 10;
while($try_s){
	try{
		$res = $pdo->query("UPDATE imgid states=3 WHERE id={$id}");
		$try_s = false;
	}catch(Exception $e){
		$err_count += 1;
		if($err_count >= 2){
			$try_s = false;
			kill("id = {$id}のstatesの上書きができませんでした(試行回数{$err_count}回)。時間をおいてもう一度送信してください。");
		}
	}
}

$F_NAME = "./../data_json/" . $id . ".txt";
$jadge = file_put_contents($F_NAME, $txt, LOCK_EX);
if(!$jadge){
	kill("ファイルの書き込みに失敗しました。DBのstatesの値はすでに更新されているので手動でファイルの更新をしてください。");
}
success_resp("書き込み・上書きに成功しました。");

$pdo = false;
?>