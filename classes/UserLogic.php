<?php

require_once '../dbconnect.php';

class UserLogic
{
    /**
     * ユーザーを登録する
     * @param array $userData
     * @return bool $result
     */
    public static function createUser($userData){
        $result = false;
        $sql = 'INSERT INTO users (name,email,password) VALUES (?,?,?)';

        //ユーザーデータを配列に入れる

        $arr = [];
        $arr[] =  $userData['username'];
        $arr[] = $userData['email'];
        $arr[] = password_hash($userData['password'],
        PASSWORD_DEFAULT);
        
        try{
            $stmt = connect()->prepare($sql);
            $result = $stmt->execute($arr);
            return $result;

        }catch(\Exception $e){
            return $result;
        }
    }

    /**
     * ログイン処理
     * @param stirng $emai;
     * @param string $password
     * @return bool $result
     */
    public static function login($email,$password){

        //結果
        $result = false;
        //ユーザーをemailから検索して取得する
        $user = self::getUserByEmail($email);


        if(!$user){
            $_SESSION['msg'] = 'emailが一致しません。';
            return $result;
        }


        
        //パスワードの照会
        if (password_verify($password,$user['password'])){
            // ログイン成功
            session_regenerate_id(true);
            $_SESSION['login_user'] = $user;
            $result = true;
        }
        $_SESSION['msg'] = 'パスワードが一致しません。';
        return $result;

    }

    /**
     * emailからユーザーを取得
     * @param stirng $emai;
     * @return arra bool $user false
     */
    public static function getUserByEmail($email){

        //SQLの準備
        //SQLの実行
        //SQLの結果を返す
        $result = false;
        $sql = 'SELECT * FROM users WHERE email = ?';

        //ユーザーデータを配列に入れる

        //emailを配列に入れる
        $arr = [];
        $arr[] = $email;
    
        
        try{
            $stmt = connect()->prepare($sql);
            $stmt->execute($arr);
            //SQLの結果を返す
            $user = $stmt->fetch();
            return $user;

        }catch(\Exception $e){
            return $result;
        }
    }
    
}

?>