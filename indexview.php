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

     //データベースの読み込み
     $dsn = 'mysql:host=mysql715.db.sakura.ne.jp;dbname=sakutoh_toko;charset=utf8';
     $user = 'sakutoh';
     $pwd = 'syb02045';
     $db = new PDO($dsn, $user, $pwd);


    //投稿内容を取得
    $id = $_REQUEST['id'];
    $sqli = "SELECT members.name, posts.* FROM members, posts WHERE members.id=posts.member_id AND posts.id=$id ORDER BY posts.id DESC";
    $saku = $db->query($sqli);
    ?>

    <header id="header">
        <div class="title-box">
            <h1>SAKUTOH</h1>
        </div>
        <div class="sub-box">
            <p class="return"><a href="index.php">ホームに戻る</a></p>
        </div>
    </header>

    <main id="main">

        <?php
        if($rese = $saku->fetch(PDO::FETCH_ASSOC)):
        ?>

        <div class="content-box">
            <h2 class="content-title"><?php echo htmlspecialchars($rese['title'], ENT_QUOTES, UTF-8); ?></h2>
            <p class="content-name"><?php echo  htmlspecialchars($rese['name'], ENT_QUOTES, UTF-8); ?></p>
            <p class="content-name"><?php echo  htmlspecialchars($rese['content'], ENT_QUOTES, UTF-8); ?></p>
            <p class="content-time"><?php echo htmlspecialchars($rese['created'], ENT_QUOTES, UTF-8); ?></p>
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