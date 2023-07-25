<?php
require_once 'DAO.php';

class GoodsGroup
{
    public int $groupcode;      //商品分類コード
    public string $groupname;   //商品分類名
}

class GoodsGroupDAO
{   //DBから全商品グループを取得するメソッド
    public function get_goodsgroup()
    {
        //DBに接続
        $dbh = DAO::get_db_connect();

        //全商品グループを取得 SQL
        $sql = "SELECT * FROM GoodsGroup";
        $stmt = $dbh->prepare($sql);

        //SQLを実行
        $stmt->execute();

        //取得したデータをGoodsGroup クラスの配列にする
        $data = [];
        while($row = $stmt->fetchObject('GoodsGroup')){
            $data[] = $row;
        }
        return $data;
    }
}