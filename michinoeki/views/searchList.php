<?php
$search_list = [];  //検索結果

    // エラー処理
    try {
        $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
        $sql =  'SELECT * FROM stationOverview AS overview LEFT OUTER JOIN stationRecommend AS recommend ON overview.stationId = recommend.stationId LEFT OUTER JOIN stationRate AS rate ON overview.stationId = rate.stationId
        WHERE overview.stationName LIKE "%":stationName"%" AND overview.stationPlace LIKE "%":stationPlace"%"';

        //おすすめ品が検索されたら
        if($_GET["recommend"] !== ""){
            $sql = $sql .'AND recommend.recommendBody LIKE "%":recommend"%"';
        }
        // 絞り込み条件が検索されたら
        if($_GET["condition"] !== ""){
            foreach($_GET["condition"] as $key => $value){
                switch($value){
                    case 'directMarket': 
                        $sql = $sql .'AND rate.directMarket > 4';    
                        break;
                    case 'cafeteria':
                        $sql = $sql .'AND rate.cafeteria > 4';
                        break;
                    case 'carNight':
                        $sql = $sql .'AND rate.carNight > 4';
                        break;
                }
            }
        }
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':stationName', $_GET['stationName'], PDO::PARAM_STR); //駅名
        $stmt->bindParam(':stationPlace', $_GET['stationPlace'], PDO::PARAM_STR);   //駅の地域
        if($_GET["recommend"] !== ""){
            $stmt->bindParam(':recommend', $_GET['recommend'], PDO::PARAM_STR);   //おすすめ品
        }
        $stmt->execute();   //実行

        if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {   //一致するデータがあれば
            $search_list = dedepulication($row);   //重複削除

            //各駅のおすすめ情報の全件取得
            foreach($search_list as $key => $value){
                $stmt = $dbh->prepare('SELECT * FROM stationRecommend WHERE stationId = :stationId;');    //駅IDに合致するおすすめ品情報の取得
                $stmt->bindParam(':stationId', $search_list[$key]['stationId'], PDO::PARAM_STR);   //駅ID
                $stmt->execute();   //実行

                if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {    //一致するデータがあれば
                    foreach($row as $rowKey => $rowValue){
                        $search_list[$key]['recommend'] = $row[$rowKey]['recommendBody'] .", " .$search_list[$key]['recommend'];  //おすすめ情報の追記
                    }
                    // 末尾のカンマ削除
                    $search_list[$key]['recommend'] = rtrim($search_list[$key]['recommend'], ", ");
                }
            }
        }
    } catch (PDOException $e) {
        $errorFlag = true;
        $errorMessage = 'データベースエラー';
    }

    // 重複した検索情報を削除
    function dedepulication($search_list){
        $tmp = [];
        $depulication = [];
        foreach ($search_list as $arr){
            if (!in_array($arr['stationId'], $tmp)) {
                $tmp[] = $arr['stationId'];
                $depulication[] = $arr;
            }
        }
        return $depulication;
    }
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>道の駅検索</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

        <link rel="stylesheet" href="./../css/main.css">
        <link rel="stylesheet" href="./../css/searchList.css">

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
                    <div class="search-list-header">
                        <div class="search-count"><?= count($search_list) ?>件</div>
                        <form action="search.php">
                            <div class="search-button">
                                <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">再検索</button>
                            </div>
                        </form>
                    </div>
                    <?php foreach($search_list as $key => $value){ ?>
                    <form action="searchDetail.php" type="post">
                        <div class="search-list">
                            <div class="station-image">
                                <img border="0" src="<?= $search_list[$key]["stationImage"] ?>">
                            </div>
                            <div class="station-explain">
                                <div class="station-name"><?= $search_list[$key]["stationName"] ?> </div>
                                <div class="station-body">
                                    <p>住所：<?= $search_list[$key]["stationPlace"] ?> </p>
                                    <p>おすすめ品：<?= $search_list[$key]["recommend"] ?> </p>
                                </div>
                            </div>
                            <div class="detail-link">
                                <a href="./searchDetail.php?stationId=<?= $search_list[$key]['stationId'] ?>">詳細</a>
                            </div>
                        </div>
                    </form>
                    <?php } ?>
                </div>
            </main>
        </div>
    </body>
</html>