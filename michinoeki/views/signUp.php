<?php
    $compFlag = false;  //DB情報格納フラグ
    $errorFlag = false;
    $errorMsg = '';
    $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008', 'g031o008', 'g031o008');
    if($dbh == null){
    	print_r('接続失敗').PHP_EOL;
    }else{
        //データが入力されているかどうかチェックする
        if(array_key_exists('userId',$_POST) AND array_key_exists('userName', $_POST) AND array_key_exists('password', $_POST) AND array_key_exists('userPlace', $_POST))
        {
            /**
             * 変更(7/1): createdの時刻挿入をDB側で行うように変更したのでコード削除
             */
            $stmt = $dbh->prepare("INSERT INTO user (userId, userName, userPlace, password) VALUES (:userId, :userName, :userPlace, :password)");
            $stmt->bindParam(':userId', $_POST['userId'], PDO::PARAM_STR);
            $stmt->bindParam(':userName', $_POST['userName'], PDO::PARAM_STR);
            $stmt->bindParam(':userPlace', $_POST['userPlace'], PDO::PARAM_STR);
            $stmt->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
            $flag = $stmt->execute();
            if($flag){  //エラーがなければ
                $compFlag = true;
            }else{  //エラーがあれば
                $errorFlag = true;
                $errorMsg = '登録エラーです．ユーザIDが重複している可能性があります．';
            }
            $stmt->execute();       
         }
    }
?>
<!DOCTYPE html>  
<head>
    <meta charset="utf-8"/>
    <title>マイページ</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" href="./../css/main.css">
    <link rel="stylesheet" href="./../css/sign.css">
  </head>
<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row" >
                <span class="mdl-layout-title"><i class="fas fa-home"></i>    道の駅情報システム</span>
                <div class="mdl-layout-spacer"></div>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">Title</span>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="">Link</a>
                <a class="mdl-navigation__link" href="">Link</a>
                <a class="mdl-navigation__link" href="">Link</a>
                <a class="mdl-navigation__link" href="">Link</a>
            </nav>
        </div>
        <main class="mdl-layout__content">
            <div class="form-wrapper">
                <!-- 変更(7/1)：if文の表記を「if():~endif;」に変更 -->
                <?php if(!$compFlag): ?>
                    <h1 class="sign-title">新規登録</h1>
                    <?php if($errorFlag) : 
                        echo $errorMsg;
                    endif; ?>
                    <form method="post">
                    <div class="form-item">
                        <label for="userId"></label>
                        <input type="text" name="userId" required="required" placeholder="ユーザID" pattern="^[0-9A-Za-z]+$"></input>
                    </div>
                    <div class="form-item">
                        <label for="userName"></label>
                        <input type="text" name="userName" required="required" placeholder="ユーザ名"></input>
                    </div>
                    <div class="form-item">
                        <select name="userPlace">
                            <option value="">居住地</option>
                            <option value="north">県北</option>
                            <option value="coast">沿岸</option>
                            <option value="south">県南</option>
                        </select>
                    </div>
                    <div class="form-item">
                        <label for="password"></label>
                        <input type="password" name="password" required="required" placeholder="パスワード" pattern="^[0-9A-Za-z]+$"></input>
                    </div>
                    <div class="button-panel">
                        <input type="submit" class="sign-button" title="新規登録" value="新規登録"></input>
                    </div>
                    <!-- 変更(7/1)：ログイン画面に戻れるようにログインボタンを追加 -->
                    <div class="form-footer">
                        <p><a href="./login.php">ログイン</a></p>
                    </div>
                    </form>
                <?php endif; ?>
                <?php if($compFlag): ?>
                    <h1 class="sign-complite">登録が完了しました！</h1>
                    <form>
                    <div class="form-footer">
                        <p><a href="./login.php">ログイン画面へ</a></p>
                    </div>
                    </form>
                <?php endif; ?>
            </div>
        </main>
    </div>    
  </body>
