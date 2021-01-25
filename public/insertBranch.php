<?php

    //  セッションを開始する
    session_start();

    // 一度だけ読み込む
    require_once '../classes/UserLogic.php';
    
    //  ポストされたワンタイムチケットを取得する。
    $get = isset($_GET['ticket']) ? $_GET['ticket'] : '';

    //  セッション変数に保存されたワンタイムチケットを取得する。
    $session = isset($_SESSION['ticket']) ? $_SESSION['ticket']: '';

    unset($_SESSION['ticket']);

    // 正常にポストされた場合
    if ($get != "" && $get === $session) {
    
        if ($_SERVER["REQUEST_METHOD"]=="GET"){ //ポストで飛ばされてきたら以下を処理
            if(isset($_GET['InsertProcessing'])){

                // 登録処理
                $title = filter_input(INPUT_GET,'title');
                if (strlen($title)>191){
                    // エラー
                    $_SESSION['err'] = 'タイトルの文字数は191文字までを入力してください';
                }            

                if (!isset($_SESSION['err'])){
                    // ユーザ登録処理
                    $UserLogic = new UserLogic();
                    $hasUpdated=$UserLogic->insert_blog($_GET);
            
                    if(!$hasUpdated){
                        $UserLogic = null;
                        $_SESSION['err'] = '登録に失敗しました';
                    }else {
                        $UserLogic = null;
                        $_SESSION['msg'] = "登録が完了しました。";
                    }        
                }
            
                header('Location:insert_form.php');

            } else if(isset($_GET['blogList'])) {
                // ブログ一覧へ
                header('Location:blogList_form.php');
            }
        }

	}
    else {
        $_SESSION['err'] = '不正な処理です';        
    }
?>