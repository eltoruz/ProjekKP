<?php

namespace App\Filament\Resources\KerjasamaResource\Pages;

use App\Filament\Resources\KerjasamaResource;
use App\Models\Kerjasama;
use App\Models\KsMetode;
use App\Models\KsImplementasi;
use App\Services\WorkflowService;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
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
        return $infolist->schema([
            Section::make('Status')->schema([
                TextEntry::make('status_pengajuan')->label('Workflow')->badge(),
                TextEntry::make('statusDok.nama_status')->label('Status Dokumen'),
            ])->columns(2),
            Section::make('Mitra')->schema([
                TextEntry::make('nama_kl')->label('Instansi'),
                TextEntry::make('jenis.nama_jenis')->label('Jenis'),
                TextEntry::make('tingkat.nama_tingkat')->label('Tingkat'),
                TextEntry::make('provinsi')->label('Provinsi'),
                TextEntry::make('pihak1')->label('Pihak 1'),
                TextEntry::make('pihak2')->label('Pihak 2'),
                TextEntry::make('tentang')->columnSpanFull(),
            ])->columns(3),
            Section::make('Waktu')->schema([
                TextEntry::make('jangka_waktu_thn')->label('Jangka (thn)'),
                TextEntry::make('tanggal_mulai_ks')->date()->label('Mulai'),
                TextEntry::make('tanggal_selesai_ks')->date()->label('Berakhir'),
            ])->columns(3),
            Section::make('Pembahasan')->schema([
                TextEntry::make('tanggal_pembahasan_ks')->date()->label('Tgl'),
                TextEntry::make('jam_pembahasan')->label('Jam')->formatStateUsing(function ($state) {
                    return $state && is_string($state) ? substr($state, 0, 5) : '-';
                }),
                TextEntry::make('lokasi_pembahasan')->label('Lokasi'),
                TextEntry::make('pic_pembahasan')->label('PIC'),
            ])->columns(2),
            Section::make('Finalisasi')->schema([
                TextEntry::make('jangka_waktu_thn')->label('Jangka (thn)')->default('-'),
                TextEntry::make('tanggal_mulai_ks')->date()->label('Tgl Mulai'),
                TextEntry::make('tanggal_selesai_ks')->date()->label('Tgl Berakhir'),
                TextEntry::make('metode.nama_metode')->label('Metode')->default('Belum'),
                TextEntry::make('implementasi.nama_status')->label('Implementasi')->default('Belum'),
            ])->columns(3),
            Section::make('Dokumen')->schema([
                TextEntry::make('dokumen_ks_display')->label('Dokumen')->html(),
                TextEntry::make('dokumen_pendukung')->label('Pendukung')->default('-'),
            ]),
            Section::make('Log Review')->schema([
                TextEntry::make('review_log_display')->label('Riwayat')->html(),
            ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        $wf = new WorkflowService;
        $ks = $this->getRecord();
        $actions = [];

        if ($ks->status_pengajuan === 'DIAJUKAN') {
            $actions[] = Actions\Action::make('setujui')->label('Setujui')->color('success')->requiresConfirmation()
                ->action(function () use ($ks, $wf) {
                    $ks->addReviewEntry('DISETUJUI', 'Disetujui');
                    $wf->transition($ks, 'DISETUJUI');
                    Notification::make()->title('Disetujui')->success()->send();
                    $this->redirect($this->getResource()::getUrl('review', ['record' => $ks->kerjasama_id]));
                });
            $actions[] = Actions\Action::make('tolak')->label('Tolak')->color('danger')
                ->form([Textarea::make('alasan')->required()->label('Alasan'), Textarea::make('catatan')->label('Catatan Perbaikan')])
                ->action(function ($data) use ($ks, $wf) {
                    $ks->addReviewEntry('DITOLAK', $data['alasan'], $data['catatan'] ?? null);
                    $wf->transition($ks, 'DITOLAK');
                    Notification::make()->title('Ditolak')->warning()->send();
                    $this->redirect($this->getResource()::getUrl('review', ['record' => $ks->kerjasama_id]));
                });
        }
        if ($ks->status_pengajuan === 'DISETUJUI') {
            $actions[] = Actions\Action::make('jadwalkan')->label('Jadwalkan')->color('warning')
                ->form([DatePicker::make('tanggal_pembahasan_ks')->required()->label('Tanggal'), TimePicker::make('jam_pembahasan')->required()->label('Jam'),
                    TextInput::make('lokasi_pembahasan')->required()->label('Lokasi'), TextInput::make('link_meeting')->label('Link'), TextInput::make('pic_pembahasan')->label('PIC')])
                ->action(function ($data) use ($ks, $wf) {
                    $ks->update($data); $wf->transition($ks, 'MENUNGGU_PEMBAHASAN');
                    $ks->addReviewEntry('JADWAL', "Pembahasan {$data['tanggal_pembahasan_ks']} di {$data['lokasi_pembahasan']}");
                    Notification::make()->title('Jadwal disimpan')->success()->send();
                    $this->redirect($this->getResource()::getUrl('review', ['record' => $ks->kerjasama_id]));
                });
        }
        if ($ks->status_pengajuan === 'MENUNGGU_PEMBAHASAN') {
            $actions[] = Actions\Action::make('selesai-bahas')->label('Selesai Pembahasan')->color('success')
                ->form([TextInput::make('nomor_pihak1')->label('No Pihak 1'), TextInput::make('nomor_pihak2')->label('No Pihak 2')])
                ->action(function ($data) use ($ks, $wf) {
                    $ks->update($data); $wf->transition($ks, 'SELESAI_PEMBAHASAN');
                    Notification::make()->title('Selesai')->success()->send();
                    $this->redirect($this->getResource()::getUrl('review', ['record' => $ks->kerjasama_id]));
                });
        }
        if ($ks->status_pengajuan === 'SELESAI_PEMBAHASAN') {
            $actions[] = Actions\Action::make('upload-final')->label('Upload Final (TTD)')->color('success')
                ->modalHeading('Upload Dokumen Final yang Sudah Ditandatangani')
                ->modalDescription('Upload dokumen NK yang sudah ditandatangani kedua pihak.')
                ->form([FileUpload::make('dokumen_final')->required()->label('Dokumen Final')->acceptedFileTypes(['application/pdf','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/zip'])->maxSize(20480)->disk('public')->directory('dokumen/'.$ks->kerjasama_id)])
                ->action(function ($data) use ($ks, $wf) {
                    if ($data['dokumen_final']) {
                        $e = $ks->dokumen_ks ? json_decode($ks->dokumen_ks, true) ?: [] : [];
                        $e[] = $data['dokumen_final'];
                        $ks->update(['dokumen_ks' => json_encode($e)]);
                    }
                    $ks->addReviewEntry('TTD_COMPLETE', 'Dokumen final ditandatangani dan diupload');
                    $wf->transition($ks, 'SELESAI');
                    Notification::make()->title('Dokumen final terupload. Selesai.')->success()->send();
                    $this->redirect($this->getResource()::getUrl('review', ['record' => $ks->kerjasama_id]));
                });
        }
        if ($ks->status_pengajuan === 'SELESAI') {
            $hasData = $ks->ks_metode || $ks->jangka_waktu_thn;
            $actions[] = Actions\Action::make('finalisasi')->label($hasData ? 'Update Finalisasi' : 'Finalisasi')->color('gray')
                ->form([
                    TextInput::make('jangka_waktu_thn')->label('Jangka (thn)')->numeric()->minValue(1),
                    DatePicker::make('tanggal_mulai_ks')->label('Tgl Mulai'),
                    DatePicker::make('tanggal_selesai_ks')->label('Tgl Berakhir'),
                    Select::make('ks_metode')->label('Metode')->options(KsMetode::pluck('nama_metode','id')->toArray()),
                    Select::make('ks_implementasi')->label('Implementasi')->options(KsImplementasi::pluck('nama_status','id')->toArray()),
                    Textarea::make('pusdatin_kirim_data')->label('Data Dikirim'),
                    Textarea::make('pusdatin_terima_data')->label('Data Diterima'),
                ])
                ->action(function ($data) use ($ks) {
                    $ks->update($data);
                    Notification::make()->title('Data final disimpan')->success()->send();
                });
        }
        return $actions;
    }
}
