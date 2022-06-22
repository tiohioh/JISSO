<head>
	<title>DB dump</title>
	<meta charset="utf-8"/>
	<script>
setTimeout(() => location.reload(),11000);
	</script>
	<meta http-equiv="refresh" content="10;URL=?">
</head>
<body>
<?php
/*
####checkDB.php####

DBの中身をhtmlのtable形式でdumpします。

*/



//エラー発生時の強制終了処理(エラー内容返却含め)
function kill($error_content) {
	echo <<< EOF
<div>faild to load</div>
EOF;
	exit(1);
}

##これより下は変えない
try{//データベースの接続
	$dsn = 'mysql:dbname=***********;host=***********;charset=***********';
	$username = '*************';
	$password = '***************************';
	$pdo = new PDO($dsn, $username, $password);
}catch(PDOException $e){
	header('Content-Type: text/plain; charset=UTF-8', true, 500);
	echo $e->getMessage();
	kill();
}
?>

<h1>DB dump</h1>
<div>Last Updated : <?php echo date('Y-m-d H:i:s',time()) ?></div>
<noscript><font color="red"><b>JavaScriptが無効化されているため自動更新は利用できません。</b></font></noscript>

<?php
$return_str = "<table border=\"1\">";
try{
	$stmt = $pdo->query("SELECT * FROM imgid");
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	for($i = -1;$i < count($result);$i += 1){
		if($i == -1){
			$return_str .= "<tr>";
			foreach(array_keys($result[0]) as $arg){
				$return_str .= "<th>".$arg."</th>";
			}
			$return_str .= "</tr>";
		}else{
			$return_str .= "<tr>";
			foreach($result[$i] as $arg){
				if($arg === NULL){
					$arg = 'Null';
				}
				$return_str .= "<td>".$arg."</td>";
			}
			$return_str .= "</tr>";
		}
	}
	$idds = implode(",",$result);
	$return_str .= "<table>";	
	//success response
	echo $return_str;
}catch(Exception $e){
	echo $e;
}

$pdo = false;
?>
<br/>
<div>Production Time : 2022-6-22 11:43:??</div>
</doby>
