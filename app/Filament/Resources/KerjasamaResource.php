<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KerjasamaResource\Pages;
use App\Models\Kerjasama;
use App\Models\KsStatusDok;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KerjasamaResource extends Resource
{
    protected static ?string $model = Kerjasama::class;
    protected static ?string $navigationGroup = 'Data Kerja Sama';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Kerja Sama';
    protected static ?string $modelLabel = 'Kerja Sama';
    protected static ?string $pluralModelLabel = 'Kerja Sama';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('ks_jenis')->label('Jenis')->relationship('jenis', 'nama_jenis')->required(),
            Select::make('ks_tingkat')->label('Tingkat')->relationship('tingkat', 'nama_tingkat')->required(),
            TextInput::make('kode_wilayah')->maxLength(20),
            TextInput::make('nama_kl')->label('Instansi')->maxLength(200),
            TextInput::make('jumlah_kl_terlibat')->label('Jumlah K/L')->numeric()->default(1),
            TextInput::make('pihak1')->label('Pihak 1')->maxLength(200),
            TextInput::make('pihak2')->label('Pihak 2')->maxLength(200),
            Textarea::make('tentang'),
            TextInput::make('jangka_waktu_thn')->label('Jangka (thn)')->numeric(),
            DatePicker::make('tanggal_mulai_ks')->label('Tgl Mulai'),
            DatePicker::make('tanggal_selesai_ks')->label('Tgl Berakhir'),
            TextInput::make('narahubung_adm')->maxLength(200),
            TextInput::make('nomor_cp_adm')->maxLength(50),
            TextInput::make('narahubung_teknis')->maxLength(200),
            TextInput::make('nomor_cp_teknis')->maxLength(50),
            TextInput::make('unit_utama_terlibat'),
            Select::make('ks_status_dok')->label('Status Dok')->relationship('statusDok', 'nama_status'),
            Select::make('ks_metode')->label('Metode')->relationship('metode', 'nama_metode'),
            Select::make('ks_implementasi')->label('Implementasi')->relationship('implementasi', 'nama_status'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->where('soft_delete', false)->whereNotNull('ks_status_dok')->orderBy('last_update', 'desc'))
            ->columns([
                TextColumn::make('nama_kl')->label('Mitra')->searchable()->sortable(),
                TextColumn::make('jenis.nama_jenis')->label('Jenis')->sortable(),
                TextColumn::make('tingkat.nama_tingkat')->label('Tingkat'),
                TextColumn::make('statusDok.nama_status')->label('Status')->badge()->color(fn ($state) => match($state) {
                    'Mengirimkan surat permohonan' => 'info',
                    'Pemohon menyampaikan undangan pembahasan NK/PKS/NDA' => 'warning',
                    'Dokumen dalam proses pembahasan' => 'warning',
                    'Dokumen dalam proses penandatanganan' => 'primary',
                    'Dokumen telah ditandatangani dan diterima oleh masing-masing Pihak' => 'success',
                    'masa berlaku selesai' => 'gray',
                    default => 'gray',
                }),
                TextColumn::make('tanggal_pembahasan')->label('Jadwal Pembahasan')->dateTime()->sortable(),
                TextColumn::make('last_update')->label('Update')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('ks_status_dok')
                    ->label('Status')
                    ->options(KsStatusDok::pluck('nama_status', 'id')->toArray()),
                SelectFilter::make('ks_jenis')->relationship('jenis', 'nama_jenis'),
            ])
            ->actions([
                Tables\Actions\Action::make('kelola')->label('Kelola')->color('warning')
                    ->icon('heroicon-m-cog-6-tooth')
                    ->url(function (Kerjasama $r) { return KerjasamaResource::getUrl('review', ['record' => $r->kerjasama_id]); }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('hapus')
                    ->label('Hapus')->color('danger')
                    ->icon('heroicon-m-trash')
                    ->requiresConfirmation()
                    ->action(function (Kerjasama $record) {
                        $record->update(['soft_delete' => true]);
                    }),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\BulkAction::make('hapus_bulk')
                    ->label('Hapus')->color('danger')
                    ->icon('heroicon-m-trash')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        Kerjasama::whereIn('kerjasama_id', $records->pluck('kerjasama_id'))
                            ->update(['soft_delete' => true]);
                    }),
            ])]);
    }

    public static function canCreate(): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKerjasamas::route('/'),
            'edit' => Pages\EditKerjasama::route('/{record}/edit'),
            'review' => Pages\ReviewKerjasama::route('/{record}/review'),
        ];
    }
}
