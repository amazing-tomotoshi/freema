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


    if($_SESSION['code']==$post['seller_code']){
        $sql='DELETE FROM posts WHERE code=?';
        $stmt=$db->prepare($sql);
        $data[0]=$post['code'];
        $stmt->execute($data);
    }

    header('Location:freema_list.php?seller='.$_SESSION['code']);

}else{
    header('Location:freema_login.php');
    exit();
}

?>
