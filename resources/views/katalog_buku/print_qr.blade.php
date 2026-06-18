<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cetak QR - {{ $buku->judul }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color:#111; margin:0; padding:20px; background:#f5f5f5; }
        .toolbar { display:flex; gap:10px; margin-bottom:20px; }
        .toolbar a, .toolbar button { padding:10px 16px; border:1px solid #ddd; border-radius:6px; background:white; cursor:pointer; text-decoration:none; color:#333; font-size:14px; font-weight:500; transition:all 0.2s; }
        .toolbar a:hover, .toolbar button:hover { background:#f0f0f0; border-color:#999; }
        .toolbar .btn-primary { background:#0f766e; color:white; border-color:#0f766e; }
        .toolbar .btn-primary:hover { background:#075d56; }
        .sheet { width:210mm; padding:20mm; margin:auto; background:white; box-shadow:0 2px 4px rgba(0,0,0,0.1); }
        .card { border:1px solid #ccc; padding:12mm; display:flex; flex-direction:column; align-items:center; justify-content:center; }
        .qr { width:170px; height:170px; display:block; }
        .meta { margin-top:12px; text-align:center; }
        .meta .code { font-weight:700; font-size:18px; margin-top:6px; }
        @media print { .toolbar { display:none; } }
    </style>
</head>
<body>
    <div class="toolbar">
        <button class="btn-primary" onclick="window.print()">🖨️ Cetak ke PDF</button>
        <a href="{{ route('katalog.downloadqr', $buku->id) }}" class="btn-primary">⬇️ Download JPEG</a>
        <a href="{{ route('katalog.show', $buku->id) }}" style="background:#e0e0e0; color:#333; border-color:#999;">← Kembali</a>
    </div>
    
    <div class="sheet">
        <div class="card">
            <h2>QR CODE BUKU</h2>
            @if($buku->qr && $buku->qr->qr_path)
                <img src="{{ asset('storage/' . $buku->qr->qr_path) }}" alt="QR" class="qr" />
            @else
                <div style="width:170px;height:170px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;">—</div>
            @endif

            <div class="meta">
                <div>Kode Buku : <span class="code">{{ $buku->kode_buku ?? '—' }}</span></div>
                <div>Judul : {{ $buku->judul }}</div>
            </div>
        </div>
    </div>

    <script>
        // auto print when opened if print=1 in URL
        if (new URLSearchParams(window.location.search).get('print') === '1') {
            window.addEventListener('load', function() {
                setTimeout(function(){ window.print(); }, 300);
            });
        }
    </script>
</body>
</html>
