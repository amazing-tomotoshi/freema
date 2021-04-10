<?php
session_start();
require_once('dbconnect.php');


//入力していたらエラーチェックしてから送る
if(!empty($_POST)){
    if($_POST['name']==''){
        $error['name']='blank';
    }
    if($_POST['nick']==''){
        $error['nick']='blank';
    }
    if($_POST['email']==''){
        $error['email']='blank';
    }

    $sql='SELECT * FROM members WHERE email=?';
    $stmt=$db->prepare($sql);
    $stmt->execute(array($_POST['email']));
    $rec=$stmt->fetch();
    if(!empty($rec['code'])){
        $error['email']='already';
    }

    if($_POST['pass']==''){
        $error['pass']='blank';
    }
    if($_POST['pass']!=$_POST['pass2']){
        $error['pass']='unmatch';
    }

    if(!isset($error)){
        $_SESSION['join']=$_POST;

        header('Location:freema_add_check.php');
        exit();
    }
}

//書き直しに来た時
if($_REQUEST['rewrite']=='on'){
    $_POST=$_SESSION['join'];
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
会員登録<br/>
<form action="" method="post"><br/>
お名前<br/>
<?php if($error['name']=='blank'){print '※お名前が入力されていません<br/>';}?>
<input type="text" name="name" value="<?php print htmlspecialchars($_POST['name'], ENT_QUOTES); ?>"><br/>
ニックネーム<br/>
<?php if($error['nick']=='blank'){print '※ニックネームが入力されていません<br/>';}?>
<input type="text" name="nick" value="<?php print htmlspecialchars($_POST['nick'], ENT_QUOTES); ?>"><br/>
メールアドレス<br/>
<?php if($error['email']=='blank'){print '※メールアドレスが入力されていません<br/>';}?>
<?php if($error['email']=='already'){print '※このメールアドレスは既に登録されています<br/>';}?>
<input type="text" name="email" value="<?php print htmlspecialchars($_POST['email'], ENT_QUOTES); ?>"><br/>
パスワード<br/>
<?php if($error['pass']=='blank'){print '※パスワードが入力されていません<br/>';}?>
<?php if($error['pass']=='unmatch'){print '※パスワードが一致しません<br/>';}?>
<input type="password" name="pass"><br/>
パスワード(確認用)<br/>
<input type="password" name="pass2"><br/>
<input type="submit" value="送信する">
</form>
    
</body>
</html>