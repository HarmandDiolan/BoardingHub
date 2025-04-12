<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Bootstrap Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body class="bg-light text-dark d-flex p-3 justify-content-center align-items-center min-vh-100 flex-column">


    <div class="d-flex justify-content-center align-items-center w-100">
        <main class="d-flex flex-column-reverse flex-lg-row justify-content-center w-100">
            <form action="{{ route('subdomain.store') }}" method="post" class="w-100 mx-auto bg-white p-4 rounded shadow-lg">
                @csrf
                <div class="mb-3">
                    <label for="subdomain" class="form-label h5">Subdomain</label>
                    <div class="d-flex align-items-center gap-3">
                        <input type="text" name="subdomain" id="subdomain" class="form-control p-3" placeholder="Enter your subdomain" required>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </main>
    </div>

    @if (Route::has('login'))
        <div class="d-none d-lg-block h-4"></div>
    @endif

    <!-- Bootstrap JS Bundle (for functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
