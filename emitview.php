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

    if (!isset($_SESSION["NAME"])) {
        //ログインしてない
        header('Location: login.php');
        exit();
    }

     //データベースの読み込み
     $dsn = 'mysql:host=mysql715.db.sakura.ne.jp;dbname=sakutoh_toko;charset=utf8';
     $user = 'sakutoh';
     $pwd = 'syb02045';
     $db = new PDO($dsn, $user, $pwd);


    //投稿内容を取得
    $box = $_REQUEST['box'];
    $sqli = "SELECT offer.*, members.name FROM offer, members WHERE members.id=offer.member_id AND offer.id=$box ORDER BY id DESC";
    $offe = $db->query($sqli);
    ?>

    <header id="header">
        <div class="title-box">
            <h1>SAKUTOH</h1>
        </div>
        <div class="sub-title-box">
            <h2>メッセージ</h2>
        </div>
    </header>

    <main id="main">

        <?php
        if($offes = $offe->fetch(PDO::FETCH_ASSOC)):
        ?>

        <div class="viewmessagebox-box">
            <div class="viewmessagebox-box-inner">
                <h2 class="message-name">to:<?php echo htmlspecialchars($offes['name'], ENT_QUOTES, UTF-8); ?></h2>
                <p class="message-content"><?php echo  nl2br(htmlspecialchars($offes['message'], ENT_QUOTES, UTF-8)); ?></p>
                <p class="message-time"><?php echo htmlspecialchars($offes['created'], ENT_QUOTES, UTF-8); ?></p>
            </div>
        </div>

        <?php
        else:
        ?>

        <div class="content-box">
            <p>その投稿は存在しません。</p>
        </div>

        <?php
        endif;
        ?>
    </main>
</body>

</html>