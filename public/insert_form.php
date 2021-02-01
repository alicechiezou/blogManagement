<?php
    // 新しいセッションを開始、あるいは既存のセッションを再開
    session_start();

    // エラーを出力する
    ini_set('display_errors', "On");

    // 一度だけ読み込む
    require_once '../classes/general.php';
    
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

    //  ワンタイムチケットを生成する。
    $rnd_ticket = md5(uniqid(rand(), true));

    //  生成したチケットをセッション変数へ保存する。
    $_SESSION['ticket'] = $rnd_ticket;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>新規登録</title>
</head>
<body>
    <a href="logout.php ?>">ログアウト</a>
    <hr>    <!-- 線を入れる -->  

    <h2>ブログ登録</h2>
        <!-- エラーがあればエラーを表示、なければ登録 --> 
        <?php if (isset($_SESSION['err'])): ?>
            <p><?php echo $_SESSION['err']; ?></p>
            <?php unset($_SESSION['err']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['msg'])): ?>
            <p><?php echo $_SESSION['msg']; ?></p>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

    <form action="insertBranch.php" method="GET">

        <input type="hidden" name="ticket" value="<?php echo $rnd_ticket; ?>">

        <!--タイトル-->
        <h3>タイトル：<input type="text" name="title" value=""></h3>
        <?php if (isset($insert_err['title'])): ?>
            <p><?php echo $insert_err['title']; ?></p>
        <?php endif; ?>

        <!--カテゴリ-->
        <label for="status">カテゴリ：</label>
        <input type="radio" name="category" value="1" checked>ブログ
        <input type="radio" name="category" value="2">日常
        <input type="radio" name="category" value="3">その他        

        <!--コンテンツ-->   
        <p>
            <label for="content" vertical-align="text-top">本文：</label>
            <textarea id="content" name="content" rows = 5 placeholder="こちらにコンテンツを入力してください。"></textarea>
        </P>

        <!-- ステータスの公開 -->
        <label for="status">ステータスの公開：</label>
        <input type="radio" name="status" value="1" checked>公開
        <input type="radio" name="status" value="2">非公開

        <hr>    <!-- 線を入れる -->
        <input type="submit" name = "InsertProcessing" value = "登録" class="btn">
        <input type="submit" name = "blogList" value = "ブログ一覧へ" class="btn">
    </form>    
</body>
</html>