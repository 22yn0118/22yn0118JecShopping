<?php
require_once 'DAO.php';

class Goods
{
    public string $goodscode; //商品コード
    public string $goodsname; //商品名
    public int $price; //価格
    public string $detail; //商品詳細
    public int $groupcode; //商品グループコード
    public bool $recommend; //おすすめフラグ
    public string $goodsimage; //商品画像
}

//Goodsテーブルアクセス用クラス 
class GoodsDAO
{
    public function get_recommend_goods()
    {
        //DBに接続
        $dbh = DAO::get_db_connect();

        //Goodsテーブルからおすすめ商品を取得する
        $sql = 'SELECT * 
                FROM Goods
                WHERE recommend = 1';
        $stmt = $dbh->prepare($sql);

        //SQL実行
        $stmt->execute();

        //取得したデータを配列にする
        $data = [];
        while($row = $stmt->fetchObject('Goods')){
            $data[] = $row;
        }
        return $data;
    }

    //引数の商品グループの商品を取得する
    public function get_goods_by_groupcode(int $groupcode)
    {
        //DBに接続する
        $dbh = DAO::get_db_connect();

        $sql = "SELECT *
                FROM goods
                WHERE groupcode = :groupcode
                ORDER BY recommend DESC";
        $stmt = $dbh->prepare($sql);
        //SQLに当てはめる
        $stmt->bindValue(':groupcode', $groupcode, PDO::PARAM_INT);

        //SQLを実行
        $stmt->execute();

        //取得したデータをGoodsクラスの配列にする
        $data = [];
        while($row= $stmt->fetchObject('Goods')){
            $data[] = $row;
        }
        return $data;
    }

    //引数の商品グループの商品を取得する
    public function get_goods_by_goodscode(string $goodscode)
    {
        //DBに接続する
        $dbh = DAO::get_db_connect();
        $sql = "SELECT * 
                FROM goods
                WHERE goodscode = :goodscode
                ORDER BY recommend DESC";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':goodscode', $goodscode, PDO::PARAM_STR);
        $stmt->execute();
        //1件分のデータをGoodsクラスのオブジェクトとして取得する
        $goods = $stmt->fetchObject('Goods');
        return $goods;
    }
}

