<?php 
require_once 'DAO.php';
require_once 'CartDAO.php';
require_once 'SaleDetailDAO.php';

class SaleDAO
{
     //Saleテーブル最新のSaleNoを取得する
     private function get_saleno(){
        $dbh = DAO::get_db_connect();

        //Saleテーブルから、最新の販売番号を取得するSQL
        $sql = "SELECT IDENT_CURRENT('Sale') AS saleno";

        $stmt = $dbh->query($sql);

        $row = $stmt->fetchObject();
        return $row->saleno; //最新のsalenoを返す
    }
   
    //DBに購入データを追加
    public function insert(int $memberid, Array $cart_list)
    {
        //戻り値
        $ret = false;

        //DBに接続
        $dbh = DAO::get_db_connect();
        
        try{
            //トランザクションを開始
            $dbh->beginTransaction();

            //トランザクション終了までSale表を共有ロックする
            $sql = "SELECT * FROM Sale WITH (TABLOCK, HOLDLOCK)";
            $dbh->query($sql);
            
            //Saleテーブルに購入情報を追加するSQL
            $sql = "INSERT INTO sale (memberid, saledate)
                    VALUES(:memberid, :saledate)";
            $stmt = $dbh->prepare($sql);

            //現在時刻を取得
            $saledate = date('Y-m-d H:i:s');

            //SQLに変数の値を当てはめる
            $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);
            $stmt->bindValue(':saledate', $saledate, PDO::PARAM_STR);
            $stmt->execute();

            //最新のsalenoの値を取得
            $saleno = $this->get_saleno();
            $saleDetailDAO = new SaleDetailDAO();

            //カートの商品をSaleDeteilテーブルに追加
            foreach($cart_list as $cart){
                $saleDetail = new SaleDetail();

                $saleDetail->saleno = $saleno;
                $saleDetail->goodscode = $cart->goodscode;
                $saleDetail->num = $cart->num ;

                $saleDetailDAO->insert($saleDetail, $dbh);
            }

            //コミットしてトランザクションを終了
            $dbh ->commit();
            $ret = true;
        }
        //DB更新中に例外が発生したとき
        catch(PDOException $e){
            //ロールバックしてトランザクションを終了
            $dbh->rollBack();
            $ret = false;
        }

        return $ret;
    }   
}