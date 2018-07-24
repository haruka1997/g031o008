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
        <link rel="stylesheet" href="./../css/search.css">

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
                    <div class="search-content">
                        <div class="search-title"><i class="fas fa-search"></i> 道の駅検索</div>
                        <div class="search-form">
                            <form type="post" action="searchList.php">
                                    <div class="search-form-item">
                                        <label for="stationName">名前</label>
                                        <input type="text" name="stationName" placeholder="例: 石鳥谷" style="margin-left:40px;"></input>
                                    </div>
                                    <div class="search-form-item">
                                        <label for="stationPlace">地域(市町村)</label>
                                        <input type="text" name="stationPlace" placeholder="例: 花巻"></input>
                                    </div>
                                    <div class="search-form-item">
                                        <label for="recommend">おすすめ品</label>
                                        <input type="text" name="recommend" placeholder="例: ラーメン"></input>
                                    </div>
                                    <div class="search-form-checkbox">
                                        <label for="condition[]">絞り込み条件:</label>
                                        <input type="checkbox" name="condition[]" value="directMarket">産直が充実している
                                        <input type="checkbox" name="condition[]" value="cafeteria">食堂が美味しい
                                        <input type="checkbox" name="condition[]" value="carNight">車中泊がしやすい
                                    </div>
                                    <div class="search-button">
                                        <button type="submit" name="search" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">検索</button>
                                        <button type="reset" name="clear" class="mdl-button mdl-js-button mdl-button--raised">クリア</button>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>