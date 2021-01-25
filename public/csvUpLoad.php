<?php
    require_once '../classes/csvPocessing.php';

    if(isset($_POST['csvUploadProcessing'])) {

        // ファイル関連の取得
        $file = $_FILES['file'];

        $filename = $file['name'];
        $tmp_path = $file['tmp_name'];

        // エラーメッセージ配列を作成
        $err_msg = array();
        
        // ファイルのUPLOAD先を指定
        $upload_dir = 'csv/';

        // ディレクトリが存在するかチェック
        if (!file_exists($upload_dir)){
            if (!mkdir($upload_dir,0777)){       // 0777 ->全権限OK
                array_push($err_msg,'作成に失敗しました');
            }
        }

        // ファイル名に日付をつける
        date_default_timezone_set('Asia/Tokyo');
        $save_filename = date('YmdHis').$filename;

        $save_pass = $upload_dir.$save_filename;

        if (is_uploaded_file($tmp_path)){   
            if (!move_uploaded_file($tmp_path,$save_pass)){      // アップロードされたファイルを新しい位置に移動  
                echo $filename.'を'.$upload_dir.'のアップに失敗しました';
            }
        }
        
        // CSV取込
        $result = csvprocessing::csvupLoad($save_pass);
        if (!$result){
            array_push($err_msg,CSVアップロードに失敗しました);
        }
        
        header('Location:blogList_form.php');
        
    } else if(isset($_POST['blogList'])) {
        header('Location:blogList_form.php');
    } else{

    }