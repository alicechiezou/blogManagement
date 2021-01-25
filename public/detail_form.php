<?php
    // 新しいセッションを開始、あるいは既存のセッションを再開
    session_start();

    // 一度だけ読み込む
    require_once '../classes/UserLogic.php';
    require_once './functions.php';
    require_once '../classes/constractData.php';    
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

    $login_user = $_SESSION['login_user'];

    $id = $_GET['id'];
    // IDが空の場合エラーメッセージを返す
    if (empty($id)){
        exit('IDが不正です。');
    }


    // ブログ詳細データ取得
    $UserLogic = new UserLogic();
    $result = $UserLogic->getblogDetailData($id);
    if(!$result){
        $UserLogic = null;
        $err = 'ブログがありません';
    }
    $UserLogic = null;
    $blogdetail_data = $result;

    $categoryName = new CategoryName();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>ブログ詳細</title>
</head>
<body>
    <a href="logout.php ?>">ログアウト</a>
    <hr>    <!-- 線を入れる -->  

    <h2>ブログ詳細</h2>
    <?php if (isset($err)): ?>
    <p><?php echo $err; ?></p>
    <?php endif; ?>    
    
    <h3>タイトル：<?php if (isset($blogdetail_data['title'])) echo $blogdetail_data['title'] ?></h3>
    <p>投稿日時：<?php if (isset($blogdetail_data['post_at'])) echo $blogdetail_data['post_at'] ?></p>
    <p>カテゴリ：<?php if (isset($blogdetail_data['category'])) echo $categoryName::setCategoryName($blogdetail_data['category']) ?></p>
    <hr>    <!-- 線を入れる -->
    <p>本文：<?php if (isset($blogdetail_data['content'])) echo $blogdetail_data['content'] ?></p>

    <hr>    <!-- 線を入れる -->
    <form action="blogList_form.php" method="POST">
        <input type="submit" name = "blogList" value = "ブログ一覧へ"  class="btn">
    </form>

</body>
</html>