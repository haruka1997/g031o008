<?php
try {
    $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008', 'g031o008', 'g031o008');
    if($dbh == null){
    	print_r('接続失敗').PHP_EOL;
    }else{
		$table_data = array();	//取得データ格納配列
		$sql = "SELECT userId, userName, userPlace, visitStationNum, getTicketNum, introduce FROM user;";	//userテーブルから全件データ取得するSQL文

		//データ取得
		$stmt = $dbh->query($sql);
		$table_data[user] = array();
		$index = 0;	//配列の添字

		/**
		 * 配列整形 以下のような形式にする
		 * user[
		 * 	0:[ userId: ....,
		 *      userName: ....,
		 *     ....
		 * 	],
		 *  1: [ userId: ....,
		 *       userName: ....,
		 *     ....
		 *  ]
		 * ]
		 */
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)){
			$table_data[user][$index] = $result;
			$index++;
		}

		//取得データ表示
		echo "userテーブル";	//テーブル名
		echo "<table border=1 style=border-collapse:collapse;>";
		echo "<tr>";
		echo "<th>ユーザID</th>";
		echo "<th>ユーザ名</th>";
		echo "<th>居住地</th>";
		echo "<th>訪問駅数</th>";
		echo "<th>入手切符数</th>";
		echo "<th>自己紹介</th>";
		echo "</tr>";
		foreach($table_data[user] as $index => $dataArray){	//$index: 添字(0,1,2...) $dataArray: [userId: ..., userName: ....,]
			echo "<tr>";
			foreach($dataArray as $dataKey => $data){	//$dataKey: userId, userNameなど　$data: 各dataKeyに値するデータ
				$dispData = htmlspecialchars($data);
				echo "<td>$dispData</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
    }
	$dbh = null;	//終了
} catch (PDOException $e) {	//DB接続エラー
    print "エラー!: " . $e->getMessage() . "<br/>";	//エラー出力
    die();
}
?>

<!DOCTYPE html>
<head>
    <title>一覧表示</title>
</head>
<form action="form.php">
    <input type="submit" value="追加画面に戻る">
</form>

