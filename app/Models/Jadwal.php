<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Jadwal extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jadwal';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pasien_id',
        'jenis',
        'tanggal_waktu_pengingat',
        'status',
        'status_konfirmasi',
        'token_konfirmasi',
        'tgl_waktu_konfirmasi',
        'fase',
        'periode',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_waktu_pengingat' => 'datetime',
        'tgl_waktu_konfirmasi' => 'datetime',
    ];
    
    /**
     * Get the patient that owns the schedule.
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }
    
    /**
     * Get the histories for this schedule.
     */
    public function riwayat(): HasMany
    {
        return $this->hasMany(Riwayat::class, 'jadwal_id');
    }
    
    /**
     * Scope a query to only include schedules that are pending.
     */
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }
    
    /**
     * Scope a query to only include schedules that are due to be sent.
     */
    public function scopeJatuhTempo($query)
    {
        return $query->where('status', 'menunggu')
            ->where('tanggal_waktu_pengingat', '<=', now());
    }
    
    /**
     * Scope a query to only include medicine reminder schedules.
     */
    public function scopeObat($query)
    {
        return $query->where('jenis', 'obat');
    }
    
    /**
     * Scope a query to only include control schedules.
     */
    public function scopeKontrol($query)
    {
        return $query->where('jenis', 'kontrol');
    }
    
    /**
     * Check if schedule is for daily medicine reminder.
     */
    public function isObatHarian()
    {
        return $this->jenis === 'obat';
    }
    
    /**
     * Check if schedule is for control reminder.
     */
    public function isKontrol()
    {
        return $this->jenis === 'kontrol';
    }
    
    /**
     * Check if confirmation is still valid (same day).
     */
    public function isKonfirmasiValid()
    {
        if ($this->jenis !== 'obat') {
            return false;
        }
        
        $today = now()->toDateString();
        $jadwalDate = $this->tanggal_waktu_pengingat->toDateString();
        
        return $today === $jadwalDate;
    }
    
    /**
     * Generate confirmation token for medicine reminder.
     */
    public function generateTokenKonfirmasi()
    {
        if ($this->jenis === 'obat') {
            $this->token_konfirmasi = Str::random(32);
            $this->save();
        }
    }
}
