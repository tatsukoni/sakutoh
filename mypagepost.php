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

if (!empty($_POST)) {
    //エラー項目の確認
    if ($_POST['writer-name'] == '') {
        $error['writer-name'] = 'blank';
    }
    if ($_POST['info'] == '') {
        $error['info'] = 'blank';
    }
    $fileName = $_FILES['picture']['name'];
    if (!empty($fileName)) {
        $ext = substr($fileName, -3);
        if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
            $error['picture'] = 'type';
        }
    }

    //マイページ登録処理
    if (empty($error)) {
        //画像をアップロードする
        $upload_time = date('YmdHis');
        $tempfile = $_FILES['picture']['tmp_name'];
        $filename = './img/' . $upload_time . $_FILES['picture']['name'];

        if (is_uploaded_file($tempfile)) {
            //$img = data('Y/m/d H:i:s') . $fileName;
            if(move_uploaded_file($tempfile, $filename)){
                echo $filename . 'をアップロードしました';
            }else{
                echo 'アップロード失敗';
            }
        }else{
            echo 'ファイルが選択されていません';
        }

        //登録処理
        $myPage = $db -> prepare("INSERT INTO mypage(name, info, expe, member_id, picture) VALUES (:name, :info, :expe, :member_id, :picture)");
        $myPage->bindParam(':name', $_POST['writer-name'], PDO::PARAM_STR);
        $myPage->bindParam(':info', $_POST['info'], PDO::PARAM_STR);
        $myPage->bindParam(':expe', $_POST['expe'], PDO::PARAM_STR);
        $id = $_REQUEST['page'];
        $myPage->bindValue(':member_id', $id, PDO::PARAM_INT);
        $myPage->bindParam(':picture', $filename, PDO::PARAM_STR);
        $myPage->execute(); 

        header('Location: main.php');
        exit();
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
                        <dt><h2>*ライター名<span class="must">（必須）</span></h2></dt>
                        <dd>
                            <input type="text" name="writer-name" size="40" maxlength="200" value="<?php echo htmlspecialchars($_POST['writer-name'], ENT_QUOTES, UTF-8); ?>">
                            <?php if ($error['writer-name'] == 'blank'): ?>
                            <p class="error">* ライター名をご記入ください</p>
                            <?php endif; ?>
                        </dd>
                        <dt><h2>*自己紹介<span class="must">（必須）</span></h2></dt>
                        <dd>
                            <textarea name="info" rows="15" cols="60"><?php echo htmlspecialchars($_POST['info'], ENT_QUOTES, UTF-8); ?></textarea>
                            <?php if ($error['info'] == 'blank'): ?>
                            <p class="error">* 自己紹介文をご記入ください</p>
                            <?php endif; ?>
                        </dd>
                        <dt><h2>経歴・実績</h2></dt>
                        <dd>
                            <textarea name="expe" rows="15" cols="60"><?php echo htmlspecialchars($_POST['expe'], ENT_QUOTES, UTF-8); ?></textarea>
                        </dd>
                        <dt><h2>写真を選択 *png, gif, jpg形式</h2></dt>
                        <dd>
                            <input type="file" name="picture" size="50">
                            <?php if ($error['picture'] == 'type'): ?>
                            <p class="error">* 画像ファイルの種類が正しくありません。jpg, gif, pngの形式で再度選択してください</p>
                            <?php endif; ?>
                        </dd>
                    </dl>
                    <div>
                        <input type="submit" value="マイページを登録する">
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

</html>