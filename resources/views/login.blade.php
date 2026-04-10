<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Inventory Management</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5 text-center">
            <h1 class="text-primary">Inventory management of SMK WIKRAMA</h1>
            <h2 class="">Kendali penuh atas aset Anda, di mana saja.</h2>
            <img src="{{ asset('images/inventory.jpg')}}" alt="inventoryppl" class="img-fluid rounded mb-4">

            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalLogin">Login</button>
        </div>

        <div class="modal fade" id="modalLogin" tabindex="-1" aria-labelledby="modalLoginLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLoginLabel">Silahkan Login</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="/login">
                            @csrf
                            <div class="mb-3">   
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Masuk</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <footer class="bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0">
        © 2026 Inventory | Dibuat oleh Nisa Samrotul Fuadah.
    </p>
    </footer>
    
    @if ($errors->any() || session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var loginBtn = document.querySelector('[data-bs-target="#modalLogin"]');
                if (loginBtn) loginBtn.click();
            });
        </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    
</html>