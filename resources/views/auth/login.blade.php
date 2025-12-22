<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SpendNote</title>
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
        <h2>Masuk ke SpendNote</h2>

        {{-- ======================
            NOTIF LOGIN
        ====================== --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <div class="move-link">
            Belum punya akun? <a href="/register" class="go-register">Daftar</a>
        </div>
    </div>

</div>

</body>

<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => el.remove());
    }, 4000);
</script>

</html>
