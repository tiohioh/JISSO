<?php
function kill($error_content) {
	echo <<< EOF
{
	"jadge":false,
	"text":{$error_content}
}
EOF;
	exit(1);
}

header('Content-Type: application/json; charset=utf-8');

try{
	$dsn = 'mysql:dbname=*************;host=*************;charset=************';
	$username = '**********';
	$password = '***************';
	$pdo = new PDO($dsn, $username, $password);//, $driver_options);//open sql
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

try{
	$stmt = $pdo->query("SELECT id FROM imgid WHERE states = 0");
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	for($i = 0;$i < count($result);$i += 1){
		$result[$i] = $result[$i]["id"];
	}
	$idds = implode(",",$result);
	
	echo <<< EOF
{
	"image":[{$idds}],
	"text":"テスト",
	"jadge":true
}
EOF;

}catch(Exception $e){
	echo $e;
}

$pdo = false;
?>