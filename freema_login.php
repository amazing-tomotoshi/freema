<?php  
require_once('dbconnect.php');
session_start();


if(!empty($_POST)){
    if($_POST['email']!=''&&$_POST['pass']!=''){
        $sql='SELECT * FROM members WHERE email=? AND pass=?';
        $stmt=$db->prepare($sql);
        $passmd5=md5($_POST['pass']);
        $stmt->execute(array($_POST['email'],$passmd5));
        $rec=$stmt->fetch();

        if($rec['code']!=''){
            $_SESSION['code']=$rec['code'];
            $_SESSION['time']=time();

            if($_POST['save']=='on'){
                setcookie('email',$_POST['email'],time()+60*60*24*7);
                setcookie('pass',$_POST['pass'],time()+60*60*24*7);
            }

            header('Location:freema_list.php');
            exit();
        }else{
            $error['login']='failed';
        }
    }else{
        $error['login']='blank';
    }

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
<h1>ログイン画面</h1>
<?php if($error['login']=='failed'){print '※メールアドレスかパスワード、またはどちらも違います。';} ?>
<?php if($error['login']=='blank'){print '※メールアドレスとパスワードを入力してください。';} ?>
<form action="" method="post">
メールアドレス<br/>
<input type="text" name="email" value="<?php print htmlspecialchars($_COOKIE['email'],ENT_QUOTES); ?>"><br/>
パスワード<br/>
<input type="password" name="pass" value="<?php print htmlspecialchars($_COOKIE['pass'],ENT_QUOTES); ?>"><br/><br/>
<input type="checkbox" name="save" value="on">
メールアドレスとパスワードを保存する。<br/>
<input type="submit" value="ログインする">
</form>

    
</body>
</html>