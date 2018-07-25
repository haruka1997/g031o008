<?php
$overview_info = []; //概要情報
$basic_info = []; //基本情報
$recommend_info = [];   //おすすめ品情報
$buzz_info = []; //口コミ情報
$rate_info = [];    //評価情報

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
                $basic_info[] = $row[$key]['basicBody'];
            }
        }

        // おすすめ品の取得
        $sql = 'SELECT * FROM stationRecommend WHERE stationRecommend.stationId = :stationId;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':stationId', $_GET['stationId'], PDO::PARAM_STR); //駅ID
        $stmt->execute();   //実行

        if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {   //一致するデータがあれば
            foreach($row as $key => $value){
                $recommend_info[] = $row[$key]['recommendBody'];
            }
        }

        // 口コミの取得
        $sql = 'SELECT * FROM stationBuzz WHERE stationBuzz.stationId = :stationId;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':stationId', $_GET['stationId'], PDO::PARAM_STR); //駅ID
        $stmt->execute();   //実行

        if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {   //一致するデータがあれば
            foreach($row as $key => $value){
                $buzz_info[] = $row[$key]['buzzBody'];
            }
        }

        // 評価の取得
        $sql = 'SELECT * FROM stationRate WHERE stationRate.stationId = :stationId;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':stationId', $_GET['stationId'], PDO::PARAM_STR); //駅ID
        $stmt->execute();   //実行

        if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {   //一致するデータがあれば
            foreach($row as $key => $value){
                $rate_info['directMarket'] = $row[$key]['directMarket'];
                $rate_info['cafeteria'] = $row[$key]['cafeteria'];
                $rate_info['carNight'] = $row[$key]['carNight'];
            }
        }

    } catch (PDOException $e) {
        $errorFlag = true;
        $errorMessage = 'データベースエラー';
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
                                <p id="info-title">評価 <i class="fas fa-pencil-alt"></i></p>
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
                                <p id="info-title">基本情報 <i class="fas fa-pencil-alt"></i></p>
                                <div id="info-body">
                                    <?php foreach($basic_info as $key => $value){
                                        echo '<p>' .$value .'</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- おすすめ品 -->
                            <div class="station-recommend">
                                <p id="info-title">おすすめ品 <i class="fas fa-pencil-alt"></i></p>
                                <div id="info-body">
                                    <?php foreach($recommend_info as $key => $value){
                                        echo '<p>' .$value .'</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- 口コミ -->
                            <div class="station-buzz">
                                <p id="info-title">口コミ <i class="fas fa-pencil-alt"></i></p>
                                <div id="info-body">
                                    <?php foreach($buzz_info as $key => $value){
                                        echo '<p>' .$value .'</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>