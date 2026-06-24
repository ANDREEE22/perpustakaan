<?php

namespace App\Imports;

use App\Models\Anggota;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class AnggotaImport implements ToCollection
{
    public int $inserted = 0;
    public int $skipped = 0;

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            return;
        }

        // Detect header row: check first row for 'nomor' or 'nama'
        $first = $rows->first()->map(fn($v) => Str::lower(trim((string) $v)));
        $hasHeader = $first->contains('nomor_induk') || $first->contains('nama_lengkap') || $first->contains('nomor induk');

        $start = $hasHeader ? 1 : 0;

        foreach ($rows->slice($start) as $row) {
            // Map columns by order requested by user:
            // 0: nomor_induk, 1: nama_lengkap, 2: jenis_kelamin, 3: kelas,
            // 4: tempat_lahir, 5: tanggal_lahir, 6: no_telepon, 7: alamat
            $nomor_induk = trim((string) ($row[0] ?? ''));
            $nama        = trim((string) ($row[1] ?? ''));

            if ($nomor_induk === '' || $nama === '') {
                $this->skipped++;
                continue;
            }

            // Skip duplicates by nomor_induk
            if (Anggota::where('nomor_induk', $nomor_induk)->exists()) {
                $this->skipped++;
                continue;
            }

            $jenis = trim((string) ($row[2] ?? ''));
            $kelas = trim((string) ($row[3] ?? ''));
            $tempat = trim((string) ($row[4] ?? ''));
            $tgl = $row[5] ?? null;
            $telp = trim((string) ($row[6] ?? ''));
            $alamat = trim((string) ($row[7] ?? ''));

            // Normalize jenis_kelamin to 'L' or 'P'
            $jk = null;
            if ($jenis !== '') {
                $low = Str::lower($jenis);
                if (str_contains($low, 'l')) $jk = 'L';
                elseif (str_contains($low, 'p')) $jk = 'P';
            }

            // Convert excel date to Y-m-d when possible
            $tanggal = null;
            if ($tgl !== null && $tgl !== '') {
                if (is_numeric($tgl)) {
                    try {
                        $dt = ExcelDate::excelToDateTimeObject($tgl);
                        $tanggal = $dt->format('Y-m-d');
                    } catch (\Throwable $e) {
                        $tanggal = null;
                    }
                } else {
                    // Try parse string to date
                    $d = date_create(trim((string) $tgl));
                    $tanggal = $d ? $d->format('Y-m-d') : null;
                }
            }

            Anggota::create([
                'nomor_induk' => $nomor_induk,
                'nama_lengkap' => $nama,
                'jenis_kelamin' => $jk,
                'kelas' => $kelas ?: null,
                'tempat_lahir' => $tempat ?: null,
                'tanggal_lahir' => $tanggal,
                'no_telepon' => $telp ?: null,
                'alamat' => $alamat ?: null,
            ]);

            $this->inserted++;
        }
    }
}
