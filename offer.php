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

    //メンバーIDの読み取り
    $res = $_REQUEST['res'];

    //投稿処理
    if (!empty($_POST)) {
        if ($_POST['name'] != '' && $_POST['message'] != '') {

            $offer = $db -> prepare("INSERT INTO offer(name, message, member_id, created) VALUES (:name, :message, :member_id, :created)");
            $offer->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
            $offer->bindParam(':message', $_POST['message'], PDO::PARAM_STR);
            $offer->bindValue(':member_id', $res, PDO::PARAM_INT);
            $created_time = date('Y/m/d H:i:s');
            $offer->bindParam(':created', $created_time, PDO::PARAM_STR);
            $offer->execute(); 

            if(isset($_SESSION["NAME"])){
                header('Location: main.php');
                exit();
            }else{
                header('Location: index.php');
                exit();
            }
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
            <h2>オファー送信</h2>
        </div>
    </header>

    <main id="main">
        <div class="mypagepost-box">
            <div class="mypagepost-box-inner">
                <form action="" method="post">
                    <dl>
                        <dt><h2>名前</h2></dt>
                        <dd>
                            <input type="text" name="name" size="50" maxlength="200">
                        </dd>
                        <dt><h2>メッセージ</h2></dt>
                        <dd>
                            <textarea name="message" rows="20" cols="60"></textarea>
                        </dd>
                        <div>
                            <input type="submit" value="送信">
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