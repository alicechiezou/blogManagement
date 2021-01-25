<?php 
    session_start();

    // 一度だけ読み込む
    require_once '../classes/UserLogic.php';
    require_once '../classes/csvPocessing.php';

    $_SESSION['msg'] = '';
    if (isset($_POST['csvDownloadProcessing'])) {
        // CSV出力
        $result = csvprocessing::csvdownLoad();
        if (!$result){
            $_SESSION['msg'] = 'CSVダウンロードに失敗しました';
            header('Location:blogList_form.php');
            return;
        }

        header('Location:blogList_form.php');

    }elseif (isset($_POST['csvUploadProcessing'])){

        // CSVアップロード画面呼び出し
        header('Location:csvUpLoad_form.php');
    
    }elseif (isset($_POST['insertProcessing'])){

        // 登録処理
        header('Location:insert_form.php');
        
    }elseif (isset($_POST['deleteProcessing'])){
        // 削除処理  
        if (isset($_POST['chk'])){      
            $cnt=count($_POST['chk']);    
            for($i = 0;$i < $cnt;$i++){    
                $id = $_POST['chk'][$i];    
                // 削除
                $UserLogic = new UserLogic();
                $hasdeleted=$UserLogic->delete_blog($id);        
                if(!$hasdeleted){
                    $UserLogic = null;
                    $err = '削除に失敗しました';
                }
                $UserLogic = null;
            }
            //$mssage = "削除が完了しました。";
            //$alert = "<script type='text/javascript'>alert('". $mssage. "');</script>";
            //echo $alert;

            header('Location:blogList_form.php');
        } else{            
            $_SESSION['msg'] = '1つ以上選択してください';
            header('Location:blogList_form.php');
            return;            
        }
    }

    
?>