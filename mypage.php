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

    //マイページ内容を取得
    $id = $_REQUEST['id'];
    //$sql = "SELECT * FROM mypage";
    //$fmypage = $db->query($sql);
    if ($sqlii = "SELECT mypage.*, members.class FROM mypage, members WHERE members.id=mypage.member_id AND member_id=$id"){
        if ($mypage = $db->query($sqlii) ) {
            $mypg = $mypage->fetch(PDO::FETCH_ASSOC);
        }
    }

    //マイページ未設定の場合の処理
    if ($mypg['name'] == ''){
        $error['mypage'] = 'blank';
    }
    ?>

    <header id="header">
        <div class="title-box">
            <h1>SAKUTOH</h1>
        </div>
        <div class="sub-title-box">
            <h2>プロフィールページ</h2>
        </div>
    </header>

    <main id="main">

        <div class="mypage-box">
            <div class="mypage-box-inner">
                <div class="mypage-box-name">
                    <dt>
                        <?php if ($mypg['class'] == "ライター"): ?>
                        <h2><?php echo 'ライター名' ?></h2>
                        <?php endif; ?>
                        <?php if ($mypg['class'] == "クライアント"): ?>
                        <h2><?php echo 'クライアント名' ?></h2>
                        <?php endif; ?>
                    </dt>
                    <dd>
                        <h3><?php echo htmlspecialchars($mypg['name'], ENT_QUOTES, UTF-8); ?></h3>
                        <?php if($error['mypage'] == 'blank'): ?>
                        <h2><?php echo '名称未設定' ?></p>
                        <?php endif; ?>
                    </dd>
                </div>
                <div class="mypage-box-picture">
                    <img src="<?php echo htmlspecialchars($mypg['picture'], ENT_QUOTES, UTF-8); ?>" width="180" alt="プロフィール画像未設定">
                </div>
                <div class="mypage-box-info">
                    <dt>
                        <?php if ($mypg['class'] == "ライター"): ?>
                        <h2><?php echo '自己紹介' ?></h2>
                        <?php endif; ?>
                        <?php if ($mypg['class'] == "クライアント"): ?>
                        <h2><?php echo '自己紹介' ?></h2>
                        <?php endif; ?>
                    </dt>
                    <dd>
                        <p><?php echo nl2br(htmlspecialchars($mypg['info'], ENT_QUOTES, UTF-8)); ?></p>
                        <?php if($error['mypage'] == 'blank'): ?>
                        <h2><?php echo '自己紹介文未設定' ?></h2>
                        <?php endif; ?>
                    </dd>
                </din>
                <div class="mypage-box-expe">
                    <dt>
                        <?php if ($mypg['class'] == "ライター"): ?>
                        <h2><?php echo '経歴・実績' ?></h2>
                        <?php endif; ?>
                        <?php if ($mypg['class'] == "クライアント"): ?>
                        <h2><?php echo 'その他プロフィール' ?></h2>
                        <?php endif; ?>
                    </dt>
                    <dd>
                        <p><?php echo nl2br(htmlspecialchars($mypg['expe'], ENT_QUOTES, UTF-8)); ?></p>
                    </dd>
                </div>
                <div class="mypage-box-link">
                    <p><a href="page.php?id=<?php echo htmlspecialchars($id, ENT_QUOTES, UTF-8); ?>">ユーザー別作品一覧ページへ</p>
                </div>
            </div>
        </div>

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