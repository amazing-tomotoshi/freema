<?php
session_start();
require_once('dbconnect.php');

if($_SESSION['code']&&$_SESSION['time']+60*60*3>time()){
    $_SESSION['time']=time();

    if(empty($_REQUEST['code'])){
        header('freema_list.php');
        exit();
    }

    $sql='SELECT * FROM posts WHERE code=?';
    $stmt=$db->prepare($sql);
    $data[]=$_REQUEST['code'];
    $stmt->execute($data);
    $post=$stmt->fetch();
    
    if($_POST['buy']=='yes'){
        $sql='UPDATE posts SET buyer_code=? WHERE code=?';
        $data[0]=$_SESSION['code'];
        $data[1]=$post['code'];
        $stmt=$db->prepare($sql);
        $stmt->execute($data);

        $_SESSION['post_code']=$post['code'];

        header('Location:freema_bought.php');
        exit();
    }

}else{
    header('freema_login.php');
    exit();
}

$sql='SELECT address,tel FROM members WHERE code=?';
$stmt=$db->prepare($sql);
$data=array();
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

<?php if(empty($member['address'])||empty($member['tel'])): ?>
商品を購入するには、住所、電話番号の登録が必要です。<br/>
<a href="freema_profile_edit.php">住所、電話番号を登録する</a><br/><br/>

<?php else: ?>
<img src="images/<?php print $post['photo'] ?>" width="300px">
<dl>
<dt>商品名</dt>
<dd><?php print $post['name'] ?></dd>
<dt>価格</dt>
<dd>￥<?php print $post['price'] ?></dd>
<dt>商品の詳細</dt>
<dd><?php print $post['detail'] ?></dd>
</dl>

<form action="" method="post">
<input type="hidden" name="buy" value="yes">
<input type="submit" value="購入確定">
<input type="button" onclick="history.back()" value="戻る">
</form>

<?php endif; ?>
    
</body>
</html>