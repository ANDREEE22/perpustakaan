<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Qr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class KatalogBukuController extends Controller
{
    /**
     * Menampilkan daftar buku dengan fitur search, filter, dan pagination.
     */
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        // Filter pencarian: judul, pengarang, isbn
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori_id
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        // Filter berdasarkan status stok
        if ($request->filled('status')) {
            match ($request->status) {
                'tersedia' => $query->where('stok', '>', 5),
                'terbatas' => $query->whereBetween('stok', [1, 5]),
                'habis' => $query->where('stok', 0),
                default => null,
            };
        }

        $data_buku = $query->latest()->paginate(10)->withQueryString();
        $kategoris = Kategori::orderBy('nama')->get();

        return view('katalog_buku.index', compact('data_buku', 'kategoris'));
    }

    /**
     * Menampilkan form tambah buku baru.
     */
    public function create()
    {
        $kategoris = Kategori::orderBy('nama')->get();

        return view('katalog_buku.create', compact('kategoris'));
    }

    /**
     * Menyimpan data buku baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_buku' => 'nullable|string|max:50|unique:bukus,kode_buku',
            'judul' => 'required|string|max:255',
            'isbn' => 'required|string|max:50|unique:bukus,isbn',
            'kategori_id' => 'required|exists:kategoris,id',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:'.date('Y'),
            'stok' => 'required|integer|min:0',
            'lokasi_rak' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'sampul' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload sampul jika ada
        if ($request->hasFile('sampul')) {
            $validated['sampul'] = $request->file('sampul')->store('sampul_buku', 'public');
        }

        $buku = Buku::create($validated);

        // Generate QR jika kode_buku ada
        if (! empty($validated['kode_buku'])) {
            $this->generateQrForBuku($buku);
        }

        return redirect()->route('katalog')->with('success', 'Buku "'.$validated['judul'].'" berhasil ditambahkan!');
    }

    /**
     * Import buku dari file Excel (header: judul, kode_buku, isbn, kategori_id, pengarang, penerbit, tahun_terbit, stok, lokasi_rak, description)
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $path = $request->file('file')->getRealPath();
        $sheets = Excel::toArray([], $request->file('file'));
        $rows = $sheets[0] ?? [];
        $created = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            // skip header row if contains non-empty string 'judul' in first cell
            if ($index === 0 && is_string($row[0]) && Str::lower(trim($row[0])) === 'judul') {
                continue;
            }

            // map columns (assume order provided by user)
            $data = [
                'judul' => $row[0] ?? null,
                'kode_buku' => $row[1] ?? null,
                'isbn' => $row[2] ?? null,
                'kategori_id' => $row[3] ?? null,
                'pengarang' => $row[4] ?? null,
                'penerbit' => $row[5] ?? null,
                'tahun_terbit' => $row[6] ?? null,
                'stok' => $row[7] ?? 0,
                'lokasi_rak' => $row[8] ?? null,
                'description' => $row[9] ?? null,
            ];

            // basic validation per row
            try {
                $validated = validator($data, [
                    'judul' => 'required|string|max:255',
                    'kode_buku' => 'nullable|string|max:50|unique:bukus,kode_buku',
                    'isbn' => 'required|string|max:50|unique:bukus,isbn',
                    'kategori_id' => 'required|exists:kategoris,id',
                    'pengarang' => 'required|string|max:255',
                    'penerbit' => 'nullable|string|max:255',
                    'tahun_terbit' => 'nullable|integer|min:1900|max:'.date('Y'),
                    'stok' => 'required|integer|min:0',
                    'lokasi_rak' => 'nullable|string|max:50',
                    'description' => 'nullable|string',
                ])->validate();

                $buku = Buku::create($validated);
                if (! empty($validated['kode_buku'])) {
                    $this->generateQrForBuku($buku);
                }
                $created++;
            } catch (\Throwable $e) {
                $errors[] = 'Baris '.($index + 1).': '.$e->getMessage();
            }
        }

        $msg = "$created buku berhasil diimpor.";
        if (! empty($errors)) {
            return redirect()->route('katalog.create')->with('error', $msg.' Beberapa baris gagal: '.implode('; ', array_slice($errors, 0, 5)));
        }

        return redirect()->route('katalog')->with('success', $msg);
    }

    /**
     * Generate QR image for a Buku using its kode_buku and save record.
     */
    protected function generateQrForBuku(Buku $buku)
    {
        if (empty($buku->kode_buku)) {
            return null;
        }

        // Use Google Chart API to generate QR PNG via HTTP client
        // Use QuickChart QR API (more reliable) instead of deprecated Google Chart API
        $url = 'https://quickchart.io/qr?text='.urlencode($buku->kode_buku).'&size=300';
        try {
            $resp = Http::timeout(10)->withoutVerifying()->get($url);
        } catch (\Throwable $e) {
            return null;
        }

        if (! $resp->ok()) {
            return null;
        }

        $content = $resp->body();
        $fileName = 'qr_'.preg_replace('/[^A-Za-z0-9_\-]/', '_', $buku->kode_buku).'.png';
        $path = 'qr_codes/'.$fileName;
        Storage::disk('public')->put($path, $content);

        Qr::updateOrCreate(
            ['buku_id' => $buku->id],
            ['kode_buku' => $buku->kode_buku, 'qr_path' => $path]
        );

        return $path;
    }

    /**
     * Menampilkan detail satu buku.
     */
    public function show($id)
    {
        $buku = Buku::with('kategori', 'qr')->findOrFail($id);

        // Jika buku sudah punya kode_buku tapi QR belum ada, coba generate otomatis
        if ((empty($buku->qr) || empty($buku->qr->qr_path)) && ! empty($buku->kode_buku)) {
            $this->generateQrForBuku($buku);
            $buku->load('qr');
        }

        return view('katalog_buku.show', compact('buku'));
    }

    /**
     * Show printable QR layout (simple page intended for printing to PDF or paper)
     */
    public function printQr($id)
    {
        $buku = Buku::with('qr')->findOrFail($id);

        // Attempt to generate QR if missing but kode_buku exists
        if ((empty($buku->qr) || empty($buku->qr->qr_path)) && ! empty($buku->kode_buku)) {
            $this->generateQrForBuku($buku);
            // reload relation
            $buku->load('qr');
        }

        return view('katalog_buku.print_qr', compact('buku'));
    }

    /**
     * Menampilkan form edit buku.
     */
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::orderBy('nama')->get();

        return view('katalog_buku.edit', compact('buku', 'kategoris'));
    }

    /**
     * Mengupdate data buku di database.
     */
    public function update(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);

        $validated = $request->validate([
            'kode_buku' => 'nullable|string|max:50|unique:bukus,kode_buku,'.$id,
            'judul' => 'required|string|max:255',
            'isbn' => 'required|string|max:50|unique:bukus,isbn,'.$id,
            'kategori_id' => 'required|exists:kategoris,id',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:'.date('Y'),
            'stok' => 'required|integer|min:0',
            'lokasi_rak' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'sampul' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Upload sampul baru & hapus yang lama jika ada
        if ($request->hasFile('sampul')) {
            if ($buku->sampul) {
                Storage::disk('public')->delete($buku->sampul);
            }
            $validated['sampul'] = $request->file('sampul')->store('sampul_buku', 'public');
        }

        $oldKode = $buku->kode_buku;
        $buku->update($validated);

        // regenerate QR when kode_buku changed
        if (($oldKode ?? null) !== ($buku->kode_buku ?? null)) {
            // delete old QR file if exists
            if ($buku->qr && $buku->qr->qr_path) {
                Storage::disk('public')->delete($buku->qr->qr_path);
            }
            if (! empty($buku->kode_buku)) {
                $this->generateQrForBuku($buku);
            }
        }

        return redirect()->route('katalog')->with('success', 'Buku "'.$buku->judul.'" berhasil diperbarui!');
    }

    /**
     * Menghapus data buku dari database.
     */
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        // Hapus file sampul dari storage jika ada
        if ($buku->sampul) {
            Storage::disk('public')->delete($buku->sampul);
        }

        // Hapus QR jika ada
        if ($buku->qr && $buku->qr->qr_path) {
            Storage::disk('public')->delete($buku->qr->qr_path);
            $buku->qr->delete();
        }

        $judulBuku = $buku->judul;
        $buku->delete();

        return redirect()->route('katalog')->with('success', 'Buku "'.$judulBuku.'" berhasil dihapus.');
    }

    /**
     * Download QR code as JPEG (4x4cm) with book name text below
     */
    public function downloadQrJpeg($id)
    {
        $buku = Buku::with('qr')->findOrFail($id);

        // Generate QR if missing
        if ((empty($buku->qr) || empty($buku->qr->qr_path)) && ! empty($buku->kode_buku)) {
            $this->generateQrForBuku($buku);
            $buku->load('qr');
        }

        // Set DPI and calculate 4x4cm in pixels (96 DPI = ~151x151px, use 200x200 for better quality)
        $dpi = 96;
        $cmToPixels = $dpi / 2.54; // 1cm = ~37.8px at 96 DPI
        $targetWidth = (int) (4 * $cmToPixels); // ~151px

        // Fetch compact QR code (200x200) directly from API
        $qrUrl = 'https://quickchart.io/qr?text='.urlencode($buku->kode_buku).'&size=200';
        try {
            $resp = Http::timeout(10)->withoutVerifying()->get($qrUrl);
        } catch (\Throwable $e) {
            abort(500, 'Gagal membuat QR code');
        }

        if (! $resp->ok()) {
            abort(500, 'Gagal membuat QR code');
        }

        // Load QR image
        $qrImage = imagecreatefromstring($resp->body());
        if (! $qrImage) {
            abort(500, 'Gagal memproses QR code');
        }

        $qrSize = 200; // QR size from API

        // Compact layout: minimal padding
        $padding = 12;
        $textPadding = 10;
        $canvasWidth = $qrSize + ($padding * 2);

        // Colors
        $white = imagecolorallocate(imagecreatetruecolor(1, 1), 255, 255, 255);
        $black = imagecolorallocate(imagecreatetruecolor(1, 1), 0, 0, 0);
        $darkGray = imagecolorallocate(imagecreatetruecolor(1, 1), 60, 60, 60);

        // Create new canvas for title + code text
        $titleCanvas = imagecreatetruecolor(250, 50);
        $canvasBg = imagecolorallocate($titleCanvas, 255, 255, 255);
        imagefill($titleCanvas, 0, 0, $canvasBg);

        $titleBlack = imagecolorallocate($titleCanvas, 0, 0, 0);
        $titleGray = imagecolorallocate($titleCanvas, 100, 100, 100);

        // Prepare title text (max 25 chars, truncate if needed)
        $judul = $buku->judul;
        $maxLen = 25;
        if (strlen($judul) > $maxLen) {
            $judul = substr($judul, 0, $maxLen);
        }

        // Write title (font 2 = small)
        $titleX = ($canvasWidth - (strlen($judul) * imagefontwidth(2))) / 2;
        imagestring($titleCanvas, 2, 5, 5, $judul, $titleBlack);

        // Write kode (font 1 = tiny)
        $kodeText = 'Kode: '.$buku->kode_buku;
        $kodeX = ($canvasWidth - (strlen($kodeText) * imagefontwidth(1))) / 2;
        imagestring($titleCanvas, 1, 5, 24, $kodeText, $titleGray);

        // Final canvas height = QR + text + padding
        $textHeight = 40;
        $finalHeight = $qrSize + ($padding * 2) + $textHeight;
        $canvasHeight = $finalHeight;

        // Create final canvas
        $canvas = imagecreatetruecolor($canvasWidth, $canvasHeight);
        $canvasWhite = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $canvasWhite);

        // Place QR code centered
        $qrX = ($canvasWidth - $qrSize) / 2;
        imagecopy($canvas, $qrImage, $qrX, $padding, 0, 0, $qrSize, $qrSize);

        // Add thin border around QR
        $borderColor = imagecolorallocate($canvas, 180, 180, 180);
        imagerectangle($canvas, (int) $qrX - 2, $padding - 2, (int) $qrX + $qrSize + 1, $padding + $qrSize + 1, $borderColor);

        // Place text info below QR
        $textY = $qrSize + $padding + $textPadding;
        $textCanvas = imagecreatetruecolor($canvasWidth, $textHeight);
        imagefill($textCanvas, 0, 0, $canvasWhite);

        $canvasBlack = imagecolorallocate($textCanvas, 0, 0, 0);
        $canvasGray = imagecolorallocate($textCanvas, 100, 100, 100);

        // Title
        $titleX = (int) (($canvasWidth - (strlen($judul) * imagefontwidth(2))) / 2);
        imagestring($textCanvas, 2, $titleX, 3, $judul, $canvasBlack);

        // Kode
        $kodeX = (int) (($canvasWidth - (strlen($kodeText) * imagefontwidth(1))) / 2);
        imagestring($textCanvas, 1, $kodeX, 18, $kodeText, $canvasGray);

        // Merge text canvas into final canvas
        imagecopy($canvas, $textCanvas, 0, $textY, 0, 0, $canvasWidth, $textHeight);

        // Output as JPEG download
        header('Content-Type: image/jpeg');
        header('Content-Disposition: attachment; filename="QR_'.preg_replace('/[^A-Za-z0-9_-]/', '_', $buku->judul).'.jpg"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        imagejpeg($canvas, null, 95);

        imagedestroy($qrImage);
        imagedestroy($canvas);
        imagedestroy($titleCanvas);
        imagedestroy($textCanvas);
        exit;
    }
}
