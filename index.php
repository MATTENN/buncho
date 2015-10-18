<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8" />
    <title>Buncho v2 - 画像だけを見れるTwitterクライアント。</title>
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="lib/masonry.pkgd.min.js"></script>
    <script src="lib/imagesloaded.pkgd.min.js" type="text/javascript"></script>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <style type="text/css">
        body { padding-top: 48px; }
        #container{
            margin: 0 auto;
        }
        .box{
            background-color: #FFF;
            margin: 10px;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.4);
            width: 320px;
        }
        .tweet-text{
            padding: 3px;
            font-family: "メイリオ";
            font-size: 0.9em;
        }
        .title-unit{
            background-color: #AAA;
            padding: 20px;
            padding-top: 40px;
            padding-bottom: 40px;
        }
        .title-text{
            font-family: "メイリオ";
            font-size: 18px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Buncho v2</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="login.php">ログイン</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<div class="title-unit">
    <div class="container">
        <img src="bunchov2.png" width="70%">
    </div>
</div>
<div class="container" style="margin-top: 20px;">
    <div class="title-text">
        Buncho v2とはTwitterのタイムラインに流れる画像を眺めることができるWebサービスです。<br>
        お手持ちのアカウントからすぐに始めることができます。<br>
        <br><h1>はじめかた</h1>
        <ol>
            <li>右上のボタンからログインします。（勝手にツイートやフォローすることはありません）</li>
            <li>楽しい!!✌('ω')✌三✌('ω')✌三✌('ω')✌</li>
        </ol>
        <br><h1>更新情報</h1>

        <h2>2015/6/5 0:38</h2>
        <b>サーバーを移転しました。</b>自宅サーバー不調のためMicrosoft Azureに移転しています。内部の処理を簡略化しているため、<b>表示が高速化しました。</b>また、アプリケーションの表示も「Buncho v2」から「Buncho v2 for Azure」に変更されています。<br>
        <h2>2015/4/12 1:01 Buncho v2.1.1</h2>
        <b>画像が表示されない問題を修正しました。</b>内部で使用しているライブラリ「medoo」の扱い方を間違え、最新のトークンが取得できなかったため、トークンの有効期限が切れて画像が表示されませんでした。再度修正を行い現在は正しく表示されるようになっています。<br>
        <h2>2015/4/9 20:14 Buncho v2.1</h2>
        <b>画像が表示されない問題を修正しました。</b>内部で使用しているConoHaオブジェクトストレージへの接続に失敗してキャッシュが正しく行われないため、画像が表示されていませんでした。一定時間ごとにトークン(オブジェクトストレージに接続するために必要な資格情報)の更新を行うようになっています。<br>
        <br><h1>仕様</h1>
        このWebサービスは<s>福岡県福岡市に設置された自宅サーバー</s>Microsoft Azureより配信しています。<br>
        <s>また、画像の配信はConoHa オブジェクトストレージを使用しています。</s><br>
        ロゴの原案を<a href="https://twitter.com/tsbm75">tsbm75</a>氏に作っていただきました。ありがとうございます。
        <br><br><h1>Ｑ＆Ａ</h1>
        <b>yflogやimg.lyなどの画像が表示されないのは何故ですか?</b><br>
        Twitter公式の画像のみ対応しているためです。ほかのサービスには対応していません。
        <br>
        <b><u>エラー表示もないのに</u>画像が１件も表示されない</b><br>
        タイムライン上の最新ツイート120件から画像を抽出するため、<b>120件の中に画像がない場合は表示されません。</b><br>
        読み込み件数を増やすと初回の読み込みに時間がかかるため、初回の読み込みに限り最新から120件を読み込んでいます。<br>
        なお、「強制再読み込み」と自動再読み込みは40件、「過去のツイートを読み込み」は200件(一度に取得できるTwitter APIの上限)を読み込んでいます。
        <br>
        <b>精神的にアレな画像が流れてきたのですが。</b><br>
        現在非表示にする機能はまだ実装できていません。Twitterでリムるかブロックしてください。
        <br>
        <b>これってpict-streamのパクリじゃん</b><br>
        はい。もともとpict-streamを使っていたのですがいつからか使えなくなったため、自分で作ったものがこちらになります。もしsaqooshaさんにお会いする機会がありましたら<a href="http://www.ryoyupan.co.jp/manhattan.html">マンハッタン</a>をお持ちしますので、それでお許しいただければと思います。
        <br>
        <br><h1>Buncho v1（初期）との変更点</h1>
        <ul>
            <li>サーバー等がすべて変更されています。</li>
            <li>画像がスクエア表示ではなくフルサイズで表示され、ツイートやアイコンが表示されるなど、もととなったpict-streamに近づいた表示になっています。</li>
            内部は以前から引き継いだものはなくすべて変更されています。詳しい内容に関してはブログの記事をご覧ください。
        </ul>
        <br><h1>何か要望がありますか?</h1>
        要望などの意見に関してはTwitter : <a href="https://twitter.com/MATTENN">@MATTENN</a>で受け付けています。<br>
        遠慮なくツイートを飛ばしてください。<br>またこのサービスは各種ニュースサイトで掲載しても構いません。どうぞ紹介してください。
    </div>
</div>
<div id="container" class="centered"></div>
</body>
</html>
