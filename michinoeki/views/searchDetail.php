<?php
session_start();
$overview_info = []; //概要情報
$basic_info = []; //基本情報
$recommend_info = [];   //おすすめ品情報
$buzz_info = []; //口コミ情報
$rate_info = [];    //評価情報
$edit_basic_info = [];  //基本情報編集
$edit_recommend_info = [];  //おすすめ品情報編集
$edit_buzz_info = [];   //口コミ情報編集

    // 詳細情報の取得
    try {
        $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
        // 概要情報の取得
        $sql = 'SELECT * FROM stationOverview WHERE stationOverview.stationId = :stationId;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':stationId', $_GET['stationId'], PDO::PARAM_STR); //駅ID
        $stmt->execute();   //実行

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {   //一致するデータがあれば
            $overview_info['stationName'] = $row['stationName'];
            $overview_info['stationImage'] = $row['stationImage'];
        }

        // 基本情報の取得
        $sql = 'SELECT * FROM stationBasic WHERE stationBasic.stationId = :stationId;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':stationId', $_GET['stationId'], PDO::PARAM_STR); //駅ID
        $stmt->execute();   //実行

        if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {   //一致するデータがあれば
            foreach($row as $key => $value){
                $basic_info[] = $value;
            }
            $edit_basic_info = $basic_info;
        }

        // おすすめ品の取得
        $sql = 'SELECT * FROM stationRecommend WHERE stationRecommend.stationId = :stationId;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':stationId', $_GET['stationId'], PDO::PARAM_STR); //駅ID
        $stmt->execute();   //実行

        if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {   //一致するデータがあれば
            foreach($row as $key => $value){
                $recommend_info[] = $value;
            }
            $edit_recommend_info = $recommend_info;
        }

        // 口コミの取得
        $sql = 'SELECT * FROM stationBuzz WHERE stationBuzz.stationId = :stationId;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':stationId', $_GET['stationId'], PDO::PARAM_STR); //駅ID
        $stmt->execute();   //実行

        if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {   //一致するデータがあれば
            foreach($row as $key => $value){
                $buzz_info[] = $value;
            }
            $edit_buzz_info = $buzz_info;
        }

        // 評価の取得
        $sql = 'SELECT TRUNCATE(AVG(directMarket), 0) AS directMarket, TRUNCATE(AVG(cafeteria), 0) AS cafeteria, TRUNCATE(AVG(carNight),0) AS carNight FROM stationRate WHERE stationRate.stationId = :stationId;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':stationId', $_GET['stationId'], PDO::PARAM_STR); //駅ID
        $stmt->execute();   //実行


        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {   //一致するデータがあれば
                $rate_info['directMarket'] = $row['directMarket'];
                $rate_info['cafeteria'] = $row['cafeteria'];
                $rate_info['carNight'] = $row['carNight'];
        }
    } catch (PDOException $e) {
        $errorFlag = true;
        $errorMessage = 'データベースエラー';
    }

    /**
     * 基本情報の編集・削除
     */
    //  編集完了ボタンが押されたら
    if (isset($_POST["basic-regist"])) {
        if(isset($_POST["edit-basic-key"])){
            foreach($_POST["edit-basic-key"] as $key => $value){
                try {
                    $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
                    // 概要情報の取得
                    $sql = 'UPDATE stationBasic SET userId = :userId, basicBody = :basicBody WHERE basicId = :basicId;';
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_STR); //ユーザID
                    $stmt->bindParam(':basicBody', $_POST["edit-basic-value-" .$value], PDO::PARAM_STR); //基本情報
                    $stmt->bindParam(':basicId', $value, PDO::PARAM_STR); //基本情報ID
            
                    $flag = $stmt->execute();
                    if($flag){  //更新に成功
                        header("Location: " . 'searchDetail.php?stationId=' .$_GET['stationId']);
                    }
                } catch (PDOException $e) {
                    $errorFlag = true;
                    $errorMessage = 'データベースエラー';
                }
            }
        }
        if(isset($_POST["add-basic-key"])){
            try {
                $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
                // 概要情報の取得
                $sql = 'INSERT INTO stationBasic(stationId, userId, basicBody) VALUES(:stationId, :userId, :basicBody)' ;
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':stationId', $_GET['stationId'], PDO::PARAM_STR); //駅ID
                $stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_STR); //ユーザID
                $stmt->bindParam(':basicBody', $_POST["add-basic-value"], PDO::PARAM_STR); //基本情報ID
        
                $flag = $stmt->execute();
                if($flag){  //更新に成功
                    header("Location: " . 'searchDetail.php?stationId=' .$_GET['stationId']);
                }
            } catch (PDOException $e) {
                $errorFlag = true;
                $errorMessage = 'データベースエラー';
            }
        }
    }
    // 削除ボタンが押されたら
    if (isset($_POST["basic-delete"])) {
        foreach($_POST["edit-basic-key"] as $key => $value){
            try {
                $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
                // 概要情報の取得
                $sql = 'DELETE FROM stationBasic WHERE  basicId = :basicId;';
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':basicId', $value, PDO::PARAM_STR); //基本情報ID
                $stmt->execute();   //実行
        
                $flag = $stmt->execute();
                if($flag){  //更新に成功
                    header("Location: " . 'searchDetail.php?stationId=' .$_GET['stationId']);
                }
            } catch (PDOException $e) {
                $errorFlag = true;
                $errorMessage = 'データベースエラー';
            }
        }
    }

    /**
     * おすすめ品情報の編集・削除
     */
    if (isset($_POST["recommend-regist"])) {
        foreach($_POST["edit-recommend-key"] as $key => $value){
            try {
                $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
                // 概要情報の取得
                $sql = 'UPDATE stationRecommend SET userId = :userId, recommendBody = :recommendBody WHERE recommendId = :recommendId;';
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_STR); //ユーザID
                $stmt->bindParam(':recommendBody', $_POST["edit-recommend-value-" .$value], PDO::PARAM_STR); //基本情報
                $stmt->bindParam(':recommendId', $value, PDO::PARAM_STR); //基本情報ID
                $stmt->execute();   //実行
        

                $flag = $stmt->execute();
                if($flag){  //更新に成功
                    header("Location: " . 'searchDetail.php?stationId=' .$_GET['stationId']);
                }
            } catch (PDOException $e) {
                $errorFlag = true;
                $errorMessage = 'データベースエラー';
            }
        }
        if(isset($_POST["add-recommend-key"])){
            try {
                $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
                // 概要情報の取得
                $sql = 'INSERT INTO stationRecommend(stationId, userId, recommendBody) VALUES(:stationId, :userId, :recommendBody)' ;
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':stationId', $_GET['stationId'], PDO::PARAM_STR); //駅ID
                $stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_STR); //ユーザID
                $stmt->bindParam(':recommendBody', $_POST["add-recommend-value"], PDO::PARAM_STR); //基本情報ID
        
                $flag = $stmt->execute();
                if($flag){  //更新に成功
                    header("Location: " . 'searchDetail.php?stationId=' .$_GET['stationId']);
                }
            } catch (PDOException $e) {
                $errorFlag = true;
                $errorMessage = 'データベースエラー';
            }
        }
    }
    // 削除ボタンが押されたら
    if (isset($_POST["recommend-delete"])) {
        foreach($_POST["edit-recommend-key"] as $key => $value){
            try {
                $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
                // 概要情報の取得
                $sql = 'DELETE FROM stationRecommend WHERE  recommendId = :recommendId;';
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':recommendId', $value, PDO::PARAM_STR); //基本情報ID
                $stmt->execute();   //実行
        
                $flag = $stmt->execute();
                if($flag){  //更新に成功
                    header("Location: " . 'searchDetail.php?stationId=' .$_GET['stationId']);
                }
            } catch (PDOException $e) {
                $errorFlag = true;
                $errorMessage = 'データベースエラー';
            }
        }
    }

    /**
     * 口コミ情報の編集・削除
     */
    if (isset($_POST["buzz-regist"])) {
        foreach($_POST["edit-buzz-key"] as $key => $value){
            try {
                $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
                // 概要情報の取得
                $sql = 'UPDATE stationBuzz SET userId = :userId, buzzBody = :buzzBody WHERE buzzId = :buzzId;';
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_STR); //ユーザID
                $stmt->bindParam(':buzzBody', $_POST["edit-buzz-value-" .$value], PDO::PARAM_STR); //基本情報
                $stmt->bindParam(':buzzId', $value, PDO::PARAM_STR); //基本情報ID
                $stmt->execute();   //実行
        
                $flag = $stmt->execute();
                if($flag){  //更新に成功
                    header("Location: " . 'searchDetail.php?stationId=' .$_GET['stationId']);
                }
            } catch (PDOException $e) {
                $errorFlag = true;
                $errorMessage = 'データベースエラー';
            }
        }
        if(isset($_POST["add-buzz-key"])){
            try {
                $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
                // 概要情報の取得
                $sql = 'INSERT INTO stationBuzz(stationId, userId, buzzBody) VALUES(:stationId, :userId, :buzzBody)' ;
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':stationId', $_GET['stationId'], PDO::PARAM_STR); //駅ID
                $stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_STR); //ユーザID
                $stmt->bindParam(':buzzBody', $_POST["add-buzz-value"], PDO::PARAM_STR); //基本情報ID
        
                $flag = $stmt->execute();
                if($flag){  //更新に成功
                    header("Location: " . 'searchDetail.php?stationId=' .$_GET['stationId']);
                }
            } catch (PDOException $e) {
                $errorFlag = true;
                $errorMessage = 'データベースエラー';
            }
        }
    }
    // 削除ボタンが押されたら
    if (isset($_POST["buzz-delete"])) {
        foreach($_POST["edit-buzz-key"] as $key => $value){
            try {
                $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
                // 概要情報の取得
                $sql = 'DELETE FROM stationBuzz WHERE  buzzId = :buzzId;';
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':buzzId', $value, PDO::PARAM_STR); //基本情報ID
                $stmt->execute();   //実行
        
                $flag = $stmt->execute();
                if($flag){  //更新に成功
                    header("Location: " . 'searchDetail.php?stationId=' .$_GET['stationId']);
                }
            } catch (PDOException $e) {
                $errorFlag = true;
                $errorMessage = 'データベースエラー';
            }
        }
    }

    /**
     * 評価の編集
     */
    if (isset($_POST["rate-regist"])) {
        try {
            $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
            // 概要情報の取得
            $sql = 'INSERT INTO stationRate(stationId, directMarket, cafeteria, carNight) VALUES (:stationId, :directMarket, :cafeteria, :carNight)';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':stationId', $_GET['stationId'], PDO::PARAM_STR); //駅ID
            $stmt->bindParam(':directMarket', $_POST['directMarket'], PDO::PARAM_STR); //産直の充実さ
            $stmt->bindParam(':cafeteria', $_POST['cafeteria'], PDO::PARAM_STR); // 食堂の美味しさ
            $stmt->bindParam(':carNight', $_POST['carNight'], PDO::PARAM_STR); //車中泊のしやすさ

            $flag = $stmt->execute();
            print_r($flag);
            if($flag){  //更新に成功
                header("Location: " . 'searchDetail.php?stationId=' .$_GET['stationId']);
            }
        } catch (PDOException $e) {
            $errorFlag = true;
            $errorMessage = 'データベースエラー';
        }
    }


