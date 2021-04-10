<?php

session_start();
require_once('dbconnect.php');

if($_SESSION['code']&&$_SESSION['time']+60*60*3>time()&&$_SESSION['post_code']){
    $_SESSION['time']=time();

}else{
    header('Location:freema_login.php');
    exit();
}

$sql='SELECT posts.name AS product, members.* FROM posts,members WHERE posts.seller_code=members.code AND posts.code=?';
$stmt=$db->prepare($sql);
$data=array();
$data[]=$_SESSION['post_code'];
$stmt->execute($data);
$bought=$stmt->fetch(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Market</title>
</head>
<body>
<p style="font-size:40px; color:orange;">
<?php print $bought['product']; ?>の購入が完了しました
</p><br/>
連絡を取ったのち、以下の口座にお振込みください。<br/>
出品者が振り込みを確認し次第、商品が発送されます<br/><br/>
口座番号：<?php print $bought['bank']; ?><br/>
名前：<?php print $bought['name'] ?><br/>
メールアドレス：<?php print $bought['email']; ?><br/><br/>



<a href="freema_list.php">商品一覧</a><br/>
<a href="freema_profile.php">プロフィール画面</a>
    
</body>
</html>