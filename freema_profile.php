<?php
session_start();
require_once('dbconnect.php');

if($_SESSION['code']&&$_SESSION['time']+60*60*3>time()){
    $_SESSION['time']=time();

}else{
    header('Location:freema_login.php');
    exit();
}

$sql='SELECT * FROM members WHERE code=?';
$stmt=$db->prepare($sql);
$data[]=$_SESSION['code'];
$stmt->execute($data);
$member=$stmt->fetch();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Market</title>
</head>
<body>
<?php if(!empty($member['image'])): ?>
<img src="images_profile/<?php print $member['image'];?>" width="100px" style="border-radius:50%"><br/>
<?php endif; ?>
ニックネーム：<?php print $member['nick']; ?><br/><br/>

<a href="freema_profile_edit.php">プロフィール編集</a><br/><br/>

<a href="freema_logout.php">ログアウトする</a><br/>

<a href="freema_list.php">商品一覧</a><br/>
<a href="freema_list.php?seller=<?php print $member['code']; ?>">出品した商品</a><br/>
<a href="freema_list.php?seller=<?php print $member['code']; ?>&sold=on">売れた自分の商品</a><br>
<a href="freema_list.php?buyer=<?php print $member['code']; ?>">買った商品</a><br/>
<a href="freema_post.php">出品する</a>
    
</body>
</html>