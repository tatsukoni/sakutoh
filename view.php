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


    //投稿内容を取得
    $id = $_REQUEST['id'];
    $sqli = "SELECT members.name, posts.* FROM members, posts WHERE members.id=posts.member_id AND posts.id=$id ORDER BY posts.id DESC";
    $saku = $db->query($sqli);
    ?>

    <header id="header">
        <div class="title-box">
            <h1>SAKUTOH</h1>
        </div>
        <div class="sub-title-box">
            <h2>投稿記事</h2>
        </div>
    </header>

    <main id="main">

        <?php
        if($rese = $saku->fetch(PDO::FETCH_ASSOC)):
        ?>

        <div class="viewcontent-box">
            <div class="viewcontent-box-inner">
                <h2 class="content-title"><?php echo htmlspecialchars($rese['title'], ENT_QUOTES, UTF-8); ?></h2>
                <p class="viewcontent-name"><a href="mypage.php?id=<?php echo htmlspecialchars($rese['member_id'], ENT_QUOTES, UTF-8); ?>"><?php echo  htmlspecialchars($rese['name'], ENT_QUOTES, UTF-8); ?></a></p>
                <p class="viewcontent-content"><?php echo  nl2br(htmlspecialchars($rese['content'], ENT_QUOTES, UTF-8)); ?></p>
                <p class="viewcontent-time"><?php echo htmlspecialchars($rese['created'], ENT_QUOTES, UTF-8); ?></p>
                <p><a href="offer.php?res=<?php echo htmlspecialchars($rese['member_id'], ENT_QUOTES, UTF-8); ?>">このライターにオファーを送る</a></p>
                <?php
                //いいね数表示機能
                $postId = $rese['id'];
                $sqll = "SELECT COUNT(*) AS cntt FROM good WHERE post_id=$postId";
                $record = $db->query($sqll);
                $table = $record->fetch(PDO::FETCH_ASSOC);
                $goodcnt = $table['cntt'];
                ?>
                <p class="good-link"><a href="good.php?id=<?php echo htmlspecialchars($rese['id'], ENT_QUOTES, UTF-8); ?>">この作品を応援する</a>　<a href="gooduser.php?id=<?php echo htmlspecialchars($rese['id'], ENT_QUOTES, UTF-8); ?>">★<?php echo $goodcnt ?></a></p>
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

    <footer id="footer">
        <div class="footer">
        <div class="footer-inner">
            <ul class="pagetop">
                <li><p>All created by konishi</p></li>
                <li><a>ページトップへ</a></li>
            </ul>
        </div>
        </div>
    </footer>

    <script src="js/sakutoh.js"></script>
</body>

</html>