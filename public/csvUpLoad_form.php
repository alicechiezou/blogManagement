<?php
    // 新しいセッションを開始、あるいは既存のセッションを再開
    session_start();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSVアップロード</title>
</head>
<style type="text/css">
    .btn {
      display: inline-block;
      border-radius: 3px;
      font-size: 18px;
      background: #67c5ff;
      border: 2px solid #67c5ff;
      padding: 5px 10px;
      color: #fff;
      cursor: pointer;
    }
</style>
<body>
    <a href="logout.php ?>">ログアウト</a>
    <hr>    <!-- 線を入れる -->
    <!--multipart/form-data => 複数の種類のデータを一度に扱える -->
    <form action="csvUpLoad.php" method="POST" enctype="multipart/form-data">
      <div class="file-up">
        <input name="file" type="file" size="80" />
      </div>
      <hr>    <!-- 線を入れる -->
      <div class="submit">
        <input type="submit" name = "csvUploadProcessing" value="実行" class="btn" />
        <input type="submit" name = "blogList" value = "ブログ一覧へ" class="btn">
      </div>
    </form>
</body>
</html>