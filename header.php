<?php
    require_once './helpers/MemberDAO.php';

    //セッションの開始
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    //ログイン中のとき
    if(!empty($_SESSION['member'])){
        //セッション変数の会員を取得
        $member = $_SESSION['member'];
        
    }
    
    
?>
<header>
    <link href="css/HeaderStyle.css" rel="stylesheet">

    <div id="logo">
        <a href="index.php">
            <img src="images/JecShoppingLogo.jpg" alt="JecShoppingロゴ">
        </a>
    </div>
    
    <div id="link">
        <?php if(isset($member)) : ?>
            <?= $member->membername ?>さん
            <a href="cart.php">カート</a>
            <a href="logout.php">ログアウト</a>
            
        <?php else: ?>
            <a href="login.php">ログイン</a>
        <?php endif; ?>
    </div>
    <div id="clear">
        <hr>
    </div>
</header>