<?php
require_once 'lib/TwistOAuth.phar';
require_once 'config.php';
require_once 'token.php';

session_start();
$to = $_SESSION['to'];

try {
    if ($_GET['method'] == "more") {
        //=========================================================
        //過去ツイート読み込み時
        //=========================================================
        $timeline = $to->get('statuses/home_timeline',array('max_id' => $_GET['max_id'],'count' => 200));
    }else if($_GET['method'] == "ready"){
        //=========================================================
        //初回読み込み時
        //=========================================================
        $timeline = $to->get('statuses/home_timeline',array('count' => 120));
    }else{
        //=========================================================
        //最新ツイート読み込み時
        //=========================================================
        $timeline = $to->get('statuses/home_timeline',array('since_id' => $_GET['min_id'],'count' => 40));
    }

    // レスポンス確認(異常時にはcatchにジャンプするため、ここへの到達は成功を意味する)
    foreach($timeline as $i => $tweet){
        if(isset($_GET['max_id']) && $i == 0){
            continue;
        }
        if(isset($_GET['min_id']) > $tweet->id_str){
            //continue;
        }
        if (!empty($tweet->extended_entities->media[0])) {
            $picture = $tweet->extended_entities->media[0]->media_url;
        }
        if (isset($picture)) {
            foreach ($tweet->extended_entities->media as $key => $value) {
                if (!isset($tweet->retweeted_status)){
                    $screen_name = $tweet->user->screen_name;
                    $id_bigint = $tweet->user->id;
                    $profile_image_url = $tweet->user->profile_image_url;
                    $name = $tweet->user->name;
                }else{
                    $screen_name = $tweet->retweeted_status->user->screen_name;
                    $id_bigint = $tweet->retweeted_status->user->id;
                    $profile_image_url = $tweet->retweeted_status->user->profile_image_url;
                    $name = $tweet->retweeted_status->user->name;
                }
                echo '
                    <div class="box" id="'.$tweet->id_str.'">
                      <img src="'.$value->media_url.':orig" width="320px">
                      <div class="tweet-text">
                        <div class="media">
                          <div class="media-left media-middle">
                              <a href="'."https://twitter.com/".$screen_name.'">
                              <img class="media-object" width="32px" src="'.$profile_image_url.'">
                              </a>
                          </div>
                          <div class="media-body">
                              <b>'.$name.'</b>
                              <br>
                             '.$tweet->text.'
                          </div>
                        </div>
                      </div>
                    </div>
                    ';
                //echo storage_request($id_bigint,$tweet->id,$key,$value->media_url)."<br>";
                    //storage_request($id_bigint,$tweet->id,$key,$value->media_url)
            }
        }
    }
} catch (TwistException $e) {
    // エラーを表示
    echo "[{$e->getCode()}] {$e->getMessage()}";
    //header("HTTP/1.1 500 Internal Server Error");
    exit;
}
