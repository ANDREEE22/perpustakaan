<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Buku;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GenerateQrs extends Command
{
    protected $signature = 'generate:qrs {--all : Generate for all books with kode_buku}';
    protected $description = 'Generate QR images for Buku records that have kode_buku but missing QR';

    public function handle()
    {
        $this->info('Scanning books...');

        $query = Buku::query()->whereNotNull('kode_buku')->where('kode_buku', '!=', '');

        $books = $query->get();
        $count = 0;
        foreach ($books as $buku) {
            $hasQr = $buku->qr && !empty($buku->qr->qr_path) && Storage::disk('public')->exists($buku->qr->qr_path);
            if ($hasQr) continue;

            $this->line('Generating QR for: ' . $buku->id . ' - ' . $buku->kode_buku);
            $url = 'https://quickchart.io/qr?text=' . urlencode($buku->kode_buku) . '&size=300';
            try {
                $resp = Http::timeout(10)->withoutVerifying()->get($url);
            } catch (\Throwable $e) {
                $this->error('Failed HTTP for ' . $buku->id . ': ' . $e->getMessage());
                continue;
            }
            if (!$resp->ok()) {
                $this->error('Non-OK response for ' . $buku->id);
                continue;
            }
            $content = $resp->body();
            $fileName = 'qr_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $buku->kode_buku) . '.png';
            $path = 'qr_codes/' . $fileName;
            Storage::disk('public')->put($path, $content);

            // update or create qrs record
            if ($buku->qr) {
                $buku->qr->update(['qr_path' => $path]);
            } else {
                $buku->qr()->create(['kode_buku' => $buku->kode_buku, 'qr_path' => $path]);
            }
            $count++;
        }

        $this->info("Done. Generated: $count");
        return 0;
    }
}
