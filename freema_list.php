<?php
session_start();
require('dbconnect.php');

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

<?php

if($_REQUEST['seller']==$_SESSION['code']){

    if($_REQUEST['sold']=="on"){
        $sql='SELECT * FROM posts WHERE seller_code=? AND buyer_code!=?';
        $stmt=$db->prepare($sql);
        $data[]=$_REQUEST['seller'];
        $data[]=0;
        $stmt->execute($data);
        $posts=$stmt->fetchAll(PDO::FETCH_ASSOC);

        print '<h1>売れた自分の商品一覧</h1>';
    }else{
        $sql='SELECT * FROM posts WHERE seller_code=?';
        $stmt=$db->prepare($sql);
        $data[]=$_REQUEST['seller'];
        $stmt->execute($data);
        $posts=$stmt->fetchAll(PDO::FETCH_ASSOC);

        print '<h1>出品した商品一覧</h1>';
    }

}else if($_REQUEST['buyer']==$_SESSION['code']){
    $sql='SELECT posts.*,members.name AS pname,members.address,members.email FROM posts,members WHERE buyer_code=? AND posts.seller_code=members.code';
    $stmt=$db->prepare($sql);
    $data[]=$_REQUEST['buyer'];
    $stmt->execute($data);
    $posts=$stmt->fetchAll(PDO::FETCH_ASSOC);

    print '<h1>買った商品一覧</h1>';
}else{
    $sql='SELECT * FROM posts';
    $stmt=$db->prepare($sql);
    $stmt->execute();
    $posts=$stmt->fetchAll(PDO::FETCH_ASSOC);

    print '<h1>商品一覧</h1>';
}


foreach($posts as $post){
    
    print '<img src="images/'.$post['photo'].'" width="80px">';
    if($post['buyer_code']!=0){print '<i style="color:red">SOLD/</i>';}
    print '<a href="freema_post_view.php?code='.$post['code'].'">'.$post['name'].'<a/>';
    print '---￥'.$post['price'];
    print '<br/>';
}
?>


<br/>
<a href="freema_profile.php">プロフィール画面</a><br/>
<a href="freema_post.php">出品する</a>
    
</body>
</html>