<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kunjungan;
use Carbon\Carbon;

class GenerateNomorPermohonan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:nomor-permohonan';
    protected $description = 'Generate nomor_permohonan untuk data yang belum memilikinya';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Mulai generate nomor permohonan...");

        // Ambil semua permohonan yang belum ada nomor permohonannya
        $permohonans = Kunjungan::whereNull('nomor_permohonan')->orderBy('created_at')->get();

        // Group by tanggal
        $grouped = $permohonans->groupBy(function($item) {
            return $item->created_at->format('Ymd');
        });

        foreach ($grouped as $tanggal => $items) {
            $i = 1;
            foreach ($items as $permohonan) {
                $nomorUrut = str_pad($i, 3, '0', STR_PAD_LEFT);
                $permohonan->nomor_permohonan = 'PK-' . $tanggal . $nomorUrut;
                $permohonan->save();
                $this->line("✔ " . $permohonan->id . " -> " . $permohonan->nomor_permohonan);
                $i++;
            }
        }

        $this->info("Selesai generate semua nomor permohonan.");
    }
}
