<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cetak QR Massal - {{ count($bukus) }} Buku</title>
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }

        * { box-sizing: border-box; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
            margin: 0;
            padding: 0;
            background: #e5e5e5;
        }

        /* ── Toolbar (disembunyikan saat print) ── */
        .toolbar {
            display: flex;
            gap: 10px;
            padding: 16px 20px;
            background: #fff;
            border-bottom: 1px solid #ddd;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .toolbar a, .toolbar button {
            padding: 10px 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
            cursor: pointer;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }
        .toolbar .btn-primary {
            background: #0f766e;
            color: white;
            border-color: #0f766e;
        }
        .toolbar .btn-primary:hover { background: #075d56; }
        .toolbar .info {
            align-self: center;
            font-size: 13px;
            color: #666;
        }
        @media print {
            .toolbar { display: none; }
            body { background: #fff; }
        }

        /* ── Halaman & Grid Label ── */
        .page {
            width: 19cm; /* A4 (21cm) dikurangi margin @page 1cm kiri-kanan */
            margin: 20px auto;
            background: #fff;
            padding: 0;
        }
        @media print {
            .page {
                width: auto;
                margin: 0;
                box-shadow: none;
            }
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 5cm);
            gap: 0.45cm;
            justify-content: center;
        }

        .label {
            width: 5cm;
            height: 6cm;
            border: 1px dashed #aaa;
            border-radius: 2px;
            padding: 0.25cm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .label .qr-wrap {
            width: 3.3cm;
            height: 3.3cm;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .label .qr-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .label .qr-wrap .placeholder {
            width: 100%;
            height: 100%;
            background: #f5f5f5;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #999;
        }

        .label .judul {
            font-size: 8.5px;
            font-weight: 700;
            margin-top: 0.15cm;
            line-height: 1.2;
            max-height: 2.4em;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .label .kode {
            font-size: 7.5px;
            color: #555;
            margin-top: 0.1cm;
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button class="btn-primary" onclick="window.print()">🖨️ Cetak ke PDF</button>
        <a href="{{ route('katalog') }}" style="background:#e0e0e0; color:#333; border-color:#999;">← Kembali</a>
        <span class="info">{{ count($bukus) }} label QR akan dicetak &bull; Ukuran kertas A4 &bull; Label 5cm × 6cm</span>
    </div>

    <div class="page">
        <div class="grid">
            @foreach($bukus as $buku)
                <div class="label">
                    <div class="qr-wrap">
                        @if($buku->qr && $buku->qr->qr_path)
                            <img src="{{ asset('storage/' . $buku->qr->qr_path) }}" alt="QR {{ $buku->kode_buku }}">
                        @else
                            <div class="placeholder">Tanpa QR</div>
                        @endif
                    </div>
                    <div class="judul">{{ $buku->judul }}</div>
                    <div class="kode">{{ $buku->kode_buku ?? '—' }}</div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>