?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>道の駅詳細情報</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

        <link rel="stylesheet" href="./../css/main.css">
        <link rel="stylesheet" href="./../css/modal.css">
        <link rel="stylesheet" href="./../css/searchDetail.css">

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
                    <!-- 検索リストに戻るリンク -->
                    <div class="back-list"><a href="<?= $_SERVER['HTTP_REFERER'] ?>">< 検索リストに戻る</a></div>
                    <!-- 詳細情報 -->
                    <div class="detail-content">
                        <div class="detail-info-left">
                            <!-- 駅画像 -->
                            <div class="station-image">
                                <img border="0" src="<?= $overview_info['stationImage'] ?>" width="300">
                            </div>
                            <!-- 評価 -->
                            <div class="station-rate">
                                <p id="info-title">評価  <label for="edit-rate-modal-trigger"><i class="fas fa-pencil-alt"></i></label></p>
                                <label>産直の充実度</label>
                                <p><img border="0" src="./../img/rate<?= $rate_info['directMarket'] ?>.png" width="200"></p>
                                <label>食堂の美味しさ</label>
                                <p><img border="0" src="./../img/rate<?= $rate_info['cafeteria'] ?>.png" width="200"></p>
                                <label>車中泊のしやすさ</label>
                                <p><img border="0" src="./../img/rate<?= $rate_info['carNight'] ?>.png" width="200"></p>
                            </div>
                        </div>
                        <div class="detail-info-right">
                            <!-- 駅名 -->
                            <div class="station-name"><?= $overview_info['stationName'] ?></div>
                            <!-- 基本情報 -->
                            <div class="station-overview">
                                <p id="info-title">基本情報 <label for="edit-basic-modal-trigger"><i class="fas fa-pencil-alt"></i></label></p>
                                <div id="info-body">
                                    <?php foreach($basic_info as $key => $value){
                                        echo '<p>' .$basic_info[$key]['basicBody'] .'</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- おすすめ品 -->
                            <div class="station-recommend">
                                <p id="info-title">おすすめ品 <label for="edit-recommend-modal-trigger"><i class="fas fa-pencil-alt"></i></label></p>
                                <div id="info-body">
                                    <?php foreach($recommend_info as $key => $value){
                                        echo '<p>' .$recommend_info[$key]['recommendBody'] .'</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- 口コミ -->
                            <div class="station-buzz">
                                <p id="info-title">口コミ <label for="edit-buzz-modal-trigger"><i class="fas fa-pencil-alt"></i></label></p>
                                <div id="info-body">
                                    <?php foreach($buzz_info as $key => $value){
                                        echo '<p>' .$buzz_info[$key]['buzzBody'] .'</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 基本情報編集モーダル -->
                    <div class="modal">
                        <input id="edit-basic-modal-trigger" class="checkbox" type="checkbox">
                        <div class="modal-overlay">
                            <label for="edit-basic-modal-trigger" class="o-close"></label>
                            <div class="modal-wrap">
                                <label for="edit-basic-modal-trigger" class="close">×</label>
                                <!-- モーダルヘッダ -->
                                <div class="modal-header">
                                    基本情報編集
                                </div>
                                <form name="regist" method="post">
                                    <div class="edit-items">
                                        <?php foreach($edit_basic_info as $key => $value){ ?>
                                            <div class="edit-item">
                                                <input type="checkbox" name="edit-basic-key[]" value=<?= $edit_basic_info[$key]['basicId'] ?> />
                                                <textarea type="text" name="edit-basic-value-<?= $edit_basic_info[$key]['basicId'] ?>" rows="2" cols="100"><?= $edit_basic_info[$key]['basicBody'] ?></textarea> 
                                            </div>
                                        <?php } ?>
                                        <div class="add-item">
                                            <input type="checkbox" name="add-basic-key" />
                                            <textarea type="text" name="add-basic-value" rows="2" cols="100"></textarea> 
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- 編集ボタン -->
                                        <div class="regist-button">
                                            <button type="submit" name="basic-regist" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">完了</label>
                                        </div>
                                        <!-- 削除ボタン -->
                                        <div class="delete-button">
                                            <button type="submit" name="basic-delete" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">削除</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- おすすめ品情報編集モーダル -->
                    <div class="modal">
                        <input id="edit-recommend-modal-trigger" class="checkbox" type="checkbox">
                        <div class="modal-overlay">
                            <label for="edit-recommend-modal-trigger" class="o-close"></label>
                            <div class="modal-wrap">
                                <label for="edit-recommend-modal-trigger" class="close">×</label>
                                <!-- モーダルヘッダ -->
                                <div class="modal-header">
                                    おすすめ品情報編集
                                </div>
                                <form name="regist" method="post">
                                    <div class="edit-items">
                                        <?php foreach($edit_recommend_info as $key => $value){ ?>
                                            <div class="edit-item">
                                                <input type="checkbox" name="edit-recommend-key[]" value=<?= $edit_recommend_info[$key]['recommendId'] ?> />
                                                <textarea type="text" name="edit-recommend-value-<?= $edit_recommend_info[$key]['recommendId'] ?>" rows="2" cols="100"><?= $edit_recommend_info[$key]['recommendBody'] ?></textarea> 
                                            </div>
                                        <?php } ?>
                                        <div class="add-item">
                                            <input type="checkbox" name="add-recommend-key" />
                                            <textarea type="text" name="add-recommend-value" rows="2" cols="100"></textarea> 
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- 編集ボタン -->
                                        <div class="regist-button">
                                            <button type="submit" name="recommend-regist" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">完了</label>
                                        </div>
                                        <!-- 削除ボタン -->
                                        <div class="delete-button">
                                            <button type="submit" name="recommend-delete" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">削除</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- 口コミ情報編集モーダル -->
                    <div class="modal">
                        <input id="edit-buzz-modal-trigger" class="checkbox" type="checkbox">
                        <div class="modal-overlay">
                            <label for="edit-buzz-modal-trigger" class="o-close"></label>
                            <div class="modal-wrap">
                                <label for="edit-buzz-modal-trigger" class="close">×</label>
                                <!-- モーダルヘッダ -->
                                <div class="modal-header">
                                    口コミ情報編集
                                </div>
                                <form name="regist" method="post">
                                    <div class="edit-items">
                                        <?php foreach($edit_buzz_info as $key => $value){ ?>
                                            <div class="edit-item">
                                                <input type="checkbox" name="edit-buzz-key[]" value=<?= $edit_buzz_info[$key]['buzzId'] ?> />
                                                <textarea type="text" name="edit-buzz-value-<?= $edit_buzz_info[$key]['buzzId'] ?>" rows="2" cols="100"><?= $edit_buzz_info[$key]['buzzBody'] ?></textarea> 
                                            </div>
                                        <?php } ?>
                                        <div class="add-item">
                                            <input type="checkbox" name="add-buzz-key" />
                                            <textarea type="text" name="add-buzz-value" rows="2" cols="100"></textarea> 
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- 編集ボタン -->
                                        <div class="regist-button">
                                            <button type="submit" name="buzz-regist" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">完了</label>
                                        </div>
                                        <!-- 削除ボタン -->
                                        <div class="delete-button">
                                            <button type="submit" name="buzz-delete" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">削除</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- 評価情報編集モーダル -->
                    <div class="modal">
                        <input id="edit-rate-modal-trigger" class="checkbox" type="checkbox">
                        <div class="modal-overlay">
                            <label for="edit-rate-modal-trigger" class="o-close"></label>
                            <div class="modal-wrap" style="width: 45%;">
                                <label for="edit-rate-modal-trigger" class="close">×</label>
                                <!-- モーダルヘッダ -->
                                <div class="modal-header">
                                    口コミ情報編集
                                </div>
                                <form name="regist" method="post">
                                    <div class="edit-items" style="text-align:center;">
                                        <div class="edit-item">
                                            <label>産直の充実度</label>
                                            <input class="edit-rate-input" type="number" name="directMarket" max="5" min="0" placeholder="0"/>
                                        </div>
                                        <div class="edit-item">
                                            <label>食堂の美味しさ</label>
                                            <input class="edit-rate-input" type="number" name="cafeteria" max="5" min="0" placeholder="0"/>
                                        </div>
                                        <div class="edit-item">
                                            <label>車中泊のしやすさ</label>
                                            <input class="edit-rate-input" type="number" name="carNight" max="5" min="0" placeholder="0"/>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- 編集ボタン -->
                                        <div class="regist-button">
                                            <button type="submit" name="rate-regist" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">完了</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>