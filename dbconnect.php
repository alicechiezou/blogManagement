<?php
    //一度だけファイル読み込み
    require_once 'env.php';
    //エラーの内容を表示
    ini_set('display_errors',true);

    function connect()
    {
        $host = DB_HOST;
        $db   = DB_NAME;
        $user = DB_USER;
        $pass = DB_PASS;

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        try{
            //サーバーへ接続
            $pdo = new PDO($dsn,$user,$pass,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            // '成功です！';
            return $pdo;
        }catch(PDOException $e){
            echo '接続失敗です！'.$e->getMessage();
            exit();
        }
    }        
?>