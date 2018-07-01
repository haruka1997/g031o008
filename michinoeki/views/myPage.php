<?php

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
        <link rel="stylesheet" href="./../css/myPage.css">
    </head>
    <body>
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row" >
                    <span class="mdl-layout-title"><i class="fas fa-home"></i>    道の駅情報システム</span>
                    <div class="mdl-layout-spacer"></div>
                    <nav class="mdl-navigation mdl-layout--large-screen-only">
                        <!-- 変更(7/1): ログアウトボタンの追加 -->
                        <div class="logout-button">
                            <p><a href="./login.php">ログアウト</a></p>
                        </div>
                    </nav>
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
                <div class="page-content">
                    <div class="profile">
                        <div class="profile-image">
                            <img border="0" src="./../img/profile-image.png" width="250" height="250">
                        </div>
                        <div class="profile-table">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th>ユーザ名</th>
                                    <td>ＴＥＸＴ</td>
                                </tr>
                                <tr>
                                    <th>居住地</th>
                                    <td>ＴＥＸＴ</td>
                                </tr>
                                <tr>
                                    <th>訪問駅数</th>
                                    <td>ＴＥＸＴ</td>
                                </tr>
                                <tr>
                                    <th>入手切符数</th>
                                    <td>ＴＥＸＴ</td>
                                </tr>
                                <tr>
                                    <th>自己紹介</th>
                                    <td style="height:200px;">ＴＥＸＴ</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="message"></div>
                </div>
            </main>
        </div>
    </body>
</html>
