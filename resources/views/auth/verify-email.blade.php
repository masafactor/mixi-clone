
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メール認証</title>
</head>
<body>
    <h1>メールアドレスを確認してください</h1>

    <p>登録されたメールアドレスに確認用のリンクを送信しました。</p>
    <p>届いていない場合は、以下から再送信できます。</p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">確認メールを再送信</button>
    </form>
</body>
</html>
