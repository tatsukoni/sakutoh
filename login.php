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

    //利用区分情報
    $_SESSION["CLASS"] = $_POST['user-class'];

    //ログイン処理
    if (!empty($_POST)) {
        if ($_POST['email'] != '' && $_POST['password'] != '' && $_POST['user-class'] != ''){
            if ($_POST['user-class'] == "ライター") {

                $stmt = $db -> prepare('SELECT * FROM members WHERE email = ? AND class="ライター"');
                $stmt->execute(array($_POST['email']));

                $password = $_POST['password'];

                if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($password == $result['password']) {
                        session_regenerate_id(true);
                        // 入力したIDのユーザー名を取得
                        $id = $result['id'];
                        $sql = "SELECT * FROM members WHERE id = $id";  //入力したIDからユーザー名を取得
                        $stmt = $db->query($sql);
                        foreach ($stmt as $result) {
                            $result['name'];  // ユーザー名
                        }
                        $_SESSION["NAME"] = $result['name'];
                        $_SESSION["ID"] = $result['id'];
                        header('Location: main.php');  // メイン画面へ遷移
                        exit();  // 処理終了
                    } else{
                        // 認証失敗
                        $error['login'] = 'failed';
                    } 
                } else{
                    // 認証失敗
                    $error['login'] = 'failed';
                } 
            }elseif ($_POST['user-class'] == "クライアント") {

                $stmt = $db -> prepare('SELECT * FROM members WHERE email = ? AND class="クライアント"');
                $stmt->execute(array($_POST['email']));

                $password = $_POST['password'];

                if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($password == $result['password']) {
                        session_regenerate_id(true);
                        // 入力したIDのユーザー名を取得
                        $id = $result['id'];
                        $sql = "SELECT * FROM members WHERE id = $id";  //入力したIDからユーザー名を取得
                        $stmt = $db->query($sql);
                        foreach ($stmt as $result) {
                            $result['name'];  // ユーザー名
                        }
                        $_SESSION["NAME"] = $result['name'];
                        $_SESSION["ID"] = $result['id'];
                        header('Location: maincliant.php');  // メイン画面へ遷移
                        exit();  // 処理終了
                    } else{
                        // 認証失敗
                        $error['login'] = 'failed';
                    } 
                }else{
                    // 認証失敗
                    $error['login'] = 'failed';
                } 
            } 
        } else {
                // 該当データなし
                $error['login'] = 'blank';
            }
    } else{
            //記入なし
            $error['login'] = 'blank';
    }
    ?>

    <header id="header">
        <div class="title-box">
            <h1>SAKUTOH</h1>
        </div>
        <div class="sub-title-box">
            <h2>ログイン画面</h2>
        </div>
    </header>

    <main id="main">
        <div class="user-box">
            <div class="user-box-inner">
                <p>必要事項をご記入の上、ログインしてください。<br>
                ユーザー登録がまだの方は、こちらからどうぞ</p>
                <p><a href="user.php">ユーザー登録</a></p>
                <form action="" method="post">
                    <dl>
                        <dt>メールアドレス</dt>
                        <dd>
                            <input type="text" name="email" size="35" maxlength="200">
                        </dd>
                        <dt>パスワード</dt>
                        <dd>
                            <input type="password" name="password" size="35" maxlength="200">
                        </dd>
                        <dt>利用区分</dt>
                        <dd>
                            <input  type="radio" name="user-class" value="ライター"><label for="class_writer">ライター</label>
                            <input  type="radio" name="user-class" value="クライアント"><label for="class_cliant">クライアント</label>
                        </dd>
                        <div>
                            <input type="submit" value="ログイン">
                            <?php if ($error['login'] == 'blank'): ?>
                            <p class="error">* 記入欄をすべてご記入ください</p>
                            <?php endif; ?>
                            <?php if ($error['login'] == 'failed'): ?>
                            <p class="error">* ログインに失敗しました。正しくご記入ください</p>
                            <?php endif; ?>
                        </div>
                    </dl>
                </form>
            </div>
        </div>
    </main>
</body>