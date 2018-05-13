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

//編集前の情報を取得
$id = $_REQUEST['rer'];
$sql = "SELECT * FROM mypage WHERE member_id=$id";
$mypage = $db->query($sql);
$mypg = $mypage->fetch(PDO::FETCH_ASSOC);

//更新処理
if (!empty($_POST)) {
    if ($_POST['writer-name'] == '') {
        $error['writer-name'] = 'blank';
    }
    if ($_POST['info'] == '') {
        $error['info'] = 'blank';
    }

    //データベース変更処理
    if (empty($error)) {
        $re_name = $_POST['writer-name'];
        $re_info = $_POST['info'];
        $re_expe = $_POST['expe'];
        $sqli = "UPDATE mypage SET name= :name, info= :info, expe= :expe WHERE member_id= :member_id";
        $update = $db->prepare($sqli);
        $params = array(':name' => $re_name, ':info' => $re_info, ':expe' => $re_expe, ':member_id' => $id);
        $update->execute($params);
        
        if ($_SESSION["CLASS"] == "クライアント") {
            header('Location: maincliant.php');
            exit();
        }elseif ($_SESSION["CLASS"] == "ライター"){
            header('Location: main.php');
            exit();
        }
    }
}

?>

    <header id="header">
        <div class="title-box">
            <h1>SAKUTOH</h1>
        </div>
        <div class="sub-title-box">
            <h2>マイページ編集</h2>
        </div>
    </header>

    <main id="main">
        <div class="mypagepost-box">
            <div class="mypagepost-box-inner">
                <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="submit">
                    <dl>
                        <dt>
                            <?php if ($_SESSION["CLASS"] == "ライター"): ?>
                            <h2><?php echo 'ライター名' ?></h2>
                            <?php endif; ?>
                            <?php if ($_SESSION["CLASS"] == "クライアント"): ?>
                            <h2><?php echo 'クライアント名' ?></h2>
                            <?php endif; ?>
                            <span class="must">（必須）</span>
                        </dt>
                        <dd>
                            <input type="text" name="writer-name" size="40" maxlength="200" value="<?php echo htmlspecialchars($mypg['name'], ENT_QUOTES, UTF-8); ?>">
                            <?php if ($error['writer-name'] == 'blank'): ?>
                            <p class="error">* ライター名をご記入ください</p>
                            <?php endif; ?>
                        </dd>
                        <dt><h2>*自己紹介<span class="must">（必須）</span></h2></dt>
                        <dd>
                            <textarea name="info" rows="15" cols="60"><?php echo htmlspecialchars($mypg['info'], ENT_QUOTES, UTF-8); ?></textarea>
                            <?php if ($error['info'] == 'blank'): ?>
                            <p class="error">* 自己紹介文をご記入ください</p>
                            <?php endif; ?>
                        </dd>
                        <dt>
                            <?php if ($_SESSION["CLASS"] == "ライター"): ?>
                            <h2><?php echo '経歴・実績' ?></h2>
                            <?php endif; ?>
                            <?php if ($_SESSION["CLASS"] == "クライアント"): ?>
                            <h2><?php echo 'その他プロフィール' ?></h2>
                            <?php endif; ?>
                        </dt>
                        <dd>
                            <textarea name="expe" rows="15" cols="60"><?php echo htmlspecialchars($mypg['expe'], ENT_QUOTES, UTF-8); ?></textarea>
                        </dd>
                    </dl>
                    <div>
                        <input type="submit" value="マイページを更新する">
                    </div>
                </form>
            </div>
        </div>
    </main>

</body>

</html>