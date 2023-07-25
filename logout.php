<?php
    //セッションを開始
    session_start();

    //セッション名を取得
    $session_name = session_name();

    //Cookieを削除
    if(isset($_COOKIE[$session_name])){
        setcookie($session_name, '', time()-3600);
    }
    //セッションデータを破棄
    session_destroy();

    //index.phpへリダイレクト
    header('Location: index.php');
    exit;
?>
