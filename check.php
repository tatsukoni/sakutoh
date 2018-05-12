<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>SAKUTOH</title>
	<meta name="keywords" content="作品投稿サービス">
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<link href="css/sakutoh.css" rel="stylesheet" media="all">
    <link href="css/base.css" rel="stylesheet" media="all">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>

<body>

<?php
session_start();
//require('dbconnect.php');
//データベースの読み込み
//$db = mysqli_connect('mysql715.db.sakura.ne.jp', 'sakutoh', 'syb02045', 'sakutoh_toko') or die(mysqli_connect_error());
//mysqli_set_charset($db, 'utf8');
$dsn = 'mysql:host=mysql715.db.sakura.ne.jp;dbname=sakutoh_toko;charset=utf8';
$user = 'sakutoh';
$pwd = 'syb02045';
$db = new PDO($dsn, $user, $pwd);

if (!isset($_SESSION['join'])) {
    echo ('送信失敗'); 
    header('Location: user.php');
    exit();
}

//書き直し
if ($_REQUEST['action'] == 'rewrite') {
    $_POST = $_SESSION['join'];
    $error['rewrite'] = true;
}

//登録処理
if (!empty($_POST)) {
    //$sql = sprintf('INSERT INTO members SET name="%s", email="%s", password="%s"',
        //mysqli_real_escape_string($db, $_SESSION['join']['name']),
        //mysqli_real_escape_string($db, $_SESSION['join']['email']),
        //mysqli_real_escape_string($db, shal($_SESSION['join']['password']))
    //);
    $stt = $db -> prepare("INSERT INTO members(name, email, password) VALUES (:name, :email, :password)");
    $stt->bindParam(':name', $_SESSION['join']['name'], PDO::PARAM_STR);
    $stt->bindParam(':email', $_SESSION['join']['email'], PDO::PARAM_STR);
    $pass = password_hash($_SESSION['join']['password'], PASSWORD_DEFAULT);
    $stt->bindParam(':password', $_SESSION['join']['password'], PDO::PARAM_STR);
    $stt->execute();

    //$db->query('INSERT INTO members SET name="小西", email="tatsuh", password="trreer"');
    //$db->query($sql);
    header('Location: thanks.php');
    exit();
}
?>

    <header id="header">
        <div class="title-box">
            <h1>SAKUTOH</h1>
        </div>
        <div class="sub-title-box">
            <h2>確認画面</h2>
        </div>
    </header>

    <main id="main">
        <div class="check-box">
            <div class="check-box-inner">
                <p class="check-explain">下記内容で登録します</p>
                <form action="check.php" method="post" class="checkForm">
                <input type="hidden" name="action" value="submit">
                    <dl>
                        <dt>ユーザーネーム</dt>
                        <dd>
                            <?php echo htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES, 'UTF-8'); ?>
                        </dd>
                        <dt>メールアドレス</dt>
                        <dd>
                            <?php echo htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES, 'UTF-8'); ?>
                        </dd>
                        <dt>パスワード</dt>
                        <dd>
                        【表示されません】
                        </dd>
                    </dl>
                    <div class="check-bottun">
                        <a href="user.php?action=rewrite">&laquo;&nbsp;書き直す</a>
                        <input type="submit" value="登録する">
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

</html>