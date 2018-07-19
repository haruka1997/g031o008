<?php
$search_list = [];  //検索結果
$display_list = []; //表示結果(重複削除)

    // エラー処理
    try {
        $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008; charset=utf8;', 'g031o008', 'g031o008');
        
        //もし駅名または駅の地域が検索されたら
        if(($_GET["stationName"] !== "") or ($_GET["stationPlace"] !== "")){
            $stmt = $dbh->prepare('SELECT stationId, stationName, stationPlace, stationImage FROM stationOverview WHERE stationName like "%":stationName"%" AND stationPlace like "%":stationPlace"%"');    //入力した駅名かつ駅の地域の情報を選択
            $stmt->bindParam(':stationName', $_GET['stationName'], PDO::PARAM_STR); //駅名
            $stmt->bindParam(':stationPlace', $_GET['stationPlace'], PDO::PARAM_STR);   //駅の地域
            $stmt->execute();   //実行

            if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {    //入力した駅名と駅の地域に一致するデータがあれば
               $search_list = $row; //検索リストに追加
               //おすすめ品情報取得
               foreach($search_list as $key => $value){
                $stmt = $dbh->prepare('SELECT recommendId, stationId, body FROM stationRecommend WHERE stationId = :stationId');    //取得した駅IDをもとにおすすめ品情報を取得
                $stmt->bindParam(':stationId', $search_list[$key]['stationId'], PDO::PARAM_INT);    //駅ID
                $stmt->execute();   //実行

                if ($recommend = $stmt->fetch(PDO::FETCH_ASSOC)) {    //取得した駅IDに一致するおすすめ品情報があれば
                    $search_list[$key]['recommendId'] = $recommend['recommendId'];  //検索リストにおすすめ品IDを追加
                    $search_list[$key]['recommend'] = $recommend['body'];   //検索リストにおすすめ品を追加
                }
               }
            } else {
                // 該当データなし
                $errorFlag = true;
                $errorMessage = '一致するデータがありません';   //エラー文
            }
        }

        //おすすめ品が検索されたら
        if($_GET["recommend"] !== ""){
            $stmt = $dbh->prepare('SELECT recommendId, stationId, body FROM stationRecommend WHERE body like "%":recommend"%"');    //入力したおすすめ品の情報を選択
            $stmt->bindParam(':recommend', $_GET['recommend'], PDO::PARAM_STR); //おすすめ品
            $stmt->execute();   //実行

            if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {    //入力したおすすめ品に一致するデータがあれば
                foreach($row as $key => $value){
                    // 駅情報の取得
                    $stmt = $dbh->prepare('SELECT stationId, stationName, stationPlace, stationImage FROM stationOverview WHERE stationId = :stationId');    //入力した駅IDに一致する駅情報を取得
                    $stmt->bindParam(':stationId', $row[$key]['stationId'], PDO::PARAM_INT);    //駅ID
                    $stmt->execute();   //実行

                    if ($station = $stmt->fetch(PDO::FETCH_ASSOC)) {    //入力した駅IDに一致する駅情報があれば
                        $row[$key]['stationName'] = $station['stationName'];
                        $row[$key]['stationPlace'] = $station['stationPlace'];
                        $row[$key]['stationImage'] = $station['stationImage'];
                    }
                    array_push($search_list, $value);
                }
                $stmt = null;
            } else {
                // 該当データなし
                $errorFlag = true;
                $errorMessage = '一致するデータがありません';   //エラー文
            }
        }

        // 条件検索されたら
       if($_GET["condition"] !== ""){
            foreach($_GET["condition"] as $key => $value){
                switch($value){
                    case 'directMarket': 
                        $stmt = $dbh->prepare('SELECT stationId FROM stationRate WHERE 4 < directMarket');    
                        break;
                    case 'cafeteria':
                        $stmt = $dbh->prepare('SELECT stationId FROM stationRate WHERE 4 < cafeteria');  
                        break;
                    case 'carNight':
                        $stmt = $dbh->prepare('SELECT stationId FROM stationRate WHERE 4 < carNight');
                        break;
                }
                $stmt->bindParam(':stationId', $search_list[$searchKey]['stationId'], PDO::PARAM_INT);    //駅ID
                $stmt->execute();   //実行
                if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {    //入力したおすすめ品に一致するデータがあれば
                    $search_list = $row;
                }
                $stmt = null;
            }

            //駅情報とおすすめ品の取得
            foreach($search_list as $key => $value){
                $stmt = $dbh->prepare('SELECT stationId, stationName, stationPlace, stationImage FROM stationOverview WHERE stationId = :stationId');    //入力した駅IDに一致する駅情報を取得
                $stmt->bindParam(':stationId', $search_list[$key]['stationId'], PDO::PARAM_INT);    //駅ID
                $stmt->execute();   //実行

                if ($station = $stmt->fetch(PDO::FETCH_ASSOC)) {    //入力した駅IDに一致する駅情報があれば
                    $search_list[$key]['stationName'] = $station['stationName'];
                    $search_list[$key]['stationPlace'] = $station['stationPlace'];
                    $search_list[$key]['stationImage'] = $station['stationImage'];
                }

                $stmt = null;

                $stmt = $dbh->prepare('SELECT recommendId, stationId, body FROM stationRecommend WHERE stationId = :stationId');    //取得した駅IDをもとにおすすめ品情報を取得
                $stmt->bindParam(':stationId', $search_list[$key]['stationId'], PDO::PARAM_INT);    //駅ID
                $stmt->execute();   //実行

                if ($recommend = $stmt->fetch(PDO::FETCH_ASSOC)) {    //取得した駅IDに一致するおすすめ品情報があれば
                    $search_list[$key]['recommendId'] = $recommend['recommendId'];  //検索リストにおすすめ品IDを追加
                    $search_list[$key]['recommend'] = $recommend['body'];   //検索リストにおすすめ品を追加
                }
            }
        }

       $search_list = dedepulication($search_list); //重複削除

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
                    </div>
                    <?php } ?>
                </div>
            </main>
        </div>
    </body>
</html>