<?php
    session_start();

    // 一度だけ読み込む
    require_once '../classes/UserLogic.php';

    // エラーメッセージ
    $err=[];

    // バリデーション
    if(!$username = filter_input(INPUT_POST,'username')){
        $err[] = 'ユーザ名を記入してください。';
    }
    if(!$email = filter_input(INPUT_POST,'email')){
        $err[] = 'メールアドレスを記入してください。';
    }
    // 既に存在するパスワードであるかチェック。存在すればエラー
    $UserLogic = new UserLogic();
    $hasSelect=$UserLogic->emailExists($email);
    if($hasSelect){
        $err[] = '既に存在するメールアドレスです。';
    }
    $UserLogic = null;

    $password = filter_input(INPUT_POST,'password');
    // 正規表現チェック
    if (!preg_match("/\A[a-z\d]{8,100}+\z/i",$password)){
        $err[] = 'パスワードは英数字8文字以上100文字以下にしてください。';
    }
    $password_conf = filter_input(INPUT_POST,'password_conf');
    if ($password !== $password_conf){
        $err[] = '確認用パスワードが異なっています。';
    }

    if (count($err) === 0){
        // ユーザ登録処理
        $UserLogic = new UserLogic();
        $hasCrated=$UserLogic->createUser($_POST);

        if(!$hasCrated){
            $err[] = '登録に失敗しました';
        }
        $UserLogic = null;
    }
    
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ登録完了画面</title>
</head>
<body>
    <!--　登録完了画面作成 -->

    <!-- エラーがあればエラーを表示、なければ登録 -->       
    <?php if (count($err) > 0): ?>
        <?php foreach($err as $e): ?>
            <p><?php echo $e ?></p>
        <?php endforeach ?>
    <?php else: ?>
        <p>ユーザ登録が完了しました。</p>
    <?php endif ?>
    <a href="./signup_form.php">戻る</a>
</body>
</html>