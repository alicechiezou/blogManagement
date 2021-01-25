<?php

    // セッション開始
    session_start();

    // 一度だけ読み込む
    require_once '../classes/general.php';

    // エラーメッセージ
    $err=[];

    // バリデーション
    if(!$email = filter_input(INPUT_POST,'email')){
        $err['email'] = 'メールアドレスを記入してください。';
    }
    if(!$password = filter_input(INPUT_POST,'password')){
        $err['password'] = 'パスワードを記入してください。';
    }

    if (count($err) > 0){
        // エラーがあった場合は戻す
        $_SESSION = $err;
        header('Location:login_form.php');
        return;
    }

    $general = new general();    
    $result=$general->login($email,$password);
    // ログイン失敗時の処理
    if (!$result){
        $general = null;
        header('Location:login_form.php');
        return;
    }
    $general = null;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン完了</title>
</head>
<body>
    <h2>ログイン完了</h2>
    <p>ログインしました。</p>
    <a href="./blogList_form.php">ブログ一覧へ</a>
</body>
</html>