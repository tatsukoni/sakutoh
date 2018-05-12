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

    if (!isset($_SESSION["NAME"])) {
        //ログインしてない
        header('Location: login.php');
        exit();
    }

    //投稿処理
    if (!empty($_POST)) {
        if ($_POST['title'] != '' && $_POST['content'] != '') {

            $toko = $db -> prepare("INSERT INTO posts(title, content, member_id, created) VALUES (:title, :content, :member_id, :created)");
            $toko->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
            $toko->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
            $toko->bindValue(':member_id', $_SESSION["ID"], PDO::PARAM_INT);
            $created_time = date('Y/m/d H:i:s');
            $toko->bindParam(':created', $created_time, PDO::PARAM_STR);
            $toko->execute(); 

            header('Location: main.php');
            exit();
        }else{
            $error['post'] = 'blank';
        }
    }
    ?>

    <header id="header">
        <div class="title-box">
            <h1>SAKUTOH</h1>
        </div>
        <div class="sub-title-box">
            <h2>作品投稿画面</h2>
        </div>
    </header>

    <main id="main">
        <div class="post-box">
            <div class="post-box-inner">
                <form action="" method="post">
                    <dl>
                        <dt>タイトル</dt>
                        <dd>
                            <input type="text" name="title" size="50" maxlength="200">
                        </dd>
                        <dt>本文</dt>
                        <dd>
                            <textarea name="content" rows="50" cols="70"></textarea>
                        </dd>
                        <div>
                            <input type="submit" value="投稿">
                            <?php if ($error['post'] == 'blank'): ?>
                            <p class="error">* 内容をご記入ください</p>
                            <?php endif; ?>
                        </div>
                    </dl>
                </form>
            </div>
        </div>
    </main>
</body>

</html>