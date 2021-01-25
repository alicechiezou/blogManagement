<?php
    // 新しいセッションを開始、あるいは既存のセッションを再開
    session_start();

    // 一度だけ読み込む
    require_once '../classes/UserLogic.php';
    require_once './functions.php';
    require_once '../classes/constractData.php';
    require_once '../classes/general.php';

    // ログインしているか(セッションにログインユーザが入っている)判定、してない場合、新規登録画面へ返す
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

    // ブログデータ取得
    $UserLogic = new UserLogic();
    $result = $UserLogic->getblogdata();
    if(!$result){
        $UserLogic = null;
        $err = 'ブログデータ取得に失敗しました';
    }
    $blog_data = $result;
    $UserLogic = null;

    $categoryName = new CategoryName();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>マイページ</title>
</head>
<body>
    <a href="logout.php ?>">ログアウト</a>

    <hr>    <!-- 線を入れる -->
    <h2>ブログ一覧</h2>

    <?php if (isset($err)): ?>
    <p><?php echo $err; ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['msg'])): ?>
        <p><?php echo $_SESSION['msg']; ?></p>
        <?php $_SESSION['msg'] = '';?>
    <?php endif; ?>

    <form action="blogList.php" method="POST">
        <table border="1" cellspacing="0" cellpadding="0" width="70%">
            <tr>
                <th>選択</th>
                <th>No</th>
                <th>タイトル</th>
                <th>カテゴリ</th>
            </tr>
            <?php foreach($blog_data as $column): ?>
            <tr>
                <td align="center"><input type="checkbox" name="chk[]" value="<?php echo $column['id']; ?>"></td>
                <td><?php echo $column['id'] ?></td>
                <td><?php echo $column['title'] ?></td>
                <td><?php echo $categoryName::setCategoryName($column['category']) ?></td>
                <td align="center"><a href="detail_form.php?id=<?php echo $column['id'] ?>">詳細</a></td>
                <td align="center"><a href="detailUpdate_form.php?id=<?php echo $column['id'] ?>">編集</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <hr>    <!-- 線を入れる -->
        <div class="submit">
            <input type="submit" name = "csvDownloadProcessing" value = "CSV出力" class="btn">
            <input type="submit" name = "csvUploadProcessing" value = "CSV取込" class="btn">
            <input type="submit" name = "insertProcessing" value = "新規登録" class="btn">
            <input type="submit" name = "deleteProcessing" value = "削除" class="btn">
        </div>
    </form>

</body>
</html>