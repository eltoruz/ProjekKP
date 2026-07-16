<?php

namespace App\Models;

use App\Services\WorkflowService;
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
        'kerjasama_id', 'no_input', 'ks_jenis', 'ks_tingkat', 'kode_wilayah', 'provinsi', 'nama_kl',
        'jumlah_kl_terlibat', 'pihak1', 'pihak2', 'tentang', 'jangka_waktu_thn',
        'tanggal_mulai_ks', 'tanggal_selesai_ks', 'sisa_masa_berlaku', 'tahun_mulai',
        'nomor_pihak1', 'nomor_pihak2', 'ttd_pihak1', 'ttd_pihak2',
        'ks_status_dok', 'narahubung_adm', 'nomor_cp_adm',
        'ks_metode', 'ks_implementasi', 'narahubung_teknis', 'nomor_cp_teknis',
        'dokumen_ks', 'dokumen_pendukung', 'folder_ks', 'unit_utama_terlibat',
        'pusdatin_kirim_data', 'pusdatin_terima_data', 'expired_date', 'soft_delete',
        'create_date', 'last_update',
        'status_pengajuan', 'tanggal_pembahasan_ks', 'jam_pembahasan',
        'lokasi_pembahasan', 'link_meeting', 'pic_pembahasan', 'tanggal_ttd', 'review_log',
    ];

    protected $casts = [
        'tanggal_mulai_ks' => 'date',
        'tanggal_selesai_ks' => 'date',
        'tanggal_pembahasan_ks' => 'date',
        'tanggal_ttd' => 'date',
        'expired_date' => 'date',
        'soft_delete' => 'boolean',
        'create_date' => 'datetime',
        'last_update' => 'datetime',
    ];

    protected $appends = ['dokumen_ks_display', 'review_log_display'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            if ($model->tanggal_mulai_ks && !$model->tahun_mulai) {
                $model->tahun_mulai = Carbon::parse($model->tanggal_mulai_ks)->year;
            }
            $model->create_date ??= now();
            $model->last_update ??= now();
        });
        static::updating(function ($model) {
            if ($model->tanggal_mulai_ks && !$model->tahun_mulai) {
                $model->tahun_mulai = Carbon::parse($model->tanggal_mulai_ks)->year;
            }
            $model->last_update = now();
        });
    }

    public function jenis() { return $this->belongsTo(KsJenis::class, 'ks_jenis'); }
    public function tingkat() { return $this->belongsTo(KsTingkat::class, 'ks_tingkat'); }
    public function statusDok() { return $this->belongsTo(KsStatusDok::class, 'ks_status_dok'); }
    public function metode() { return $this->belongsTo(KsMetode::class, 'ks_metode'); }
    public function implementasi() { return $this->belongsTo(KsImplementasi::class, 'ks_implementasi'); }

    public function scopeNotDeleted($query) { return $query->where('soft_delete', false); }
    public function scopeByStatus($query, string $status) { return $query->where('status_pengajuan', $status); }

    public function getReviewLogAttribute($value): array
    {
        return json_decode($value ?? '[]', true) ?: [];
    }

    public function setReviewLogAttribute($value): void
    {
        $this->attributes['review_log'] = json_encode($value);
    }

    public function addReviewEntry(string $status, ?string $alasan = null, ?string $catatan = null): void
    {
        $logs = $this->review_log;
        $logs[] = [
            'status' => $status,
            'alasan' => $alasan,
            'catatan' => $catatan,
            'waktu' => now()->toDateTimeString(),
        ];
        $this->review_log = $logs;
        $this->save();
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
        return WorkflowService::STATUS[$this->status_pengajuan] ?? $this->status_pengajuan;
    }

    public function getDokumenKsDisplayAttribute(): string
    {
        if (!$this->dokumen_ks) return '-';
        $files = json_decode($this->dokumen_ks, true);
        if (!is_array($files)) return $this->dokumen_ks;
        $labels = ['Surat Permohonan', 'Draft Nota Kesepakatan', 'Dokumen Final', 'Dokumen Revisi'];
        return collect($files)->map(function ($f, $i) use ($labels) {
            $name = $labels[$i] ?? 'Dokumen '.($i+1);
            $url = Storage::disk('public')->exists($f)
                ? Storage::disk('public')->url($f)
                : $f;
            return "<a href='{$url}' target='_blank' class='text-primary-600 underline'>{$name}</a>";
        })->implode(' | ');
    }

    public function getReviewLogDisplayAttribute(): string
    {
        $logs = $this->review_log;
        if (empty($logs)) return '<span class="text-gray-400">-</span>';
        return collect($logs)->map(function ($l) {
            $status = $l['status'] ?? '';
            $alasan = $l['alasan'] ?? '';
            $waktu = $l['waktu'] ?? '';
            $badgeColor = match($status) {
                'DITOLAK' => '#ef4444',
                'DISETUJUI' => '#22c55e',
                'JADWAL', 'FINAL_UPLOAD' => '#3b82f6',
                'TTD_COMPLETE' => '#a855f7',
                default => '#9ca3af',
            };
            $label = \App\Services\WorkflowService::STATUS[$status] ?? $status;
            $time = \Carbon\Carbon::parse($waktu)->format('d M Y, H:i');
            return "<div style='display:flex;align-items:flex-start;gap:8px;padding:4px 0'>
                <span style='display:inline-block;width:8px;height:8px;border-radius:50%;background:{$badgeColor};margin-top:5px;flex-shrink:0'></span>
                <div>
                    <strong>{$label}</strong>
                    <span style='color:#9ca3af;font-size:0.85em;margin-left:4px'>{$time}</span>
                    <br><span style='color:#6b7280'>{$alasan}</span>
                </div>
            </div>";
        })->implode('');
    }

    public function getStatusColorAttribute()
    {
        return match($this->status_pengajuan) {
            'DRAFT', 'UPLOAD_DOKUMEN' => 'gray',
            'DIAJUKAN', 'REVIEW_ADMIN' => 'blue',
            'DITOLAK' => 'red',
            'DISETUJUI', 'MENUNGGU_PEMBAHASAN', 'SELESAI_PEMBAHASAN' => 'yellow',
            'PROSES_TTD' => 'purple',
            'SELESAI' => 'green',
            'EXPIRED' => 'gray',
            default => 'gray',
        };
    }
}
