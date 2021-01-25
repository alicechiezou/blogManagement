<?php
    // 一度読み込みを行う
    require_once '../dbconnect.php';

    class UserLogic
    {
        /**
         * E-mailが一致するユーザの存在チェック、存在すればエラー
         * @param  string @email
         * @return bool $result
         */
        public function emailExists($email)
        {
            $dbh = connect();

            $sql="select count(id) from users where email=:email";
            $stmt=$dbh->prepare($sql);
            $stmt->bindvalue(':email',$email,PDO::PARAM_STR);
            $stmt->execute();
            $count=$stmt->fetch(PDO::FETCH_ASSOC); //結果を配列で取得
            if($count['count(id)']>0){ //件数を取得
                return true;
            }else{
                return false;
            }
        }



        /**
         *  ユーザを登録する
         *  @param array $userData
         *  @return bool $result
         */
        public function createUser($userData)
        {
            $result = false;

            $dbh = connect();
            $dbh->beginTransaction();

            $sql = 'INSERT INTO users (name,email,password) 
                    VALUES (?,?,?)';

            // ユーザデータを配列に入れる
            $arr = [];
            $arr[] = $userData['username']; //name
            $arr[] = $userData['email'];    //email
            $arr[] = password_hash($userData['password'],PASSWORD_DEFAULT); //passwordをハッシュ化

            try{
                // ユーザからの入力をSQL文に含めて接続
                $stmt = $dbh->prepare($sql);
                $result = $stmt->execute($arr);
                $dbh->commit();
                return $result;
            }catch(\Exception $e){
                $dbh->rollBack();
                return $result;
            }

        }


         /**
         *  emailからユーザを取得
         *  @param string $email
         *  @return array|bool $user|false
         */
        public function getUserByEmail($email)
        {
            // SQLの準備
            // SQLの実行
            // SQLの結果を返す
            $sql = 'SELECT * FROM users WHERE email = ?';

            // データを配列に入れる
            $arr = [];
            $arr[] = $email; 

            try{
                // ユーザからの入力をSQL文に含めて接続
                $stmt = connect()->prepare($sql);
                $stmt->execute($arr);
                // SQLの結果を返す
                $user = $stmt ->fetch();
                return $user;
            }catch(\Exception $e){
                return false;
            }
        }


         /**
         *  blogからデータを取得
         *  @param  void
         *  @return array|bool $blog_result|false
         */
        public function getblogdata()
        {
            // SQLの準備
            // SQLの実行
            // SQLの結果を返す
            $sql = 'SELECT * FROM blog';

            try{
                // ユーザからの入力をSQL文に含めて接続
                $stmt = connect()->prepare($sql);
                $stmt->execute();
                // SQLの結果を返す
                $blog_result = $stmt->fetchall(PDO::FETCH_ASSOC);
                return $blog_result;
            }catch(\Exception $e){
                return false;
            }
        }       


        /**
         *  blogから詳細データを取得
         *  @param  string $id
         *  @return array|bool $detail_result|false
         */
        public function getblogDetailData($id)
        {
            // SQLの準備
            // SQLの実行
            // SQLの結果を返す
            $sql = 'SELECT * FROM blog WHERE id = :id';

            try{
                // ユーザからの入力をSQL文に含めて接続
                $stmt = connect()->prepare($sql);
                $stmt->bindValue(':id',(int)$id,PDO::PARAM_INT);
                $stmt->execute();
                // SQLの結果を返す
                $detail_result = $stmt->fetch(PDO::FETCH_ASSOC);                
                return $detail_result;
            }catch(\Exception $e){
                return false;
            }
        }  


               /**
         *  ブログを登録する
         *  @param array $userData
         *  @return bool $result
         */
        public function insert_blog($userData)
        {
            $result = false;

            //update
            $sql = 'INSERT INTO blog (title,content,category,post_at,publish_status) VALUES (?,?,?,?,?)'; 

            try{
                // 本日日時を取得
                date_default_timezone_set('Asia/Tokyo');
                $post_at = date("Y-m-d H:i:s");

                $dbh = connect();
                $dbh->beginTransaction();

                date_default_timezone_set('Asia/Tokyo');
                $post_at = date("Y-m-d H:i:s");

                // ユーザデータを配列に入れる
                $arr = [];
                $arr[] = $userData['title'];
                $arr[] = $userData['content']; 
                $arr[] = (int)$userData['category']; 
                $arr[] = $post_at; 
                $arr[] = (int)$userData['publish_status'];                                          
        
                // ユーザからの入力をSQL文に含めて接続
                $stmt = $dbh->prepare($sql);
                $result=$stmt->execute($arr);
                $dbh->commit();
                return $result;

            }catch(\Exception $e){
                $dbh->rollback();
                return $result;
            }

        }


        /**
         *  ブログを更新する
         *  @param array $userData
         *  @return bool $result
         */
        public function Update_blog($userData)
        {
            $result = false;

            //update
            $sql = 'UPDATE blog SET title=:title,content=:content,category=:category,post_at=:post_at WHERE id=:id'; 

            try{

                $dbh = connect();
                $dbh->beginTransaction();

                date_default_timezone_set('Asia/Tokyo');
                $post_at = date("Y-m-d H:i:s");

                // ユーザからの入力をSQL文に含めて接続
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(':id',(int)$userData['id'],PDO::PARAM_INT); 
                $stmt->bindParam (':title', $userData['title'], PDO::PARAM_STR);  
                $stmt->bindParam (':content', $userData['content'], PDO::PARAM_STR);  
                $stmt->bindValue(':category', (int)$userData['category'], PDO::PARAM_INT);  
                $stmt->bindParam (':post_at', $post_at, PDO::PARAM_STR);                                            
                $result=$stmt->execute();
                $dbh->commit();
                return $result;

            }catch(\Exception $e){
                $dbh->rollback();
                return $result;
            }

        }


        /**
         *  ブログを削除する
         *  @param array $userData
         *  @return bool $result
         */
        public function delete_blog($id)
        {
            $result = false;

            $dbh = connect();
            $dbh->beginTransaction();

            //update
            $sql = 'DELETE FROM blog WHERE id=:id'; 

            try{
                // ユーザからの入力をSQL文に含めて接続
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(':id',(int)$id,PDO::PARAM_INT);                                           
                $result=$stmt->execute();
                $dbh->commit();
                return $result;

            }catch(\Exception $e){
                $dbh->rollback;
                return $result;
            }

        }


    }
?>