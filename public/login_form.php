<?php
    session_start();

    // 一度だけ読み込む
    require_once '../classes/general.php';

    // PHPエラーの表示・非表示を設定
    ini_set('display_errors',"On");

    $general = new general();
    $result = $general->checkLogin();
    if($result){
        $general = null;
        header('Location:blogList.php');
        return;
    }
    $general = null;

    $err = $_SESSION;

    // セッションの中身をクリア
    $_SESSION = array();

    // セッションファイルを消す
    session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
</head>
<body>
    <!-- ログインフォームの作成 -->
    <h2>ログインフォーム</h2>
        <?php if (isset($err['msg'])): ?>
            <p><?php echo $err['msg']; ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <p>
                <label for="email">メールアドレス：</label>
                <input type="email" name="email">
                <?php if (isset($err['email'])): ?>
                    <p><?php echo $err['email']; ?></p>
                <?php endif; ?>
            </P> 
            <p>
                <label for="password">パスワード：</label>
                <input type="password" name="password">
                <?php if (isset($err['password'])): ?>
                    <p><?php echo $err['password']; ?></p>
                <?php endif; ?>
            </P> 
            <p>
                <input type="submit" value="ログイン">
            </p>
        </form>
        <a href="signup_form.php">新規登録はこちら</a>
</body>
</html>