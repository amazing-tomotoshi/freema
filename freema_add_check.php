<?php
session_start();
require_once('dbconnect.php');

if(empty($_SESSION['join'])){
    header('Location:freema_add.php');
    exit();
}

if($_POST['action']=='on'){
    $sql='INSERT INTO members SET name=?,nick=?,email=?, pass=?';
    $stmt=$db->prepare($sql);
    $_SESSION['join']['pass']=md5($_SESSION['join']['pass']);
    $data[]=$_SESSION['join']['name'];
    $data[]=$_SESSION['join']['nick'];
    $data[]=$_SESSION['join']['email'];
    $data[]=$_SESSION['join']['pass'];
    $stmt->execute($data);

    $db=null;

    header('Location:freema_add_done.php');
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

<form action="" method="post">
<input type="hidden" name="action" value="on">
<dl>
<dt>お名前</dt>
<dd><?php print htmlspecialchars($_SESSION['join']['name'],ENT_QUOTES); ?></dd>
<dt>ニックネーム</dt>
<dd><?php print htmlspecialchars($_SESSION['join']['nick'],ENT_QUOTES); ?></dd>
<dt>メールアドレス</dt>
<dd><?php print htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES); ?></dd>
<dt>パスワード</dt>
<dd>表示されません</dd>
</dl>
<a href="freema_add.php?rewrite=on">修正する</a>
<input type="submit" value="登録する">
</form>

    
</body>
</html>