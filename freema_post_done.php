<?php
session_start();

if($_SESSION['code']&&$_SESSION['time']+60*60*3>time()){
    $_SESSION['time']=time();

}else{
    header('Location:freema_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Market</title>
</head>
<body>

商品の登録が完了しました。<br/><br/>
<a href="freema_list.php">商品一覧</a>
    
</body>
</html>