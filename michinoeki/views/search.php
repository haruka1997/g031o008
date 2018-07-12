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
                    道の駅検索ページ(製作中...)
                </div>
            </main>
        </div>
    </body>
</html>