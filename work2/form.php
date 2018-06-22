<?php
    $dbh = new PDO('mysql:host=153.126.145.118;dbname=g031o008', 'g031o008', 'g031o008');
    if($dbh == null){
    	print_r('接続失敗').PHP_EOL;
    }else{
        //データが入力されているかどうかチェックする
        if(array_key_exists('userId',$_POST) OR array_key_exists('password', $_POST))
        {
            if($_POST['userId'] == ''){
                echo "ユーザIDを入力してください";
            }
            elseif($_POST['password']==''){
                echo "パスワードを入力してください";
            }
            else{
                $stmt = $dbh -> prepare("INSERT INTO user (userId, password) VALUES (:userId, :password)");
                $stmt->bindParam(':userId', $_POST['userId'], PDO::PARAM_STR);
                $stmt->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
                $stmt->execute();
            }
        }
    }
?>
<!DOCTYPE html>
<head>
    <title>ユーザ新規登録</title>
</head>
<body>
<form method="post">
    <input type="text" name="userId" placeholder="ユーザID">
    <input type="password" name="password" placeholder="パスワード">
    <input type="submit" value="登録する">
</form>

<form action="record.php">
    <input type="submit" value="一覧画面を表示する">
</form>
</body>