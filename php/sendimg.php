<?php


$b64 = file_get_contents('php://input');
try{
	if(1){
		$decoded = base64_decode($b64);
		$bin = base64_decode($b64);
	}
}catch(exception $e){
	file_put_contents("./error.txt",$e);
	kill("ファイルのパースを失敗しました。sendimg.php~12行目?");
}

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
	"image_id":{$response}
}
EOF;
}

header('Content-Type: application/json; charset=utf-8');

try{
	$dsn = 'mysql:dbname=*******************;host=******************;charset=****************';
	$username = '***********';
	$password = '******************';
	$pdo = new PDO($dsn, $username, $password);
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

$id = file_get_contents("sendimg_data_sql.txt");
$id += 1;
if($id > 10000000){
  $id = 0;
}

$exe = 1;
$error_count = 0;
while($exe){
  try{
    $res = $pdo->query("INSERT INTO imgid VALUES (" . $id . ", 0, NULL, NULL, NULL, NULL, now())");
    $exe = 0;
  }catch(Exception $e){
    $error_count += 1;
	$id *= $error_count;
	$id += 1;
	if($error_count > 10){
		file_put_contents("sendimg_data_sql.txt",file_get_contents("sendimg_data_sql.txt") + 1);//
		kill("データの識別IDの取得の試行回数が10回を超えました。");
	}
  }
}
$F_NAME = "./../data_image/" . $id . ".jpg";
$jadge = file_put_contents($F_NAME, $bin, LOCK_EX);
if(!$jadge){
	kill("ファイルの書き込みに失敗しました。");
}
success_resp($id);

file_put_contents("sendimg_data_sql.txt",$id);


$pdo = false;
?>