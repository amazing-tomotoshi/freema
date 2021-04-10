<?php 
session_start();
require_once('dbconnect.php');

if($_SESSION['code']&&$_SESSION['time']+60*60*3>time()){
    $_SESSION['time']=time();

}else{
    header('Location:freema_login.php');
    exit();
}

$sql='SELECT * FROM posts WHERE posts.code=?';
$stmt=$db->prepare($sql);
$data=array();
$data[]=$_REQUEST['code'];
$stmt->execute($data);
$post=$stmt->fetch();

if($post['seller_code']==$_SESSION['code']&&$post['buyer_code']!=0){
    $sql='SELECT posts.*, members.name AS buyer,members.email,members.address,members.tel FROM posts,members WHERE posts.code=? AND posts.buyer_code=members.code';
    $stmt=$db->prepare($sql);
    $data=array();
    $data[]=$_REQUEST['code'];
    $stmt->execute($data);
    $post=$stmt->fetch();
}
if($post['buyer_code']==$_SESSION['code']){
    $sql='SELECT posts.*, members.name AS seller,members.email,members.bank FROM posts,members WHERE posts.code=? AND posts.seller_code=members.code';
    $stmt=$db->prepare($sql);
    $data=array();
    $data[]=$_REQUEST['code'];
    $stmt->execute($data);
    $post=$stmt->fetch();
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

<?php if($post['buyer_code']!=0){print '<p style="color:red; font-size:30px;">SOLD</p>';} ?>

<?php if($post['buyer_code']!=0&&$post['seller_code']==$_SESSION['code']): ?>
以下の方に購入されました。連絡を取ったのち、振り込みが確認されたら商品を送ってください。<br/><br/>
名前：<?php print $post['buyer'] ?><br/>
メールアドレス：<?php print $post['email'] ?><br/>
住所：<?php print $post['address'] ?><br/>
電話番号：<?php print $post['tel'] ?><br/><br/>
<?php endif; ?>

<?php if($post['buyer_code']==$_SESSION['code']): ?>
連絡を取ったのち、以下の口座にお振込みください。<br/>
出品者が振り込みを確認し次第、商品が発送されます<br/><br/>
口座番号：<?php print $post['bank']; ?><br/>
名前：<?php print $post['seller'] ?><br/>
メールアドレス：<?php print $post['email']; ?><br/><br/>
<?php endif; ?>

<img src="<?php print 'images/'.$post['photo'] ?>" width="300px"><br/>
<table border="1px">
    <tr>
        <td>商品名</td>
        <td>値段</td>
        <td>商品の詳細</td>
    </tr>
    <tr>
        <td><?php print $post['name'];?><br/></td>
        <td>￥<?php print $post['price'];?><br/></td>
        <td><?php print $post['detail'];?><br/></td>
    </tr>
</table>
<br/>

<?php if($post['seller_code']==$_SESSION['code']): ?>
<a href="freema_post_update.php?code=<?php print $post['code']; ?>">編集する</a>
｜<a href="freema_post_delete.php?code=<?php print $post['code']; ?>">削除する</a>
<?php endif; ?>

<?php if($post['seller_code']!=$_SESSION['code']&&$post['buyer_code']==0): ?>
<a href="freema_buy.php?code=<?php print $post['code']; ?>">購入手続きへ</a>
<?php endif; ?>

<br/><br/>
<a href="freema_list.php">商品一覧</a><br/>
<a href="freema_profile.php">プロフィール画面</a>

</body>
</html>