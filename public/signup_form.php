<?php

    session_start();

    require_once './functions.php';
    require_once '../classes/general.php';

    $general = new general();
    $result = $general->checkLogin();
    if($result){
        $general = null;
        header('Location:blogList_form.php');
        return;
    }
    $general = null;

    // ログインエラーが入っていたらログインエラー、なければNULLを入れる
    $login_err = isset($_SESSION['login_err']) ? $_SESSION['login_err'] : null;
    unset($_SESSION['login_err']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ登録画面</title>
</head>
<body>
    <!-- ユーザ登録フォームの作成 -->
    <h2>ユーザ登録フォーム</h2>
    <?php if (isset($login_err)): ?>
        <p><?php echo $login_err; ?></p>
    <?php endif; ?>

    <form action="signup.php" method="POST">
        <p>
            <label for="username">ユーザ名：</label>
            <input type="text" name="username">
        </P> 
        <p>
            <label for="email">メールアドレス：</label>
            <input type="email" name="email">
        </P> 
        <p>
            <label for="password">パスワード：</label>
            <input type="password" name="password">
            <label>（※パスワードは英数字8文字以上100文字以下にしてください。）</label>
        </P> 
        <p>
            <label for="password_conf">パスワード確認：</label> 
            <input type="password" name="password_conf"> 
        </P> 
        <input type="hidden" name="csrf_token" value="<?php echo h(setToken());?>">
        <p>
            <input type="submit" value="新規登録">
        </p>
    </form>
    <a href="login_form.php">ログインする</a>
</body>
</html>