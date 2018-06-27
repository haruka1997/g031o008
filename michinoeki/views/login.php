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
      <link rel="stylesheet" href="./../css/sign.css">
  </head>
  <body>
      <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
          <header class="mdl-layout__header">
              <div class="mdl-layout__header-row" >
                  <span class="mdl-layout-title"><i class="fas fa-home"></i>    道の駅情報システム</span>
                  <div class="mdl-layout-spacer"></div>
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
              <div class="form-wrapper">
                  <h1 class="sign-title">ログイン</h1>
                  <p>ログインエラー</p>
                  <form>
                    <div class="form-item">
                      <label for="userId"></label>
                      <input type="userId" name="userId" required="required" placeholder="ユーザID"></input>
                    </div>
                    <div class="form-item">
                      <label for="password"></label>
                      <input type="password" name="password" required="required" placeholder="パスワード"></input>
                    </div>
                    <div class="button-panel">
                      <input type="submit" class="sign-button" title="ログイン" value="ログイン"></input>
                    </div>
                  </form>
                  <div class="form-footer">
                    <p><a>新規登録</a></p>
                </div>
          </main>
        </div>
  </body>
</html>
