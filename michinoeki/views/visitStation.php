<?php
session_start();

// 訪問した駅の取得
try {
    $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008', 'g031o008', 'g031o008');

    $stmt = $dbh->prepare('SELECT stationId, visitDate, category, memo FROM visitStation WHERE userId = :userId');    //ユーザIDに合致するユーザ情報の取得
    $stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_STR);
    $stmt->execute();
    if ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {    //ユーザ情報がDBにあれば
        $visitStation = $row;

        // 駅名の取得
        foreach($visitStation as $key => $value){
            try {
                $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008', 'g031o008', 'g031o008');
            
                $stmt = $dbh->prepare('SELECT stationName FROM stationOverview WHERE stationId = :stationId');    //ユーザIDに合致するユーザ情報の取得
                $stmt->bindParam(':stationId', $visitStation[$key]['stationId'], PDO::PARAM_STR);
                $stmt->execute();
                if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {    //ユーザ情報がDBにあれば
                    $visitStation[$key]['stationName'] = $row['stationName'];
                } else {
                    // 該当データなし
                    $errorFlag = true;
                    print_r('情報取得エラーです'); 
                }
            } catch (PDOException $e) {
                $errorFlag = true;
                $errorMessage = 'データベースエラー';
            }
        }
        $php_json = json_encode($visitStation);
    } else {
        // 該当データなし
        $errorFlag = true;
        print_r('情報取得エラーです'); 
    }
} catch (PDOException $e) {
    $errorFlag = true;
    $errorMessage = 'データベースエラー';
}

// 更新ボタンクリック
if (isset($_POST["regist"])) {
    try {
        $dbh = new PDO('mysql:host=153.126.145.118; dbname=g031o008', 'g031o008', 'g031o008');
    
        $stmt = $dbh->prepare('UPDATE visitStation SET visitDate = :visitDate, category = :category, memo = :memo WHERE stationId = :stationId AND userId = :userId');    //ユーザIDに合致するユーザ情報の取得
        $stmt->bindParam(':visitDate', $_POST['visitDate'], PDO::PARAM_STR);
        $stmt->bindParam(':category', $_POST['category'], PDO::PARAM_STR);
        $stmt->bindParam(':memo', $_POST['memo'], PDO::PARAM_STR);
        $stmt->bindParam(':stationId', $_POST['regist'], PDO::PARAM_STR);
        $stmt->bindParam(':userId', $_SESSION['userId'], PDO::PARAM_STR);
        print_r($stmt);
        $flag = $stmt->execute();
        print_r($flag);
        if ($flag) {    //ユーザ情報がDBにあれば
            header("Location: " . 'visitStation.php');
        } else {
            // 該当データなし
            $errorFlag = true;
            print_r('情報取得エラーです'); 
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
        <title>訪問駅管理</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
        <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
        <script src="./../lib/jquery.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

        <link rel="stylesheet" href="./../css/main.css">
        <link rel="stylesheet" href="./../css/visitStation.css">

        <script>
            var js_array = JSON.parse('<?php echo $php_json; ?>');
            $(function(){
                // マップの色付け
                for(var key in js_array){
                    if(js_array[key]['visitDate'] !== "0000-00-00"){    //訪問済み
                        $('.map i.fas').eq(Number(js_array[key]['stationId'])-1).css('color', 'red');
                    }
                }

                // 選択フラグの情報表示
                $('.map i.fas').click(function () {
                    // マップの初期化
                    var showData = {};
                    $('.map i.fas').css('border', 'none');  //フラグ未指定状態

                    // マップのフラグ色を変更
                    $(this).css('border', '2px solid red');

                    var index = $('.map i.fas').index(this)+1;
                    for(var key in js_array){
                        if(index == js_array[key]['stationId']){
                            showData = js_array[key];
                            break;
                        }
                    }
                    // データ表示
                    $("#visitDate").val(showData.visitDate);
                    $("#category").val(showData.category);
                    $("#memo").val(showData.memo);
                    $(".edit-form-header label").text(showData.stationName);

                    // formのnameにstationIdを設定
                    $('.regist').val(showData.stationId);
                });
            });
        </script>

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
                    <div class="visit-station">
                        <div class="map">
                            <img src="./../img/map-iwate.png" width="500"/>
                            <i class="fas fa-flag" id="one"></i>
                            <i class="fas fa-flag" id="two"></i>
                            <i class="fas fa-flag" id="three"></i>
                            <i class="fas fa-flag" id="four"></i>
                            <i class="fas fa-flag" id="five"></i>
                            <i class="fas fa-flag" id="six"></i>
                            <i class="fas fa-flag" id="seven"></i>
                            <i class="fas fa-flag" id="eight"></i>
                            <i class="fas fa-flag" id="nine"></i>
                            <i class="fas fa-flag" id="ten"></i>
                            <i class="fas fa-flag" id="eleven"></i>
                            <i class="fas fa-flag" id="twelve"></i>
                            <i class="fas fa-flag" id="thirteen"></i>
                            <i class="fas fa-flag" id="fourteen"></i>
                            <i class="fas fa-flag" id="fifteen"></i>
                            <i class="fas fa-flag" id="sixteen"></i>
                            <i class="fas fa-flag" id="seventeen"></i>
                            <i class="fas fa-flag" id="eighteen"></i>
                            <i class="fas fa-flag" id="nineteen"></i>
                            <i class="fas fa-flag" id="twenty"></i>
                            <i class="fas fa-flag" id="twenty-one"></i>
                            <i class="fas fa-flag" id="twenty-two"></i>
                            <i class="fas fa-flag" id="twenty-three"></i>
                            <i class="fas fa-flag" id="twenty-four"></i>
                            <i class="fas fa-flag" id="twenty-five"></i>
                            <i class="fas fa-flag" id="twenty-six"></i>
                            <i class="fas fa-flag" id="twenty-seven"></i>
                            <i class="fas fa-flag" id="twenty-eight"></i>
                            <i class="fas fa-flag" id="twenty-nine"></i>
                            <i class="fas fa-flag" id="thirty"></i>
                            <i class="fas fa-flag" id="thirty-one"></i>
                            <i class="fas fa-flag" id="thirty-two"></i>
                            <i class="fas fa-flag" id="thirty-three"></i>
                        </div>
                        <div class="edit-form">
                            <div class="edit-form-header"><label></label></div>
                            <form method="post">
                                <div class="edit-form-content">
                                    <div class="edit-form-item">
                                        <p>訪問日時</p>
                                        <input id="visitDate" type="date" name="visitDate"/>
                                    </div>
                                    <div class="edit-form-item"> 
                                        <p>スタンプラリーの種類</p>
                                        <input id="category" type="text" name="category"/> 
                                    </div>
                                    <div class="edit-form-item">
                                        <p>メモ</p>
                                        <textarea id="memo" type="text" name="memo" rows="10" cols="80"></textarea>
                                    </div>
                                </div>
                                <!-- 編集ボタン -->
                                <div class="regist-button">
                                    <button type="submit" name="regist" class="regist mdl-button mdl-js-button mdl-button--raised mdl-button--colored">更新</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>