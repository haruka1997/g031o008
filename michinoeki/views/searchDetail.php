<?php
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
                                <img border="0" src="data:image/png;base64," width="240" style="max-height:260px;">
                            </div>
                            <!-- 評価 -->
                            <div class="station-rate">
                                <p id="info-title">評価 <i class="fas fa-pencil-alt"></i></p>
                            </div>
                        </div>
                        <div class="detail-info-right">
                            <!-- 駅名 -->
                            <div class="station-name"></div>
                            <!-- 基本情報 -->
                            <div class="station-overview">
                                <p id="info-title">基本情報 <i class="fas fa-pencil-alt"></i></p>
                                <div id="info-body"></div>
                            </div>
                            <!-- おすすめ品 -->
                            <div class="station-recommend">
                                <p id="info-title">おすすめ品 <i class="fas fa-pencil-alt"></i></p>
                                <div id="info-body"></div>
                            </div>
                            <!-- 口コミ -->
                            <div class="station-buzz">
                                <p id="info-title">口コミ <i class="fas fa-pencil-alt"></i></p>
                                <div id="info-body"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>