<?php
require_once 'DAO.php';

class Member
{
    public int $memberid; //会員ID
    public string $email; //メールアドレス
    public string $membername; //会員名
    public string $zipcode; //郵便番号
    public string $address; //住所
    public string $tel; //電話番号
    public string $password;    //パスワード
}

class MemberDAO
    {
     //DBからメールアドレスとパスワードが一致する会員データを取得
     public function get_member(string $email, string $password)
     {
        $dbh = DAO::get_db_connect();

        //メールアドレスが一致する会員データを取得
        $sql = "SELECT *
                FROM  Member
                WHERE email = :email";
        $stmt = $dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        //SQLを実行
        $stmt->execute();

        //1件分のデータをMemberクラスのオブジェクトとして取得
        $member = $stmt->fetchObject('Member');

        //会員データが取得できたとき
        if($member !== false){
            //パスワードが一致するか検証
            if(password_verify($password, $member->password)){
                //会員データを返す
                return $member;
            }
        }
        return false;
     } 
     
     //会員データを登録する
     public function insert(Member $member)
     {
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * 
                FROM member 
                WHERE email = :email AND password = :password AND membername = :membername AND zipcode = :zipcode AND
                address = :address AND tel = :tel";
        $stmt = $dbh->prepare($sql);
        
        //パスワードをハッシュ化
        $password = password_hash($member->password, PASSWORD_DEFAULT);

        $stmt->bindValue(':email', $member->email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':membername', $member->membername, PDO::PARAM_STR);
        $stmt->bindValue(':zipcode', $member->zipcode, PDO::PARAM_STR);
        $stmt->bindValue(':address', $member->address, PDO::PARAM_STR);
        $stmt->bindValue(':tel', $member->tel, PDO::PARAM_STR);

        $stmt->execute();
     }

     //指定したメールアドレスの会員データが存在すればtrueを返す
     public function email_exists(string $email){

       //DBに接続する
       $dbh = DAO::get_db_connect();

       if(!$this->()){
        
            $sql = "INSERT INTO member
                 VALUES(:email)";
            $stmt = $dbh->prepare($sql);
       }

       //sqlに変数の値を当てはめる
       $stmt->bindValue(':email',  $email, PDO::PARAM_STR);

       //sqlを実行
       $stmt->execute();

       if($stmt->fetch() !== false){
           return true;
       }
       else{
           return false;
       }
    }

}