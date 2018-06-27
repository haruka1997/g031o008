<?php
// セッション開始
session_start();

// エラーメッセージの初期化
$errorMessage = "";
$errorFlag = false;

// ログインボタンが押された場合
if (isset($_POST["login"])) {
    // 1. ユーザIDの入力チェック
    if (empty($_POST["userId"])) {  // emptyは値が空のとき
        $errorMessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    }

    if (!empty($_POST["userId"]) && !empty($_POST["password"])) {

        // 3. エラー処理
        try {
            $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008', 'g031o008', 'g031o008');

            $stmt = $dbh->prepare('SELECT * FROM user WHERE userId = :userId AND password = :password');
            $stmt->bindParam(':userId', $_POST['userId'], PDO::PARAM_STR);
            $stmt->bindParam(':password', $_POST['password'], PDO::PARAM_STR);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {    //入力したユーザIDとパスワードに一致するデータがあれば
                session_regenerate_id(true);    //セッション置き換え
                $_SESSION["userId"] = $row['userId'];   //ユーザIDをセッションに保存
                header("Location: myPage.php");  // マイページ画面へ遷移
                exit();  // 処理終了
            } else {
                // 該当データなし
                $errorFlag = true;
                $errorMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
            }
        } catch (PDOException $e) {
            $errorFlag = true;
            $errorMessage = 'データベースエラー';
        }
    }
}
?>

<!doctype html>
<html>
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
                  <h1 class="sign-title">ログイン</h1>
                  <?php if($errorFlag) {
                    echo $errorMessage;
                  } ?>
                  <form name="loginForm" method="post">
                    <div class="form-item">
                      <label for="userId"></label>
                      <input type="userId" name="userId" required="required" placeholder="ユーザID"></input>
                    </div>
                    <div class="form-item">
                      <label for="password"></label>
                      <input type="password" name="password" required="required" placeholder="パスワード"></input>
                    </div>
                    <div class="button-panel">
                      <input type="submit" name="login" class="sign-button" title="ログイン" value="ログイン"></input>
                    </div>
                  </form>
                  <div class="form-footer">
                    <p><a href="./signUp.php">新規登録</a></p>
                </div>
          </main>
        </div>
  </body>
</html>
