<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - SADARIN</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #7e2b16, #ff8138);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-reset {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .logo {
            font-weight: bold;
            color: #000000;
        }
        .logo .in {
            color: #ff8138;
        }
    </style>
</head>

<body>

    <div class="card card-reset">

        <div class="card-body p-4">

            <center><h5 class="mb-4">
                <img src="{{ asset('assets/image/pemprov.png') }}" width="150">
            </h5>
            </center>
            <div class="text-center mb-3">
                <h4 class="logo">SADAR<span class="in">IN</span></h4>
                <p class="text-muted mb-0">Atur Password Baru</p>
            </div>

            <!-- Alert -->
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.reset.save') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Password -->
                <div class="mb-3">

                    <label class="form-label">Password Baru</label>

                    <div class="input-group">

                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" id="password" placeholder="Masukkan password baru" required>

                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                            👁
                        </button>

                    </div>

                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <!-- Konfirmasi -->
                <div class="mb-4">

                    <label class="form-label">Konfirmasi Password</label>

                    <input type="password" class="form-control" name="password_confirmation"
                        placeholder="Ulangi password" required>

                </div>

                <!-- Button -->
                <div class="d-grid">

                    <button class="btn btn-success btn-lg">
                        Simpan Password
                    </button>

                </div>

            </form>

            <div class="text-center mt-3">
                <small class="text-muted">
                    Pastikan password minimal 8 karakter
                </small>
            </div>

        </div>
    </div>


    <script>
        function togglePassword() {

            const input = document.getElementById("password");

            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }

        }
    </script>

</body>

</html>
