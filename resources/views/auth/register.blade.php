<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - SpendNote</title>
    <link rel="stylesheet" href="/css/auth.css">
</head>

<body class="page-enter">

<div class="auth-container">
    
    <!-- LEFT IMAGE -->
    <div class="auth-left">
        <img src="/assets/org.png" alt="illustration">
    </div>

    <!-- RIGHT FORM -->
    <div class="auth-right">
        <h2>Buat Akun Baru</h2>

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <input type="text" name="name" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Daftar</button>
        </form>


        <div class="move-link">
            Sudah punya akun? <a href="/login" class="go-login">Login</a>
        </div>
    </div>

</div>

</body>
</html>
