<?php
    require_once './helpers/CartDAO.php';
    require_once './helpers/MemberDAO.php';

    //セッションスタート
    session_start();

    //未ログインのとき
    if(!empty($_SESSION['login'])){
        //login.phpに移動
        header('Location: login.php');
        exit;
    }

    //ログイン中の会員情報取得
    $member = $_SESSION['member'];

    //POSTメソッドでリクエストされたとき
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //カートに追加商品コードと数量を受け取る
        if(isset($_POST['add'])){
            $goodscode = $_POST['goodscode'];
            $num       = $_POST['num'];

            //カートに商品を追加する
            $cartDAO =new CartDAO();
            $cartDAO->insert($member->memberid, $goodscode, $num);
        }
        //「変更」ボタンがクリックされたとき
        else if(isset($_POST['change'])){
            //変更する商品コードと数量を受け取る
            $goodscode = $_POST['goodscode'];
            $num       = $_POST['num'];

            //DBの数量を変更する
            $cartDAO =new CartDAO();
            $cartDAO->update($member->memberid, $goodscode, $num);
        }
        //「削除」ボタンがクリックされたとき
        else if(isset($_POST['delete'])){
            //削除する商品コードを受け取る
            $goodscode = $_POST['goodscode'];
            //DBの商品を削除
            $cartDAO = new CartDAO();
            $cartDAO ->delete($member->memberid, $goodscode);
        }

        //自身のページへリダイレクト
        //header("Location:" .$_SERVER['PHP_SELF']);
        //exit;
    }
    
    //DBから会員のカートデータを取得
    $cartDAO = new CartDAO();
    $cart_list = $cartDAO->get_cart_by_memberid($member->memberid);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ショッピングカート</title>
</head>
<body>
    <?php include "header.php" ?>

    <?php if(empty($cart_list)) : ?>
        <P>カートに商品はありません。</P>
    <?php else: ?>
        <?php foreach($cart_list as $cart)  : ?>
            <table>
                <tr>
                    <td rowspan="4">
                        <img src="images/goods/<?= $cart->goodsimage?>">
                    </td>
                    <td>
                        <?= $cart->goodsname ?>
                    </td>
                </tr>
                <tr>
                    <td>
                       <?= $cart->detail ?> 
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= number_format($cart->price) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <form action=""  method="POST">
                            数量
                            <input type="text" name="num" value= "<?= $cart->num ?>" >
                            <input type="hidden"  name="goodscode" value="<?=$cart->goodscode?>">
                            <input type="submit" name="change" value="変更">
                            <input type="submit" name="delete" value="削除">
                        </form>
                    </td>
                </tr>
            </table>
            <hr>
        <?php endforeach; ?>
        <form action="buy.php" method="POST">
            <input type="submit" value="商品を購入する">
        </form>
    <?php endif; ?> 
</body>
</html>
