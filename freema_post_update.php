<?php
session_start();
require_once('dbconnect.php');

if($_SESSION['code']&&$_SESSION['time']+60*60*3>time()){
    $_SESSION['time']=time();

    $sql='SELECT * FROM posts WHERE code=?';
    $stmt=$db->prepare($sql);
    $data[]=$_REQUEST['code'];
    $stmt->execute($data);
    $post=$stmt->fetch();    


    if($_SESSION['code']!=$post['seller_code']){
        header('Location:freema_list.php?seller='.$_SESSION['code']);
    }

}else{
    header('Location:freema_login.php');
    exit();
}

if(!empty($_POST)){
    if($_POST['name']==''){
        $error['name']='blank';
    }

    if($_POST['change']!='no'){
        $fileName=$_FILES['photo']['name'];
        $ext = substr($fileName, -3);
        if($ext!='png'&&$ext!='jpg'&&$ext!='gif'&&$ext!='PNG'&&$ext!='JPG'){
            $error['photo']='type';
        }
    }
    
    if(!preg_match('/\A[0-9]+\z/',$_POST['price'])){
        $error['price']='unc';
    }

    if(empty($error)){
        if($_POST['change']=='no'){
            $sql='UPDATE posts SET name=?,detail=?,price=?,seller_code=? WHERE code=?';
            $stmt=$db->prepare($sql);
            $data=array();
            $data[]=$_POST['name'];
            $data[]=$_POST['detail'];
            $data[]=$_POST['price'];
            $data[]=$_SESSION['code'];
            $data[]=$post['code'];
            $stmt->execute($data);
        }else{
            $image=date('YmdHis').$_FILES['photo']['name'];
            move_uploaded_file($_FILES['photo']['tmp_name'],'./images/'.$image);
            $sql='UPDATE posts SET name=?,photo=?,detail=?,price=?,seller_code=? WHERE code=?';
            $stmt=$db->prepare($sql);
            $data=array();
            $data[]=$_POST['name'];
            $data[]=$image;
            $data[]=$_POST['detail'];
            $data[]=$_POST['price'];
            $data[]=$_SESSION['code'];
            $data[]=$post['code']; 
            $stmt->execute($data);
        }


        header('Location:freema_post_view.php?code='.$post['code']);
        exit();

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

<form action="freema_post_update.php?code=<?php print $post['code']?>" method="post" enctype="multipart/form-data"><br/>
写真（必須）<br/>
<?php if($error['photo']=='type'){print '※拡張子が.jpg,.gif,.pngのいずれかの画像を選択してください<br/>';} ?>
<input type="file" name="photo" ><br/>
<input type="checkbox" name="change" value="no">画像を変更しない<br/>
商品名（必須）<br/>
<?php if($error['name']=='blank'){print '※商品名を入力してください<br/>';} ?>
<input type="text" name="name" value="<?php print $post['name']; ?>"><br/>
商品説明（任意）<br/>
<textarea name="detail" cols="50" rows="10"><?php print $post['detail']; ?></textarea><br/>
値段（必須）<br/>
<?php if($error['price']=='unc'){print '※値段を半角数字で入力してください<br/>';} ?>
￥<input type="text" name="price" value="<?php print $post['price']; ?>"><br/>
<input type="submit" value="修正する">
</form>
<br/>
<a href="freema_post_view.php?code=<?php print $post['code'] ?>">戻る</a><br/>
<a href="freema_list.php">商品一覧</a><br/>
<a href="freema_profile.php">プロフィール画面</a>

    
</body>
</html>