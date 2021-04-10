<?php
session_start();
require_once('dbconnect.php');

if($_SESSION['code']&&$_SESSION['time']+60*60*3>time()){
    $_SESSION['time']=time();

}else{
    header('Location:freema_login.php');
    exit();
}

$sql='SELECT bank FROM members WHERE code=?';
$stmt=$db->prepare($sql);
$data[]=$_SESSION['code'];
$stmt->execute($data);
$member=$stmt->fetch();

if(!empty($_POST)){
    if($_POST['name']==''){
        $error['name']='blank';
    }
    $fileName=$_FILES['photo']['name'];
    $ext = substr($fileName, -3);
    if($ext!='png'&&$ext!='jpg'&&$ext!='gif'&&$ext!='PNG'&&$ext!='JPG'){
        $error['photo']='type';
    }
    if(!preg_match('/\A[0-9]+\z/',$_POST['price'])){
        $error['price']='unc';
    }

    if(empty($error)){
        $image=date('YmdHis').$_FILES['photo']['name'];
        var_dump($_FILES['photo']['tmp_name']);
        move_uploaded_file($_FILES['photo']['tmp_name'],'./images/'.$image);
        $sql='INSERT INTO posts SET name=?,photo=?,detail=?,price=?,seller_code=?';
        $stmt=$db->prepare($sql);
        $data=array();
        $data[]=$_POST['name'];
        $data[]=$image;
        $data[]=$_POST['detail'];
        $data[]=$_POST['price'];
        $data[]=$_SESSION['code'];
        $stmt->execute($data);

        header('Location:freema_post_done.php');
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
<h1>商品登録</h1>
<br/>
<?php if(empty($member['bank'])): ?>
商品が売れたときに購入者に振込先を伝えるため、銀行口座を登録してください。<br/>
<a href="freema_profile_edit.php">口座を登録する</a><br/><br/>
<?php else: ?>
<form action="" method="post" enctype="multipart/form-data"><br/>
写真（必須）<br/>
<?php if($error['photo']=='type'){print '※拡張子が.jpg,.gif,.pngのいずれかの画像を選択してください<br/>';} ?>
<input type="file" name="photo" ><br/>
商品名（必須）<br/>
<?php if($error['name']=='blank'){print '※商品名を入力してください<br/>';} ?>
<input type="text" name="name" value="<?php print htmlspecialchars($_POST['name'],ENT_QUOTES); ?>"><br/>
商品説明（任意）<br/>
<textarea name="detail" cols="50" rows="10"><?php print htmlspecialchars($_POST['detail'],ENT_QUOTES); ?></textarea><br/>
値段（必須）<br/>
<?php if($error['price']=='unc'){print '※値段を半角数字で入力してください<br/>';} ?>
￥<input type="text" name="price" value="<?php print htmlspecialchars($_POST['price'],ENT_QUOTES); ?>"><br/>
<input type="submit" value="登録する">
</form>

<?php endif; ?>

<a href="freema_list.php">商品一覧</a><br/>
<a href="freema_profile.php">プロフィール画面</a>



    
</body>
</html>