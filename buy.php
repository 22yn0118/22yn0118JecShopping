<?php
    require_once './helpers/MemberDAO.php';
    require_once './helpers/SaleDAO.php';

    session_start();

   //未ログインのとき
   if(!empty($_SESSION['login'])){
        //login.phpに移動
        header('Location: login.php');
        exit;
    }

    //「購入する」ボタンをクリックせずにこのページを表示した場合はcart.phpにリダイレクト
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Location: cart.php');
        exit;
    }

    //ログイン中の会員データ取得
    $member = $_SESSION['member'];

    //会員のカートデータを取得
    $cartDAO = new CartDAO();
    $cart_list = $cartDAO->get_cart_by_memberid($member->memberid);

    //カートの商品をSaleテーブルに登録
    $saleDAO = new SaleDAO();
    $ret->insert($member->memberid, $cart_list);

    //購入処理が成功したとき
    if($ret === true){
         //会員のカードデータをすべて削除する
        $cart_list = $cartDAO ->delete_by_memberid($member->memberid);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>購入完了</title>
    </head>
    <body>
        <?php include 'header2.php' ?>

        <?php if($ret === true) :?>
            <p>購入が完了しました</p>
            <p><a href="index.php">トップページへ</a></p>
        <?php else: ?>
            <p>購入処理エラーが発生しました。カートページへ戻りもう一度やり直してください。</p>
            <P><a href="cart.php">カートページへ</a></P>
        <?php endif; ?> 
    </body>
</html>

    