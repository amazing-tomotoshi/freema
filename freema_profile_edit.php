<?php
session_start();
require_once('dbconnect.php');

function h($s){
    return htmlspecialchars($s,ENT_QUOTES);
}

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

$member1=$member;

if(!empty($_POST)){

    $member1=$_POST;

    if($_POST['nick']==''){
        $error['nick']='blank';
    }
    if($_POST['email']==''){
        $error['email']='blank';
    }

    if($_POST['change']!='no'){
        $fileName=$_FILES['image']['name'];
        $ext = substr($fileName, -3);
        if($ext!='png'&&$ext!='jpg'&&$ext!='gif'&&$ext!='PNG'&&$ext!='JPG'){
            $error['image']='type';
        }
    }
    
    if(!isset($error)){
        if($_POST['change']!='no'){
            $image=date('YmdHis').$_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'],'./images_profile/'.$image);
            $sql='UPDATE members SET nick=?,email=?,image=?,address=?,tel=?,bank=? WHERE code=?';
            $stmt=$db->prepare($sql);
            $data=array();
            $data[]=$_POST['nick'];
            $data[]=$_POST['email'];
            $data[]=$image;
            $data[]=$_POST['address'];
            $data[]=$_POST['tel'];
            $data[]=$_POST['bank'];
            $data[]=$member['code'];
            $stmt->execute($data);
        }else{
            $sql='UPDATE members SET nick=?,email=?,address=?,tel=?,bank=? WHERE code=?';
            $stmt=$db->prepare($sql);
            $data=array();
            $data[]=$_POST['nick'];
            $data[]=$_POST['email'];
            $data[]=$_POST['address'];
            $data[]=$_POST['tel'];
            $data[]=$_POST['bank'];
            $data[]=$member['code'];
            $stmt->execute($data);
        }
        header('Location:freema_profile.php');
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

<form action="" method="post" enctype="multipart/form-data"><br/>
ニックネーム(必須)<br/>
<?php if($error['nick']=='blank'){print '※ニックネームが入力されていません<br/>';}?>
<input type="text" name="nick" value="<?php print h($member1['nick']); ?>"><br/>
メールアドレス(必須)<br/>
<?php if($error['email']=='blank'){print '※メールアドレスが入力されていません<br/>';}?>
<?php if($error['email']=='already'){print '※このメールアドレスは既に登録されています<br/>';}?>
<input type="text" name="email" style="width:300px" value="<?php print h($member1['email']); ?>"><br/>

プロフィール画像<br/>
<?php if($error['image']=='type'){print '※拡張子が.jpg,.gif,.pngのいずれかの画像を選択してください<br/>';} ?>
<input type="checkbox" name="change" value="no">画像を変更しない<br/>
<input type="file" name="image"><br/>
住所(郵便番号含む)(購入の際に必要です)<br/>
<input type="text" name="address" style="width:700px" value="<?php print h($member1['address']); ?>"><br/>
電話番号(購入の際に必要です)<br/>
<input type="text" name="tel" value="<?php print h($member1['tel']); ?>"><br/>
銀行口座(出品する際に必要です)<br/>
<input type="text" name="bank" style="width:500px" value="<?php print h($member1['bank']); ?>"><br/>

<input type="submit" value="更新する">
<input type="button" onclick="history.back()" value="戻る">
</form>

    
</body>
</html>