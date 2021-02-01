<?php

    // 一度読み込みを行う
    require_once 'UserLogic.php';
    
    class general{

         /**
         *  ログイン処理
         *  @param string $email
         *  @param string $password
         *  @return bool $result
         */
        public function login($email,$password)
        {
            // 結果
            $result = false;
            // ユーザをemailから検索して取得
            $UserLogic = new UserLogic();
            $user = $UserLogic->getUserByEmail($email);

            if(!$user){
                $_SESSION['msg'] = 'emailが一致しません';
                $UserLogic = null;
                return $result;
            }
            $UserLogic = null;

            // パスワードの照会
            if (password_verify($password,$user['password'])){
                // ログイン成功
                session_regenerate_id(true);    // セッションを新たに作り直す
                $_SESSION['login_user'] = $user;
                $result = true;
                return $result;
            }

            $_SESSION['msg'] = 'パスワードが一致しません。';
            return $result;
        }

        
        /**
         *  ログインチェック
         *  @param void     // なし
         *  @return bool $result
         */
        public function checkLogin()
        {
            $result = false;

            // セッションにログインユーザが入ってない場合、false
            if (isset($_SESSION['login_user']) && $_SESSION['login_user']['id'] > 0){
                // セッションにログインユーザが入っている　かつ　セッションのログインユーザのIDが入っている場合
                return $result = true;
            }

            return $result;

        }


        /**
         * ログアウト処理
         */
        public function logout()
        {
            $_SESSION = array();
            session_destroy();

        }
    
    }


