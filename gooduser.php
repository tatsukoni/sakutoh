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

    //データベースの読み込み
    $dsn = 'mysql:host=mysql715.db.sakura.ne.jp;dbname=sakutoh_toko;charset=utf8';
    $user = 'sakutoh';
    $pwd = 'syb02045';
    $db = new PDO($dsn, $user, $pwd);

    //ユーザー情報を取得
    $id = $_REQUEST['id'];
    $sql = "SELECT members.name, good.* FROM members, good WHERE members.id=good.user_id AND good.post_id=$id ORDER BY good.id";
    $record = $db->query($sql);

    ?>


    <header id="header">
        <div class="title-box">
            <h1>SAKUTOH</h1>
        </div>
        <div class="sub-title-box">
            <h2>この作品を応援するユーザー</h2>
        </div>
    </header>

    <main id="main">

        <?php
        while($table = $record->fetch(PDO::FETCH_ASSOC)):
        ?>

        <div class="messagebox-box">
            <div class="messagebox-box-inner">
                <h2 class="message-name"><?php echo htmlspecialchars($table['name'], ENT_QUOTES, UTF-8); ?></h2>
                <p class="content-name"><a href="mypage.php?id=<?php echo htmlspecialchars($table['user_id'], ENT_QUOTES, UTF-8); ?>">このユーザーのマイページへ</a></p>
            </div>
        </div>

        <?php
        endwhile;
        ?>

    </main>
</body>