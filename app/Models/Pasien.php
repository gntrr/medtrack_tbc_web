<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pasien';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'alamat',
        'nomor_telepon',
        'jadwal_pengobatan',
    ];
    
    /**
     * The attributes that should be encrypted.
     *
     * @var array<int, string>
     */
    protected $encryptable = [
        'nomor_telepon',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jadwal_pengobatan' => 'date',
    ];
    
    /**
     * Get the schedules for this patient.
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'pasien_id');
    }
    
    /**
     * Encrypt the nomor_telepon attribute when setting.
     */
    public function setNomorTeleponAttribute($value)
    {
        $this->attributes['nomor_telepon'] = encrypt($value);
    }
    
    /**
     * Decrypt the nomor_telepon attribute when getting.
     */
    public function getNomorTeleponAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }
}
