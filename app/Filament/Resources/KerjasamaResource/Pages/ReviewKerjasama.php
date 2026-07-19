<?php

namespace App\Filament\Resources\KerjasamaResource\Pages;

use App\Filament\Resources\KerjasamaResource;
use App\Models\Kerjasama;
use App\Models\KsMetode;
use App\Models\KsImplementasi;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ReviewKerjasama extends ViewRecord
{
    protected static string $resource = KerjasamaResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        $record = $this->getRecord();
        $status = (int) $record->ks_status_dok;
        $waitingUndangan = $status === 2 && !$record->surat_undangan;
        $hasJadwal = (bool) $record->tanggal_pembahasan;

        $schema = [
            Section::make('Status Dokumen')
                ->icon('heroicon-o-flag')
                ->schema([
                    TextEntry::make('statusDok.nama_status')->label('Status')->badge()->color(fn ($state) => match($state) {
                        'Mengirimkan surat permohonan' => 'info',
                        'Pemohon menyampaikan undangan pembahasan NK/PKS/NDA' => 'warning',
                        'Dokumen dalam proses pembahasan' => 'warning',
                        'Dokumen dalam proses penandatanganan' => 'primary',
                        'Dokumen telah ditandatangani dan diterima oleh masing-masing Pihak' => 'success',
                        'masa berlaku selesai' => 'gray',
                        default => 'gray',
                    }),
                ])->columns(1),
        ];

        if ($waitingUndangan) {
            $schema[] = Section::make('Menunggu Mitra')
                ->icon('heroicon-o-clock')
                ->description('Setelah disetujui, mitra harus mengupload surat undangan pembahasan terlebih dahulu. Admin belum dapat melanjutkan ke tahap penjadwalan.')
                ->schema([]);
        }

        if ($hasJadwal) {
            $schema[] = Section::make('Jadwal Pembahasan')
                ->icon('heroicon-o-calendar-days')
                ->schema([
                    TextEntry::make('tanggal_pembahasan')->label('Tanggal & Waktu')->dateTime('d M Y, H:i'),
                ])->columns(1);
        }

        $schema = array_merge($schema, [
            Section::make('Data Mitra')
                ->icon('heroicon-o-building-office-2')
                ->schema([
                    TextEntry::make('nama_kl')->label('Instansi'),
                    TextEntry::make('jenis.nama_jenis')->label('Jenis'),
                    TextEntry::make('tingkat.nama_tingkat')->label('Tingkat'),
                ])->columns(3),
            Section::make('Kontak')
                ->icon('heroicon-o-phone')
                ->schema([
                    TextEntry::make('narahubung_adm')->label('Narahubung Adm'),
                    TextEntry::make('nomor_cp_adm')->label('No. Kontak Adm'),
                    TextEntry::make('narahubung_teknis')->label('Narahubung Teknis'),
                    TextEntry::make('nomor_cp_teknis')->label('No. Kontak Teknis'),
                ])->columns(2),
            Section::make('Dokumen')
                ->icon('heroicon-o-paper-clip')
                ->schema([
                    TextEntry::make('dokumen_ks_display')->label('Dokumen')->html(),
                    TextEntry::make('dokumen_pendukung')->label('Pendukung')->default('-'),
                ]),
            Section::make('Surat Undangan')
                ->icon('heroicon-o-envelope')
                ->schema([
                    TextEntry::make('surat_undangan_display')->label('File')->html(),
                ]),
            Section::make('Riwayat Aktivitas')
                ->icon('heroicon-o-clock')
                ->schema([
                    TextEntry::make('review_log_display')->label('Riwayat')->html(),
                ]),
        ]);

        return $infolist->schema($schema);
    }

    protected function getHeaderActions(): array
    {
        $ks = $this->getRecord();
        $status = $ks->ks_status_dok;

        return match ((int) $status) {
            1 => [
                Actions\Action::make('setujui')
                    ->label('Setujui')->color('success')->requiresConfirmation()
                    ->action(function () {
                        $record = $this->getRecord();
                        $record->update(['ks_status_dok' => 2]);
                        $record->addReviewEntry('Disetujui', 'Pengajuan disetujui');
                        Notification::make()->title('Disetujui')->success()->send();
                        $this->redirect($this->getResource()::getUrl('review', ['record' => $record->kerjasama_id]));
                    }),
                Actions\Action::make('tolak')
                    ->label('Tolak')->color('danger')
                    ->modalHeading('Tolak Pengajuan')
                    ->form([Textarea::make('alasan')->required()->label('Alasan Penolakan'), Textarea::make('catatan')->label('Catatan Perbaikan')])
                    ->action(function (array $data) {
                        $record = $this->getRecord();
                        $record->update(['ks_status_dok' => null]);
                        $record->addReviewEntry('Ditolak', $data['alasan'], $data['catatan'] ?? null);
                        Notification::make()->title('Pengajuan ditolak')->warning()->send();
                        $this->redirect($this->getResource()::getUrl('review', ['record' => $record->kerjasama_id]));
                    }),
            ],
            2 => $ks->surat_undangan ? [
                Actions\Action::make('jadwalkan')
                    ->label('Jadwalkan')->color('warning')
                    ->form([
                        DateTimePicker::make('tanggal_pembahasan')->required()->label('Tanggal & Waktu')
                            ->native(false)->displayFormat('d M Y, H:i')
                            ->format('Y-m-d H:i:s'),
                    ])
                    ->action(function ($data) use ($ks) {
                        $ks->update(array_merge($data, ['ks_status_dok' => 3]));
                        $ks->addReviewEntry('Jadwal', "Pembahasan {$data['tanggal_pembahasan']}");
                        Notification::make()->title('Jadwal disimpan')->success()->send();
                        $this->redirect($this->getResource()::getUrl('review', ['record' => $ks->kerjasama_id]));
                    }),
            ] : [],
            3 => [],
            4 => [
                Actions\Action::make('finalisasi')
                    ->label('Finalisasi')->color('success')
                    ->modalHeading('Finalisasi Kerja Sama')
                    ->form([
                        FileUpload::make('dokumen_final')->label('Dokumen Final (TTD)')
                            ->acceptedFileTypes(['application/pdf','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/zip'])
                            ->maxSize(20480)->disk('public')->directory('dokumen/'.$ks->kerjasama_id),
                        TextInput::make('ttd_pihak1')->label('Penandatangan Pihak 1'),
                        TextInput::make('ttd_pihak2')->label('Penandatangan Pihak 2'),
                        TextInput::make('kode_wilayah')->label('Kode Wilayah')->maxLength(20),
                        TextInput::make('pihak1')->label('Pihak 1'),
                        TextInput::make('pihak2')->label('Pihak 2'),
                        Textarea::make('tentang')->label('Perihal'),
                        TextInput::make('jumlah_kl_terlibat')->label('Jumlah K/L')->numeric()->minValue(1),
                        TextInput::make('jangka_waktu_thn')->label('Jangka (thn)')->numeric()->minValue(1),
                        DatePicker::make('tanggal_mulai_ks')->label('Tgl Mulai'),
                        DatePicker::make('tanggal_selesai_ks')->label('Tgl Berakhir'),
                        TextInput::make('nomor_pihak1')->label('No Pihak 1'),
                        TextInput::make('nomor_pihak2')->label('No Pihak 2'),
                        Select::make('ks_metode')->label('Metode')->options(KsMetode::pluck('nama_metode','id')->toArray()),
                        Select::make('ks_implementasi')->label('Implementasi')->options(KsImplementasi::pluck('nama_status','id')->toArray()),
                        Textarea::make('pusdatin_kirim_data')->label('Data Dikirim'),
                        Textarea::make('pusdatin_terima_data')->label('Data Diterima'),
                    ])
                    ->action(function (array $data) use ($ks) {
                        if ($data['dokumen_final'] ?? null) {
                            $e = $ks->dokumen_ks ? json_decode($ks->dokumen_ks, true) ?: [] : [];
                            $e[] = $data['dokumen_final'];
                            $ks->update(['dokumen_ks' => json_encode($e)]);
                        }
                        $data['ks_status_dok'] = 5;
                        $ks->update($data);
                        $ks->addReviewEntry('Finalisasi', 'Kerja sama difinalisasi');
                        Notification::make()->title('Finalisasi selesai')->success()->send();
                        $this->redirect($this->getResource()::getUrl('review', ['record' => $ks->kerjasama_id]));
                    }),
            ],
            default => [],
        };
    }
}
