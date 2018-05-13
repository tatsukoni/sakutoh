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

    if (isset($_SESSION["NAME"])) {
        //ログインしている
        echo ('こんにちは' . htmlspecialchars($_SESSION["NAME"]) . 'さん！');
    }else{
        //ログインしてない
        header('Location: login.php');
        exit();
    }

    //投稿内容を取得
    //$sqli = "SELECT members.name, posts.* FROM members, posts WHERE members.id=posts.member_id ORDER BY posts.id DESC";
    //$saku = $db->query($sqli);

    //投稿内容を取得
    $page = $_REQUEST['page'];
    if ($page == '') {
        $page = 1;
    }
    $page = max($page, 1);
    $start = ($page - 1) * 7;
    $sqli = "SELECT members.name, posts.* FROM members, posts WHERE members.id=posts.member_id ORDER BY posts.id DESC LIMIT $start, 7";
    $saku = $db->query($sqli);

    //最終ページを取得
    $maxsql = "SELECT COUNT(*) AS cnt FROM posts";
    $max = $db->query($maxsql);
    $maxpg = $max->fetch(PDO::FETCH_ASSOC);
    $maxpage = ceil($maxpg['cnt'] / 7);
    $page = min($page, $maxpage);

    //いいね数表示機能
    //$goods = $saku->fetch(PDO::FETCH_ASSOC);
    //$postId = $goods['post_id'];
    //$sqll = "SELECT COUNT(*) AS cntt FROM good WHERE post_id=$postId";
    //$record = $db->query($sqll);
    //$table = $record->fetch(PDO::FETCH_ASSOC);

    //検索機能
    if(!empty($_POST)) {
        if ($_POST['search'] != ''){

            $search = $db -> prepare('SELECT * FROM members WHERE name = ?');
            $search->execute(array($_POST['search']));
            $searchname = $_POST['search'];

            if ($searchres = $search->fetch(PDO::FETCH_ASSOC)){
                if ($searchname == $searchres['name']){

                    $id= $searchres['id'];
                    header("Location: page.php?id=$id");
                    exit();
                }else{
                    $error['search'] = 'failed';
                }
            }else{
                $error['search'] = 'failed';
            }
        }else{
            $error['search'] = 'blank';
        }
    }
    ?>

    <header id="header">
        <div class="title-box">
            <h1>SAKUTOH</h1>
            <div class="responce-login">
                <p><a href="logout.php">ログアウト</a></p>
            </div>
        </div>
        <div class="search-box">
            <div class="searcher">
                <form action="" method="post">
                    <input type="search" name="search" size="32" maxlength="100" placeholder="検索したいライター名を入力">
                    <input type="submit" value="検索">
                    <?php if ($error['search'] == 'blank'): ?>
                    <p class="error">* ライター名をご記入ください</p>
                    <?php endif; ?>
                    <?php if ($error['search'] == 'failed'): ?>
                    <p class="error">* 該当するライターは存在しません</p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <div class="login-box">
            <div class="login-box-inner">
            <div class="logout">
                <p><a href="logout.php">ログアウト</a></p>
            </div>
            <div class="message-box">
                <p><a href="emitbox.php?mes=<?php echo htmlspecialchars($_SESSION["ID"], ENT_QUOTES, UTF-8); ?>">送信ボックス</a></p>
            </div>
            <div class="mypage">
                <button>プロフィール</button>
            </div>
            <div class="about">
                <p><a href="about.php">SAKUTOHとは</a></p>
            </div>
            </div>
        </div>
        <div class="responce-menu-box">
            <ul>
                <li><button>プロフィール</button></li>
                <li><p><a href="post.php">作品投稿</a></p></li>
                <li><a href="message.php?mes=<?php echo htmlspecialchars($_SESSION["ID"], ENT_QUOTES, UTF-8); ?>">オファー</a></p></li>
                <li><p><a href="about.php">SAKUTOH</a></p></li>
            </ul>
        </div>
    </header>

    <main id="main">

        <?php
        while($reses = $saku->fetch(PDO::FETCH_ASSOC)):
        ?>

        <div class="content-box">
            <div class="content-box-inside">
                <h2 class="content-title"><?php echo htmlspecialchars($reses['title'], ENT_QUOTES, UTF-8); ?></h2>
                <p class="content-name"><a href="mypage.php?id=<?php echo htmlspecialchars($reses['member_id'], ENT_QUOTES, UTF-8); ?>"><?php echo  htmlspecialchars($reses['name'], ENT_QUOTES, UTF-8); ?></a></p>
                <p class="content-time"><?php echo htmlspecialchars($reses['created'], ENT_QUOTES, UTF-8); ?></p>
                <p class="content-link"><a href="view.php?id=<?php echo htmlspecialchars($reses['id'], ENT_QUOTES, UTF-8); ?>">続きはこちら</a></p>
                <?php
                //いいね数表示機能
                $postId = $reses['id'];
                $sqll = "SELECT COUNT(*) AS cntt FROM good WHERE post_id=$postId";
                $record = $db->query($sqll);
                $table = $record->fetch(PDO::FETCH_ASSOC);
                $goodcnt = $table['cntt'];
                ?>
                <p class="good-link"><a href="good.php?id=<?php echo htmlspecialchars($reses['id'], ENT_QUOTES, UTF-8); ?>">この作品を応援する</a>　<a href="gooduser.php?id=<?php echo htmlspecialchars($reses['id'], ENT_QUOTES, UTF-8); ?>">★<?php echo $goodcnt ?></a></p>
            </div>
        </div>

        <?php
        endwhile;
        ?>

        <div class="pulldown">
            <div class="pulldown-inner">
                <h2>プロフィール</h2>
                <p>プロフィールページを充実させることで、クライアントからのオファーが来やすくなります。プロフィール設定ボタンから、設定画面に移動できます。</p>
                <ul>
                    <li><a href="mypagepost.php?page=<?php echo htmlspecialchars($_SESSION["ID"], ENT_QUOTES, UTF-8); ?>">プロフィール設定</a></li>
                    <li><a href="mypagererite.php?rer=<?php echo htmlspecialchars($_SESSION["ID"], ENT_QUOTES, UTF-8); ?>">プロフィール編集</a></li>
                    <li><a href="mypage.php?id=<?php echo htmlspecialchars($_SESSION["ID"], ENT_QUOTES, UTF-8); ?>">プロフィール表示</a></li>
                </ul>
                <button>戻る</button>
            </div>
        </div>

        <div class="page-link">
            <ul class="paging">
                <?php
                if ($page > 1){
                ?>
                <li><a href="maincliant.php?page=<?php echo($page - 1); ?>">前ページへ</a></li>
                <?php
                } else {
                ?>
                <li>前ページへ</li>
                <?php
                }
                ?>
                <?php
                if ($page < $maxpage) {
                ?>
                <li><a href="maincliant.php?page=<?php echo($page + 1); ?>">次ページへ</a></li>
                <?php
                } else{ 
                ?>
                <li>次ページへ</li>
                <?php
                } 
                ?>
            </ul>
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

</html>