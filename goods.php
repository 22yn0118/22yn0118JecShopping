<?php 
    require_once './helpers/GoodsDAO.php';

    //URLリクエストパラメータの商品コードがセットされているとき
    if(isset($_GET['goodscode'])){
        //リクエストパラメータの商品コードを取得
        $goodscode = $_GET['goodscode'];

        //DBから商品データを取得
        $goodsDAO = new GoodsDAO();
        $goods = $goodsDAO->get_goods_by_goodscode($goodscode);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>商品詳細</title>
</head>
<body>
    <?php include "header.php" ?>

    <table>
        <tr>
            <td rowspan="5">
                <img src="images/goods/<?= $goods->goodsimage ?>">
            </td>
            <td>
                <?= $goods->goodsname ?>
            </td>
        </tr>
        <tr>
            <td>
                <?= $goods->detail ?>
            </td>
        </tr>
        <tr>
            <td>
                ￥<?= number_format($goods->price) ?>
            </td>
        </tr>
        <tr>
            <td>
                <?= $goods->recommend? "おすすめの商品" : " " ?>
            </td>
        </tr>
        <tr>
            <td>
                <form action="cart.php" method="POST">
                    個数
                    <select name="num">
                        <?php for($i = 1; $i <= 10; $i++) : ?>
                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                        <?php endfor; ?>
                    </select>

                    <input type="hidden" name="goodscode" value="<?= $goodscode?>">
                    <input type="submit" name="add" value="カートに入れる">
                </form>
            </td>
        </tr>
    </table>
</body>
</html>
