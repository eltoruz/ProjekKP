<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Kerjasama extends Model
{
    protected $table = 'kerjasama';
    protected $primaryKey = 'kerjasama_id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kerjasama_id', 'no_input', 'ks_jenis', 'ks_tingkat', 'kode_wilayah', 'nama_kl',
        'jumlah_kl_terlibat', 'pihak1', 'pihak2', 'tentang', 'jangka_waktu_thn',
        'tanggal_mulai_ks', 'tanggal_selesai_ks',
        'nomor_pihak1', 'nomor_pihak2', 'ttd_pihak1', 'ttd_pihak2',
        'ks_status_dok', 'narahubung_adm', 'nomor_cp_adm',
        'ks_metode', 'ks_implementasi', 'narahubung_teknis', 'nomor_cp_teknis',
        'dokumen_ks', 'dokumen_pendukung', 'folder_ks', 'unit_utama_terlibat',
        'pusdatin_kirim_data', 'pusdatin_terima_data', 'soft_delete',
        'create_date', 'last_update',
        'tanggal_pembahasan',
    ];

    protected $casts = [
        'tanggal_mulai_ks' => 'date',
        'tanggal_selesai_ks' => 'date',
        'tanggal_pembahasan' => 'datetime',
        'soft_delete' => 'boolean',
        'create_date' => 'datetime',
        'last_update' => 'datetime',
    ];

    protected $appends = ['dokumen_ks_display', 'folder_ks_display'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            $model->create_date ??= now();
            $model->last_update ??= now();
        });
        static::updating(function ($model) {
            $model->last_update = now();
        });
    }

    public function jenis() { return $this->belongsTo(KsJenis::class, 'ks_jenis'); }
    public function tingkat() { return $this->belongsTo(KsTingkat::class, 'ks_tingkat'); }
    public function statusDok() { return $this->belongsTo(KsStatusDok::class, 'ks_status_dok'); }
    public function metode() { return $this->belongsTo(KsMetode::class, 'ks_metode'); }
    public function implementasi() { return $this->belongsTo(KsImplementasi::class, 'ks_implementasi'); }
    public function reviewLogs() { return $this->hasMany(ReviewLog::class, 'kerjasama_id', 'kerjasama_id')->orderBy('id'); }

    public function scopeNotDeleted($query) { return $query->where('soft_delete', false); }
    public function scopeByStatus($query, int $status) { return $query->where('ks_status_dok', $status); }

    public function getReviewLogAttribute(): array
    {
        return $this->reviewLogs->map(fn($log) => [
            'label' => $log->label,
            'catatan' => $log->catatan,
            'waktu' => $log->created_at->toDateTimeString(),
        ])->toArray();
    }

    public function addReviewEntry(string $label, ?string $catatan = null): void
    {
        $this->reviewLogs()->create([
            'label' => $label,
            'catatan' => $catatan,
        ]);
    }

    public function getSisaMasaBerlakuHariAttribute()
    {
        if (!$this->tanggal_selesai_ks) return null;
        return now()->diffInDays($this->tanggal_selesai_ks, false);
    }

    public function getIsExpiredAttribute()
    {
        return $this->tanggal_selesai_ks && $this->tanggal_selesai_ks->isPast();
    }

    public function getStatusLabelAttribute()
    {
        if (!$this->ks_status_dok) {
            $lastReject = collect($this->review_log)->filter(fn($l) => ($l['label'] ?? '') === 'Ditolak')->last();
            if ($lastReject) return 'Ditolak';
            return 'Draft';
        }
        return $this->statusDok?->nama_status ?? '-';
    }

    public function getDokumenKsDisplayAttribute(): string
    {
        if (!$this->dokumen_ks) return '-';
        $url = Storage::disk('public')->exists($this->dokumen_ks)
            ? Storage::disk('public')->url($this->dokumen_ks)
            : $this->dokumen_ks;
        return "<a href='{$url}' target='_blank' style='color:#2563eb;text-decoration:underline;font-size:0.875rem'>Dokumen Final (TTD)</a>";
    }

    public function getFolderKsDisplayAttribute(): array
    {
        if (!$this->folder_ks) return [];
        $files = json_decode($this->folder_ks, true);
        if (!is_array($files)) return [];
        $labels = ['Surat Permohonan', 'Draft Nota Kesepakatan', 'Surat Undangan'];
        return collect($files)->map(function ($f, $i) use ($labels) {
            $url = Storage::disk('public')->exists($f)
                ? Storage::disk('public')->url($f)
                : $f;
            return ['label' => $labels[$i] ?? 'Dokumen ' . ($i + 1), 'url' => $url];
        })->toArray();
    }

    public function hasSuratUndangan(): bool
    {
        if (!$this->folder_ks) return false;
        $files = json_decode($this->folder_ks, true);
        return is_array($files) && count($files) >= 3;
    }

    public function getStatusColorAttribute()
    {
        return match((int)$this->ks_status_dok) {
            1 => 'gray',
            2 => 'yellow',
            3 => 'orange',
            4 => 'purple',
            5 => 'green',
            6 => 'gray',
            default => 'gray',
        };
    }

    public function canBeDeletedByMitra(): bool
    {
        return $this->ks_status_dok === null && $this->reviewLogs()->count() === 0;
    }
}
