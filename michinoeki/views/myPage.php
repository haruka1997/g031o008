<?php
session_start();

$user = []; //ユーザの情報
$message = []; //メッセージの情報
$errorFlag = false; //エラーフラグ
$errorMessage = ''; //エラーメッセージ
$select_message = [];   //選択したメッセージ

if(!isset($_SESSION['userId'])){
    header('Location: login.php');
}

//ユーザの情報取得処理
try {
    $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');

    $stmt = $dbh->prepare('SELECT userName, userPlace, visitStationNum, getTicketNum, introduce, icon FROM user WHERE userId = :userId');    //ユーザIDに合致するユーザ情報の取得
    $stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_STR);
    $stmt->execute();

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {    //ユーザ情報がDBにあれば
        $user = $row;
        
        if($user['icon'] === ""){
            $user['icon'] = base64_encode(file_get_contents('./../img/default.png'));
        }

    } else {
        // 該当データなし
        $errorFlag = true;
        $errorMessage = 'ユーザ情報取得エラーです'; 
    }
} catch (PDOException $e) {
    $errorFlag = true;
    $errorMessage = 'データベースエラー';
}

//受信メッセージの情報取得処理
try {
    $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');

    $stmt = $dbh->prepare('SELECT * FROM message WHERE toUserId = :toUserId');    //ユーザIDに合致するメッセージ情報の取得
    $stmt->bindParam(':toUserId', $_SESSION['userId'], PDO::PARAM_STR);
    $stmt->execute();

    if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {    //ユーザ情報がDBにあれば
        $message = $row;
        
        for($i=0; $i<count($message); $i++){  
            // 送信ユーザ情報の取得
            $stmt = $dbh->prepare('SELECT userId, userName, icon FROM user WHERE userId = :userId');    //ユーザIDに合致するユーザ情報の取得
            $stmt->bindParam(':userId', $message[$i]['fromUserId'], PDO::PARAM_STR);
            $stmt->execute();

            // 送信ユーザのユーザ名とアイコン情報を格納
            if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $message[$i]['fromUserName'] = $row['userName'];
                if($row['icon'] === ""){
                    $message[$i]['icon'] = base64_encode(file_get_contents('./../img/default.png'));
                }else{
                    $message[$i]['icon'] = $row['icon'];
                }
            }
        }
    } else {
        // 該当データなし
        // $errorFlag = true;
        // $errorMessage = 'ユーザ情報取得エラーです'; 
    }
} catch (PDOException $e) {
    $errorFlag = true;
    $errorMessage = 'データベースエラー';
}

//ユーザ情報の更新
if (isset($_POST["regist"])) {

    try {
        $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
    
        $stmt = $dbh->prepare('UPDATE user SET userName = :userName, userPlace = :userPlace, introduce = :introduce, icon = :icon WHERE userId = :userId');    //ユーザIDに合致するユーザ情報の更新
        $stmt->bindParam(':userName',  $_POST['userName'], PDO::PARAM_STR);  //ユーザ名
        $stmt->bindParam(':userPlace', $_POST['userPlace'], PDO::PARAM_STR);    //居住地
        $stmt->bindParam(':introduce', $_POST['introduce'], PDO::PARAM_STR);    //自己紹介
        
        //アイコン指定可否判定
        if($_FILES['icon']['tmp_name'] !== ''){
            $icon = base64_encode(file_get_contents($_FILES['icon']['tmp_name']));  //icon画像base64化
        }else{
            $icon = $user['icon'];
        }
        $stmt->bindParam(':icon', $icon, PDO::PARAM_STR);   //アイコン画像
        $stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_STR);   //ユーザID
        $flag = $stmt->execute();

        if($flag){  //更新に成功
            header('Location: myPage.php');
        }else{  //更新に失敗
            $errorMessage = 'ユーザ情報の更新に失敗しました';
        }
        $stmt->execute();

    } catch (PDOException $e) {
        $errorFlag = true;
        $errorMessage = 'データベースエラー';
    }
}
?>

