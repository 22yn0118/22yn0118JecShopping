<?php
    require_once './helpers/MemberDAO.php';

    //POSTメソッドでリクエストされたとき
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //入力された会員データを受け取る
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password2 =  $_POST['password2'];
        $membername =  $_POST['membername'];
        $zipcode =  $_POST['zipcode'];
        $address =  $_POST['address'];
        $tel1 =  $_POST['tel1'];
        $tel2 =  $_POST['tel2'];
        $tel3 =  $_POST['tel3'];

        $memberDAO = new MemberDAO();

        //入力値のバリデーション
        //メールアドレス
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errs['email'] = 'メールアドレスの形式が正しくありません。'; 
        }
        //メールアドレスが登録されているか
        elseif(email_exists($memberDAO email)){
            $errs['email'] = 'このメールアドレスは既に登録されています。';
        }

        //パスワードの文字数
        if(!preg_match('/¥A{4,}¥z/', $password)){
            $errs['password'] = 'パスワードは4文字以上で入力してください。';
        }
        //パスワードの一致
        elseif(strcmp($password,$password2) != 0){
            $errs['password'] = 'パスワードが一致しません。';
        }

        //名前
        if($membername === ''){
            $errs['membername'] = 'お名前を入力して下さい。';
        }

        //郵便
        if(preg_match("/^d{3}-d{4}$/",$zipcode)){
            $errs['zipcode'] = '郵便番号は3桁~4桁で入力して下さい。';
        }

        //住所
        if($address === ''){
            $errs['address'] = '住所を入力して下さい。';
        }

        //電話
        if(!preg_match('/¥A(¥d{2,5})?¥z/', $tel1) || 
            preg_match('/¥A(¥d{1,4})?¥z/', $tel2) ||
            preg_match('/¥A(¥d{4})?¥z/', $tel3)){
                $errs['tel'] = '電話番号は半角数字2~5桁、1~4桁、4桁で入力してください';
            }

        if(empty($errs)){
            
            $member = new Member();
            $member-> email = $email;
            $member->password = $password;
            $member->membername = $membername;
            $member->zipcode = $zipcode;
            $member->address = $address;

            //電話番号ハイフン(-)で連絡
            $member->tel ='';
            if($tel1 !== '' && $tel2 !== '' && $tel3 !== ''){
                $member->tel = "{$tel1}-{$tel2}-{$tel3}";
            } 
            
            $memberDAO->insert($member);
            header('Location: signupEnd.php');
            exit;
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>新規会員登録</title>
    </head>
    <body>
        <?php include 'header2.php'; ?>

        <h1>会員登録</h1>
        <P>以下の項目を入力し、登録ボタンをクリックしてください（*は必須）</P>

        <form action="" method="POST">
            <table>
                <tr>
                    <td>メールアドレス*</td>
                    <td><input type="text" name="email"></td>
                    <span style="color:red"><?= @$errs['email'] ?></span>
                </tr>
                <tr>
                    <td>パスワード（４文字以上）*</td>
                    <td><input type="text" name="password"></td>
                    <span style="color:red"><?= @$errs['password'] ?></span>
                </tr>
                <tr>
                    <td>パスワード(再入力)*</td>
                    <td><input type="text" name="password2"></td>
                    <span style="color:red"><?= @$errs['password2'] ?></span>
                </tr>
                <tr>
                    <td>お名前*</td>
                    <td><input type="text" name="membername"></td>
                    <span style="color:red"><?= @$errs['membername'] ?></span>
                </tr>
                <tr>
                    <td>郵便番号*</td>
                    <td><input type="text" name="zipcode"></td>
                    <span style="color:red"><?= @$errs['zipcode'] ?></span>
                </tr>
                <tr>
                    <td>住所*</td>
                    <td><input type="text" name="address"></td>
                    <span style="color:red"><?= @$errs['address'] ?></span>
                </tr>
                <tr>
                    <td>電話番号</td>
                    <td>
                        <input type="text" name="tel1" size="4">-
                        <input type="text" name="tel2" size="4">-
                        <input type="text" name="tel3" size="4">
                    </td>
                    <td><span style="color:red"><?= @$errs['tel'] ?></span></td>
                </tr>
            </table>
                <input type="submit" value="登録する">
        </form>
    </body>
</html>