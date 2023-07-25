<?php
    require_once 'DAO.php';

    Class Cart
    {
        public int      $memberid;    //会員ID
        public string   $goodscode;   //商品コード
        public string   $goodsname;   //商品名
        public int      $price;       //価格
        public string   $detail;      //商品詳細
        public string   $goodsimage;  //商品画像
        public int      $num;         //数量
    }

    Class CartDAO
    {
        //会員のカートデータを取得
        public function get_cart_by_memberid(int $memberid)
        {
            //DBに接続
            $dbh = DAO::get_db_connect();
            $sql = "SELECT *
                    FROM Cart 
                    INNER JOIN Goods ON Cart.goodscode = Goods.goodscode
                    WHERE memberid =  :memberid ";
            $stmt = $dbh->prepare($sql);

            //SQLに変数の値を当てはめる
            $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);
            $stmt->execute();

            //取得したデータをCartクラスの配列にする
            $data = [];
            while($row = $stmt->fetchObject('Cart')){
                $data[] = $row;
            }
            return $data;
        }

         //指定した商品がカートテーブルに存在するか確認する
        public function cart_exists(int $memberid, string $goodscode)
        {
            //DBに接続する
            $dbh = DAO::get_db_connect();

            $sql = "SELECT *
                    FROM cart
                    WHERE memberid = :memberid AND goodscode = :goodscode";
            $stmt = $dbh->prepare($sql);

            //sqlに変数の値を当てはめる
            $stmt->bindValue(':memberid',  $memberid, PDO::PARAM_INT);
            $stmt->bindValue(':goodscode', $goodscode, PDO::PARAM_STR);

            //sqlを実行
            $stmt->execute();

            if($stmt->fetch() !== false){
                return true;
            }
            else{
                return false;
            }
        } 

        //カートテーブルに商品追加
        public function insert(int $memberid, string $goodscode, int $num)
        {
            $dbh = DAO::get_db_connect();

            //カートテーブルに同じ商品がないとき
            if(!$this->cart_exists($memberid, $goodscode)){
               //カートに商品を登録する
               $sql = "INSERT INTO cart
                        VALUES(:memberid, :goodscode, :num)";
                $stmt = $dbh->prepare($sql);

                $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);
                $stmt->bindValue(':goodscode', $goodscode, PDO::PARAM_STR); 
                $stmt->bindValue(':num', $num, PDO::PARAM_INT);
                $stmt->execute();
            }
            //カートテーブルに同じ商品があるとき
            else{
                //カートテーブルに商品個数を加算
                $sql = "UPDATE cart
                        SET num =num + :num
                        WHERE memberid =:memberid AND goodscode = :goodscode";
                $stmt = $dbh->prepare($sql);

                $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);
                $stmt->bindValue(':goodscode', $goodscode, PDO::PARAM_STR); 
                $stmt->bindValue(':num', $num, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
        
        //カートテーブルの商品個数を変更
        public function update(int $memberid, string $goodscode, int $num)
        {
            //DBに接続する
            $dbh = DAO::get_db_connect();

            $sql = "UPDATE cart
                    SET num = :num
                    WHERE memberid = :memberid AND goodscode = :goodscode";
            $stmt = $dbh->prepare($sql);

            //sqlに変数の値を当てはめる
            $stmt->bindValue(':memberid',  $memberid, PDO::PARAM_INT);
            $stmt->bindValue(':goodscode', $goodscode, PDO::PARAM_STR);
            $stmt->bindValue(':num',  $num, PDO::PARAM_INT);
            //sqlを実行
            $stmt->execute();
        }

        //カートテーブルから商品を削除する
        public function delete(int $memberid, string $goodscode)
        {
            //DBに接続する
            $dbh = DAO::get_db_connect();

            $sql = "DELETE  FROM Cart
                    WHERE goodscode = :goodscode  AND memberid = :memberid";
            $stmt = $dbh->prepare($sql);

            //sqlに変数の値を当てはめる
            $stmt->bindValue(':memberid',  $memberid, PDO::PARAM_INT);
            $stmt->bindValue(':goodscode', $goodscode, PDO::PARAM_STR);
            //sqlを実行
            $stmt->execute();
        }

        //会員のカート情報をすべて削除する
        public function delete_by_memberid(int $memberid){
        $dbh = DAO::get_db_connect();
        $sql = "DELETE FROM cart WHERE memberid = :memberid";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);
        $stmt->execute();
       }
    }

   