<!doctype html>
<html>
    <head>
        <meta http-equiv="content-type" charset="utf-8"/>
        <title>マイページ</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

        <link rel="stylesheet" href="./../css/main.css">
        <link rel="stylesheet" href="./../css/myPage.css">
        <link rel="stylesheet" href="./../css/modal.css">

    </head>
    <body>
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row" >
                    <span class="mdl-layout-title"><i class="fas fa-home"></i>    道の駅情報システム</span>
                    <div class="mdl-layout-spacer"></div>
                    <nav class="mdl-navigation mdl-layout--large-screen-only">
                        <div class="logout-button">
                            <p><a href="./login.php">ログアウト</a></p>
                        </div>
                    </nav>
                </div>
            </header>
            <div class="mdl-layout__drawer">
                <span class="mdl-layout-title">メニュー</span>
                <nav class="mdl-navigation">
                    <a class="mdl-navigation__link" href="./myPage.php"><i class="fas fa-home"></i>マイページ</a>
                    <a class="mdl-navigation__link" href="./search.php"><i class="fas fa-search"></i>道の駅情報</a>
                    <a class="mdl-navigation__link" href="./visitStation.php"><i class="fas fa-map-marker-alt"></i>訪問駅管理</a>
                    <a class="mdl-navigation__link" href="./getTicket.php"><i class="fas fa-ticket-alt"></i>切符管理</a>
                </nav>
            </div>
            <main class="mdl-layout__content">
                <div class="page-content">
                    <!-- プロフィール情報 -->
                    <div class="profile">
                        <!-- プロフィール画像(アイコン) -->
                        <div class="profile-image">
                            <img border="0" src="data:image/png;base64, <?= $user["icon"] ?>" width="240" style="max-height:260px;">
                        </div>
                        <!-- エラーメッセージ -->
                        <?= $errorMessage ?>
                        <!-- プロフィール情報 -->
                        <div class="profile-table">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th>ユーザ名</th>
                                    <td><?= htmlspecialchars($user['userName'], ENT_QUOTES, "UTF-8") ?></td>
                                </tr>
                                <tr>
                                    <th>居住地</th>
                                    <td><?= htmlspecialchars($user['userPlace'], ENT_QUOTES, "UTF-8") ?></td>
                                </tr>
                                <tr>
                                    <th>訪問駅数</th>
                                    <td><?= htmlspecialchars($user['visitStationNum'], ENT_QUOTES, "UTF-8") ?></td>
                                </tr>
                                <tr>
                                    <th>入手切符数</th>
                                    <td><?= htmlspecialchars($user['getTicketNum'], ENT_QUOTES, "UTF-8") ?></td>
                                </tr>
                                <tr>
                                    <th>自己紹介</th>
                                    <td style="height:200px;"><pre><?= htmlspecialchars($user['introduce'], ENT_QUOTES, "UTF-8") ?></pre></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- 編集ボタン -->
                    <div class="edit-button">
                        <label for="edit-modal-trigger" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">編集</label>
                    </div>
                </div>

                <!-- 編集モーダル -->
                <div class="modal">
                    <input id="edit-modal-trigger" class="checkbox" type="checkbox">
                    <div class="modal-overlay">
                        <label for="edit-modal-trigger" class="o-close"></label>
                        <div class="modal-wrap">
                            <label for="edit-modal-trigger" class="close">×</label>
                            <!-- モーダルヘッダ -->
                            <div class="modal-header">
                                マイページ編集
                            </div>
                            <form name="regist" method="post" enctype="multipart/form-data">
                                <div class="profile">
                                    <div class="edit-profile-image">
                                        <img border="0" src="data:image/png;base64, <?= $user["icon"] ?>" width="200">
                                        <div class="icon-change-button">
                                            <input type="file" name="icon">
                                        </div>
                                    </div>
                                    <div class="edit-profile-table">
                                        <table class="edit-table">
                                            <tbody>
                                            <tr>
                                                <th>ユーザ名</th>
                                                <td><input type="text" name="userName" value=<?= $user['userName'] ?>></td>
                                            </tr>
                                            <tr>
                                                <th>居住地</th>
                                                <td>
                                                <select name="userPlace">
                                                    <option value="県北" <?= $user['userPlace'] != '県北' ?: 'selected' ?>>県北</option>
                                                    <option value="沿岸" <?= $user['userPlace'] != '沿岸' ?: 'selected' ?>>沿岸</option>
                                                    <option value="県南" <?= $user['userPlace'] != '県南' ?: 'selected' ?>>県南</option>
                                                </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>自己紹介</th>
                                                <td style="height:200px;"><textarea type="text" name="introduce" rows="10" cols="100"><?= $user['introduce'] ?></textarea></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- 編集ボタン -->
                                <div class="regist-button">
                                    <button type="submit" name="regist" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">登録</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- 受信メッセージ -->
                <div class="message">
                    <!-- ヘッダ -->
                    <div class="message-header">
                        <p><i class="fas fa-envelope"></i> 受信メッセージ</p>
                    </div>
                    <!-- メッセージ一覧 -->
                    <div class="message-list">
                        <?php foreach($message as $key => $value){?>
                            <form type="post">
                                 <p><label for="send-mail-modal-trigger<?= $key ?>"><?= $message[$key]['title'] ?></input></p>
                            </form>
                        <?php } ?>
                    </div>
                </div>

                <!-- 受信メッセージモーダル -->
                <?php foreach($message as $key => $value){?>
                    <div class="modal">
                        <input id="send-mail-modal-trigger<?= $key ?>" class="checkbox" type="checkbox">
                        <div class="modal-overlay">
                            <label for="send-mail-modal-trigger<?= $key ?>" class="o-close"></label>
                            <div class="modal-wrap">
                                <label for="send-mail-modal-trigger<?= $key ?>" class="close">×</label>
                                <!-- モーダルヘッダ(タイトル) -->
                                <div class="title-header">
                                    <p><?= $message[$key]['title'] ?></p>
                                </div>
                                <div class="title-sub-header">
                                    <!-- 送信ユーザ -->
                                    <div class="send-user">
                                        <img border="0" src="data:image/png;base64, <?= $message[$key]['icon'] ?>" width="40">
                                        <label><?= $message[$key]['fromUserName'] ?></label>
                                    </div>
                                    <!-- 送信日時 -->
                                    <div class="send-date">
                                        <label><?= $message[$key]['created'] ?></label>
                                    </div>
                                </div>
                                <!-- メール本文 -->
                                <div class="body">
                                    <div class="mail-body">
                                    <?= $message[$key]['body'] ?>
                                    </div>
                                </div>
                                <!-- 送信ボタン -->
                                <div class="regist-button">
                                    <button type="submit" name="regist" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">返信</label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }  ?>
            </main>
        </div>
    </body>
</html>
