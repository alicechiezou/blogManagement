<?php
    // 新しいセッションを開始、あるいは既存のセッションを再開
    session_start();

    // 一度だけ読み込む
    require_once '../classes/UserLogic.php';
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

    $id = $_GET['id'];
    
    // IDが空の場合エラーメッセージを返す
    if (empty($id)){
        exit('IDが不正です。');
    }

    $update_err=[];
    if(isset($_GET['updateProcessing'])) {

        $title = filter_input(INPUT_GET,'title');
        if (strlen($title)>191){
            // エラー
            $update_err['title'] = 'タイトルの文字数は191文字までを入力してください';
        }

        if (count($update_err) === 0){
            // ユーザ更新処理
            $UserLogic = new UserLogic();
            $hasUpdated=$UserLogic->Update_blog($_GET);
            
            if(!$hasUpdated){
                $UserLogic = null;
                $err = '更新に失敗しました';
            }else{
                //echo "更新完了";
                $UserLogic = null;
                header('Location:blogList_form.php');
            }

        }
    } else if(isset($_GET['blogList'])) {
        header('Location:blogList_form.php');
    } else{

    }

    // ブログ詳細データ取得
    $blogdetail_data = [];
    $UserLogic = new UserLogic();
    $result = $UserLogic->getblogDetailData($id);
    if(!$result){
        $UserLogic = null;
        $err = 'ブログがありません';
    }
    $UserLogic = null;
    $blogdetail_data = $result;

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

    <!-- エラーがあればエラーを表示、なければ登録 --> 
    <?php if (isset($err)): ?>
        <p><?php echo $err; ?></p>
    <?php endif; ?> 

    <h2>ブログ編集</h2>
    <form action="detailUpdate_form.php" method="GET">
        <!--ID-->      
        <input type="hidden" name="id" value="<?php if (isset($blogdetail_data['id'])) echo $blogdetail_data['id'] ?>">

        <!--タイトル-->
        <?php if (!isset($update_err['title'])): ?>
            <?php if (isset($blogdetail_data['title'])): ?>
                <?php $title = $blogdetail_data['title'] ?>
            <?php endif; ?>
        <?php endif; ?>

        <h3>タイトル：<input type="text" name="title" value="<?php echo $title; ?>"></h3>
        <?php if (isset($update_err['title'])): ?>
            <p><?php echo $update_err['title']; ?></p>
        <?php endif; ?>

        <!--投稿日時-->
        <p>投稿日時：<input type="text" name="updateTime" value="<?php if (isset($blogdetail_data['updateTime'])) echo $blogdetail_data['updateTime'] ?>" readonly></p>

        <?php if (!isset($update_err['category'])): ?>
            <?php if (isset($blogdetail_data['category'])): ?>
                <?php $category = $blogdetail_data['category'] ?>
            <?php endif; ?>
        <?php endif; ?>

        <!--カテゴリ-->
        <label for="status">カテゴリ：</label>

        <?php switch ($category):
            case 1:
        ?>
            <input type="radio" name="category" value="1" checked>ブログ
            <input type="radio" name="category" value="2">日常
            <input type="radio" name="category" value="3">その他
            <?php break; ?>
        <?php case 2: ?>
            <input type="radio" name="category" value="1">ブログ
            <input type="radio" name="category" value="2" checked>日常
            <input type="radio" name="category" value="3">その他
            <?php break; ?>
        <?php default: ?>
            <input type="radio" name="category" value="1">ブログ
            <input type="radio" name="category" value="2">日常
            <input type="radio" name="category" value="3" checked>その他
            <?php break; ?>
        <?php endswitch; ?>   

        <hr>    <!-- 線を入れる -->
        <!--コンテンツ-->   
        <p>
            <label for="content" vertical-align="text-top">本文：</label>
            <textarea id="content" name="content" rows = 5 placeholder="こちらにコンテンツを入力してください。"><?php if (isset($blogdetail_data['content'])) echo $blogdetail_data['content'] ?></textarea>
        </P>
        <hr>    <!-- 線を入れる -->

        <input type="submit" name = "updateProcessing" value = "更新" class="btn">
        <input type="submit" name = "blogList" value = "ブログ一覧へ" class="btn">
    </form>

</body>
</html>