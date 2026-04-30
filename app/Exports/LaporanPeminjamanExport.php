<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanPeminjamanExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    WithColumnWidths,
    WithEvents
{
    protected Collection $data;
    protected Carbon $dari;
    protected Carbon $sampai;

    public function __construct(Collection $data, Carbon $dari, Carbon $sampai)
    {
        $this->data   = $data;
        $this->dari   = $dari;
        $this->sampai = $sampai;
    }

    public function collection(): Collection
    {
        return $this->data;
    }

    public function title(): string
    {
        return 'Laporan Peminjaman';
    }

    public function headings(): array
    {
        return [
            // Baris 1: judul laporan (di-merge via AfterSheet event)
            ['LAPORAN PEMINJAMAN BUKU', '', '', '', '', '', '', '', '', ''],
            // Baris 2: periode
            ['SMP Negeri 4 Jember | Periode: ' . $this->dari->format('d/m/Y') . ' s.d. ' . $this->sampai->format('d/m/Y'), '', '', '', '', '', '', '', '', ''],
            // Baris 3: kosong
            ['', '', '', '', '', '', '', '', '', ''],
            // Baris 4: header kolom
            ['No', 'Tanggal Pinjam', 'Nama Anggota', 'Nomor Induk', 'Kelas', 'Judul Buku', 'Kategori', 'Tgl Harus Kembali', 'Tgl Realisasi Kembali', 'Status', 'Denda (Rp)'],
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $row->tgl_pinjam?->format('d/m/Y') ?? '-',
            $row->anggota?->nama_lengkap ?? '-',
            $row->anggota?->nomor_induk ?? '-',
            $row->anggota?->kelas ?? 'Guru/Staf',
            $row->buku?->judul ?? '-',
            $row->buku?->kategori?->nama ?? '-',
            $row->tgl_harus_kembali?->format('d/m/Y') ?? '-',
            $row->tgl_realisasi_kembali?->format('d/m/Y') ?? '-',
            ucfirst($row->status),
            $row->denda > 0 ? number_format($row->denda, 0, ',', '.') : '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 16,
            'C' => 28,
            'D' => 16,
            'E' => 10,
            'F' => 35,
            'G' => 16,
            'H' => 18,
            'I' => 20,
            'J' => 12,
            'K' => 14,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header kolom (baris 4)
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a5c3a']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $this->data->count() + 4;
                $lastCol = 'K';

                // Merge cell judul dan sub-judul
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->mergeCells("A3:{$lastCol}3");

                // Style judul
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1a5c3a']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['size' => 10, 'italic' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Tinggi baris header
                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->getRowDimension(4)->setRowHeight(20);

                // Border seluruh tabel data
                if ($this->data->count() > 0) {
                    $sheet->getStyle("A4:{$lastCol}{$lastRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color'       => ['rgb' => 'D1D5DB'],
                            ],
                        ],
                    ]);
                }

                // Warna baris data: alternating
                for ($i = 5; $i <= $lastRow; $i++) {
                    if ($i % 2 === 0) {
                        $sheet->getStyle("A{$i}:{$lastCol}{$i}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0FDF4']],
                        ]);
                    }
                    // Warna merah untuk terlambat
                    $idx   = $i - 5;
                    $item  = $this->data->values()->get($idx);
                    if ($item && $item->isTerlambat()) {
                        $sheet->getStyle("A{$i}:{$lastCol}{$i}")->applyFromArray([
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF2F2']],
                            'font' => ['color' => ['rgb' => '991B1B']],
                        ]);
                    }
                }

                // Baris total denda di akhir
                $totalRow = $lastRow + 2;
                $total    = $this->data->sum('denda');
                $sheet->setCellValue("J{$totalRow}", 'Total Denda:');
                $sheet->setCellValue("K{$totalRow}", number_format($total, 0, ',', '.'));
                $sheet->getStyle("J{$totalRow}:K{$totalRow}")->applyFromArray([
                    'font'      => ['bold' => true],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FEF9C3']],
                ]);

                // Freeze baris header
                $sheet->freezePane('A5');

                // Cetak area
                $sheet->getPageSetup()->setPrintArea("A1:{$lastCol}{$lastRow}");
                $sheet->getPageSetup()->setFitToPage(true);
                $sheet->getPageSetup()->setFitToWidth(1);
            },
        ];
    }
}