<?php
session_start();

//データベースの読み込み
$dsn = 'mysql:host=mysql715.db.sakura.ne.jp;dbname=sakutoh_toko;charset=utf8';
$user = 'sakutoh';
$pwd = 'syb02045';
$db = new PDO($dsn, $user, $pwd);

if (!isset($_SESSION["NAME"])) {
    //ログインしてない
    header('Location: login.php');
    echo '<script type="text/javascript">alert("この機能を利用するには、ログインが必要です");</script>';
    exit();
}

//ユーザー情報の読み取り
$memberId = $_SESSION["ID"];
$postId = $_REQUEST['id'];

//ユーザー情報重複の確認
//$check = $db -> prepare('SELECT * FROM good WHERE post_id = ?');

//if ($check->execute(array($postId))) {
    //$table = $check->fetch(PDO::FETCH_ASSOC);
    //if ($memberId == $table['user_id']) {
        //header('Location: main.php');
        //exit();
    //}
//}

$check = $db->prepare("SELECT * FROM good WHERE user_id=:user_id AND post_id=:post_id");
if ($check->execute(array(':user_id' => $memberId, ':post_id' => $postId))) {
    $table = $check->fetch(PDO::FETCH_ASSOC);
    if ($memberId == $table['user_id'] && $postId == $table['post_id']) {
        $error['good'] = 'double';
        header('Location: main.php');
        exit();
    }else{
        $error['good'] = 'good';
    }
}else{
    $error['good'] = 'good';
}


//いいね登録処理
if ($error['good'] == 'good') {
    $good = $db -> prepare("INSERT INTO good(user_id, post_id, created) VALUES (:user_id, :post_id, :created)");
    $good->bindValue(':user_id', $memberId, PDO::PARAM_INT);
    $good->bindValue(':post_id', $postId, PDO::PARAM_INT);
    $created_time = date('Y/m/d H:i:s');
    $good->bindParam(':created', $created_time, PDO::PARAM_STR);
    $good->execute();

    header('Location: main.php');
    exit(); 
}

?>