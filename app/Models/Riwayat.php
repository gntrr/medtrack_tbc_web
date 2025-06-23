<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Riwayat extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'riwayat';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jadwal_id',
        'waktu_pengiriman',
        'status',
        'pesan',
        'respons',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'waktu_pengiriman' => 'datetime',
    ];
    
    /**
     * Get the schedule that owns the history.
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }
    
    /**
     * Get the patient through the schedule.
     */
    public function pasien()
    {
        return $this->jadwal->pasien;
    }
}
