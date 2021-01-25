<?php
    // 新しいセッションを開始、あるいは既存のセッションを再開
    session_start();

    // 一度だけ読み込む 
    require_once '../classes/general.php';

    // ログアウトボタンが押されたかどうか
    /*if (!$logput = filter_input(INPUT_POST,'logout')){
        exit('不正なリクエストです');
    }*/

    // ログインしているか判定、してない場合、新規登録画面へ返す
    $general = new general();
    $result = $general->checkLogin();
    if (!$result){
        $general = null;
        $_SESSION['login_err'] = 'ユーザを登録してログインしてください。';
        header('Location:signup_form.php');
        return;
    }
    $general = null;

    // ログアウトする
    $general = new general;
    $general->logout();
    $general = null;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト</title>
</head>
<body>
    <h2>ログアウト完了</h2>
    <p>ログアウトしました</p>
    <a href="login_form.php">ログイン画面へ</a>
</body>
</html>