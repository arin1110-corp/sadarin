<!-- resources/views/auth/admin-login.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HALAMAN LOGIN SADARIN</title>
    <link rel="icon" href="{{ asset('assets/image/pemprov.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
    body {
        background: #f8f9fa;
        font-family: 'Segoe UI', sans-serif;
    }

    .in {
        color: orangered;
    }

    .container {
        max-width: 500px;
        margin-top: 80px;
        background: white;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        padding: 40px;
    }

    .form-access input {
        border-radius: 10px;
        height: 45px;
    }

    .form-access button {
        border-radius: 10px;
    }
    </style>
</head>

<body>
    <div class="container text-center">
        <h3 class="mb-4">Login SADAR<span class="in">IN</span></h3>

        @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first('login') }}
        </div>
        @endif

        <div class="form-access mt-4">
            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                @if ($errors->any())
                <div style="color:red;">
                    {{ $errors->first('error') }}
                </div>
                @endif
                <div class="mb-3">
                    <input type="text" name="nip" class="form-control" placeholder="Masukkan NIP" required
                        value="{{ old('nip') }}">
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Masukkan Password"
                        required>
                </div>
                <button type="submit" class="btn btn-danger w-100">LOGIN</button>
            </form>
        </div>

        <div class="mt-4">
            <a href="{{ route('akses.form') }}" class="btn btn-outline-secondary">Kembali ke Halaman Utama</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>