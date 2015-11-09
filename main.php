<?php
require_once 'lib/TwistOAuth.phar';
require_once 'config.php';
require_once 'token.php';

session_start();

if (!isset($_SESSION['logined'])) {
    $url = WEBSITE_URL;
    header("Location: $url");
    header('Content-Type: text/plain; charset=utf-8');
    exit("Redirecting to $url ...");
}

$code = 200;

try {
    $to = $_SESSION['to'];
    $user_statues = $to->get('account/verify_credentials');
} catch (TwistException $e) {

    $message = array('red', $e->getMessage());

    $code = $e->getCode() ?: 500;

}

header('Content-Type: text/html; charset=utf-8', true, $code);
?>
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
<script>
  $(function(){
    $('#container').masonry({
      itemSelector: '.box',
      columnWidth: 200 //一列の幅サイズを指定
    });
  });
</script>
<script type="text/javascript">
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": true,
  "progressBar": true,
  "positionClass": "toast-bottom-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "10000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "slideDown",
  "hideMethod": "slideUp"
}
</script>
<script type="text/javascript">
    var max_id;
    var min_id;
$(function() {
  toastr["info"]("初回は多めにツイートを読み込むため時間がかかります。もうしばらくお待ちください。", "Please Wait...");
  $('.status-text').text('読み込み中...');
  $.ajax({
    type: "GET",
    url: "ajax_query.php",
    data: { method: "ready" }
  }).done(function(data){
      var el = $(data);
      el.css('display','none');
      $("#container").append(el);
      $('#container').imagesLoaded(function(){
        el.css('display','inline');
        $('#container').masonry('reloadItems');
        $('#container').masonry({
          itemSelector: '.box',
          columnWidth: 10
        });
        $('.status-text').text('5分後に取得します');
        toastr["success"]("過去のツイートを読み込みました。", "Completed!");
      });
    }).error(function(data){
      toastr["error"]("短時間の集中した読み込みで制限がかかっています。数分経ってからお試しください。", "Error!");
      $('.status-text').text('5分後に取得します');
    });
  $('.more').click(function() {
      if($('.centered > .box').length){
          max_id = $('.centered > .box:last').attr('id');
          console.log(max_id);
      }
    $('.status-text').text('読み込み中...');
    $.ajax({
      type: "GET",
      url: "ajax_query.php",
      data: {
          method: "more",
          max_id : max_id
      }
    }).done(function(data){
        var el = $(data);
        el.css('display','none');
        $("#container").append(el);
        $('#container').imagesLoaded(function(){
          el.css('display','inline');
          $('#container').masonry('reloadItems');
          $('#container').masonry({
            itemSelector: '.box',
            columnWidth: 10
          });
          $('.status-text').text('5分後に取得します');
          toastr["success"]("過去のツイートを読み込みました。", "Completed!");
        });
      }).error(function(data){
        toastr["error"]("短時間の集中した読み込みで制限がかかっています。数分経ってからお試しください。", "Error!");
        $('.status-text').text('5分後に取得します');
      });
    });
setInterval(function(){
    if($('.centered > .box').length){
        min_id = $('.centered > .box:first').attr('id');
        console.log(min_id);
    }
    $('.status-text').text('読み込み中...');
    $.ajax({
        type: "GET",
        url: "ajax_query.php",
        data: {
            method: "new",
            min_id : min_id
        }
    }).done(function(data){
        var el = $(data);
        el.css('display','none');
        $("#container").prepend(el);
        $('#container').imagesLoaded(function(){
            el.css('display','inline');
            $('#container').masonry('reloadItems');
            $('#container').masonry({
                itemSelector: '.box',
                columnWidth: 10
            });
            $('.status-text').text('5分後に取得します');
            toastr["success"]("最新のツイートを読み込みました。", "Completed!");
        });
    }).error(function(data){
        toastr["error"]("短時間の集中した読み込みで制限がかかっています。数分経ってからお試しください。", "Error!");
        $('.status-text').text('5分後に取得します');
    });
},300000);
  $('.new').click(function() {
      if($('.centered > .box').length){
          min_id = $('.centered > .box:first').attr('id');
          console.log(min_id);
      }
    $('.status-text').text('読み込み中...');
    $.ajax({
      type: "GET",
      url: "ajax_query.php",
      data: {
          method: "new",
          min_id : min_id
      }
    }).done(function(data){
        var el = $(data);
        el.css('display','none');
        $("#container").prepend(el);
        $('#container').imagesLoaded(function(){
          el.css('display','inline');
          $('#container').masonry('reloadItems');
          $('#container').masonry({
            itemSelector: '.box',
            columnWidth: 10
          });
          $('.status-text').text('5分後に取得します');
          toastr["success"]("最新のツイートを読み込みました。", "Completed!");
        });
      }).error(function(data){
        toastr["error"]("短時間の集中した読み込みで制限がかかっています。数分経ってからお試しください。", "Error!");
        $('.status-text').text('5分後に取得します');
      });
    });
  });
</script>
<style type="text/css">
  body {
      padding-top: 48px;
      <?php echo 'background-color: #'.$user_statues->profile_background_color.';';?>
  }
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
    font-size: 24px;
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
        <p class="navbar-text navbar-right status-text">5分後に取得します</p>
        <li><a href="logout.php">ログアウト</a></li>
        <li><a href="#" data-toggle="modal" data-target="#myModal">ステータス</a></li>
        <li><a href="#" class="more">過去のツイートを読み込み</a></li>
        <li><a href="#" class="new">強制再読み込み</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">ステータス</h4>
      </div>
      <div class="modal-body">
<!-- settings -->
<div class="media">
  <div class="media-left">
    <a href="#">
      <img class="media-object" src="<?php echo h($user_statues->profile_image_url); ?>"  width="48px" alt="...">
    </a>
  </div>
  <div class="media-body">
    <h4 class="media-heading"><?php echo h($user_statues->screen_name); ?></h4>
      <?php echo h($user_statues->name); ?>さんとしてログインしています。ほかのアカウントに切り替える場合は一度ログアウトしてください。<br><br>
  </div>
長時間表示したままにすると、大変重くなるので一定時間ごとにリロードをかけてください。<br><br>
問題が発生している場合や機能のリクエストなどありましたらTwitterの<a href="https://twitter.com/MATTENN">@MATTENN宛にリプライをお送りください。</a><br>
メールの場合は<code>at.mattenn@gmail.com</code>宛に送信してください。（迷惑メールと間違えないためにもBunchoに関する件名の記入もお願いします。）

</div>
<!-- /settings -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
      </div>
    </div>
  </div>
</div>

<div id="container" class="centered"></div>
</body>
</html>
