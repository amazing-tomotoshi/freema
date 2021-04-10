<?php
session_start();

if(empty($_SESSION['join'])){
    header('Location:freema_add.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
登録が完了しました！<br/>
<?php print 'ありがとう'.$_SESSION['join']['nick'].'!<br/>'; ?>
<a href="freema_login.php">ログイン画面へ</a>

    
</body>
</html>