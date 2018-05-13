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

<?php
session_start();

//データベースの読み込み
$dsn = 'mysql:host=mysql715.db.sakura.ne.jp;dbname=sakutoh_toko;charset=utf8';
$user = 'sakutoh';
$pwd = 'syb02045';
$db = new PDO($dsn, $user, $pwd);

if (!empty($_POST)) {
    //エラー項目の確認
    if ($_POST['name'] == '') {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] == '') {
        $error['email'] = 'blank';
    }
    if ($_POST['password'] == '') {
        $error['password'] = 'blank';
    }
    if (strlen($_POST['password']) < 6) {
        $error['password'] = 'length';
    }
    if ($_POST['password'] != $_POST['check_password']) {
        $error['password'] = 'wrong';
    }
    if ($_POST['user-class'] == '') {
        $error['user-class'] = 'blank';
    }
    if (empty($error)) {
        //メールアドレスの重複確認
        $check = $db -> prepare('SELECT * FROM members WHERE email = ?');
        $check->execute(array($_POST['email']));
        $formemail = $_POST['email'];

        if ($table = $check->fetch(PDO::FETCH_ASSOC)){
            if ($formemail == $table['email']) {
                $error['email'] = 'duplicate';
            }
        }else{
            $_SESSION['join'] = $_POST;
            header('Location: check.php');
            exit();
        }
        //$_SESSION['join'] = $_POST;
        //header('Location: check.php');
        //exit();
    }
}
?>

<body>
    <header id="header">
        <div class="title-box">
            <h1>SAKUTOH</h1>
        </div>
        <div class="sub-title-box">
            <h2>新規ユーザー登録</h2>
        </div>
    </header>

    <main id="main">
        <div class="user-box">
            <div class="user-box-inner">
                <p>必要事項をご記入の上、入力内容確認ボタンを押してください。</p>
                <form action="user.php" method="post" class="userForm">
                    <dl>
                        <dt>ユーザーネーム</dt>
                        <dd>
                            <input type="text" name="name" size="35" maxlength="100" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8'); ?>" >
                            <?php if ($error['name'] == 'blank'): ?>
                            <p class="error">* ユーザーネームを入力してください</p>
                            <?php endif; ?>
                        </dd>
                        <dt>メールアドレス</dt>
                        <dd>
                            <input type="text" name="email" size="35" maxlength="200" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'); ?>" >
                            <?php if ($error['email'] == 'blank'): ?>
                            <p class="error">* メールアドレスを入力してください</p>
                            <?php endif; ?>
                            <?php if ($error['email'] == 'duplicate'): ?>
                            <p class="error">* 入力したメールアドレスはすでに登録されています</p>
                            <?php endif; ?>
                        </dd>
                        <dt>パスワード</dt>
                        <dd>
                            <input type="password" name="password" size="35" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'); ?>" >
                            <?php if ($error['password'] == 'blank'): ?>
                            <p class="error">* パスワードを入力してください</p>
                            <?php endif; ?>
                            <?php if ($error['password'] == 'length'): ?>
                            <p class="error">* パスワードは6文字以上で入力してください</p>
                            <?php endif; ?>
                            <?php if ($error['password'] == 'wrong'): ?>
                            <p class="error">* 確認用のパスワードと一致しません</p>
                            <?php endif; ?>
                        </dd>
                        <dt>パスワード（確認）</dt>
                        <dd>
                            <input type="password" name="check_password" size="35" value="<?php echo htmlspecialchars($_POST['check_password'], ENT_QUOTES, 'UTF-8'); ?>" >
                        </dd>
                        <dt>利用区分</dt>
                        <dd>
                            <input  type="radio" name="user-class" value="ライター"><label for="class_writer">ライター</label>
                            <input  type="radio" name="user-class" value="クライアント"><label for="class_cliant">クライアント</label>
                            <?php if ($error['user-class'] == 'blank'): ?>
                            <p class="error">* 利用区分を入力してください</p>
                            <?php endif; ?>
                        </dd>
                    </dl>
                    <div>
                        <input type="submit" value="入力内容を確認する">
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>

</html>