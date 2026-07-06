<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@php($swal = session()->pull('swal'))
@if ($swal)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire(@json($swal));
        });
    </script>
@endif

@php($successMessage = session()->pull('success'))
@if ($successMessage)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Berhasil',
                text: @json($successMessage),
                icon: 'success',
                timer: 2200,
                showConfirmButton: false,
            });
        });
    </script>
@endif

@php($errorMessage = session()->pull('error'))
@if ($errorMessage)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Gagal',
                text: @json($errorMessage),
                icon: 'error',
                timer: 2600,
                showConfirmButton: false,
            });
        });
    </script>
@endif

@php($infoDenda = session()->pull('info_denda'))
@if ($infoDenda)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Informasi',
                html: {!! json_encode($infoDenda, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!},
                icon: 'info',
                confirmButtonText: 'Tutup',
            });
        });
    </script>
@endif
