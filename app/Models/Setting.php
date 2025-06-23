<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'is_encrypted'
    ];

    protected $casts = [
        'is_encrypted' => 'boolean'
    ];

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        $cacheKey = 'setting_' . $key;
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            $value = $setting->value;

            // Decrypt if encrypted
            if ($setting->is_encrypted && $value) {
                try {
                    $value = Crypt::decrypt($value);
                } catch (\Exception $e) {
                    // If decryption fails, return the raw value (for migration compatibility)
                    // Log the issue for debugging
                    \Log::warning("Failed to decrypt setting '{$key}': " . $e->getMessage());
                    // Return the raw value as fallback
                    $value = $setting->value;
                }
            }

            // Cast to appropriate type
            return self::castValue($value, $setting->type);
        });
    }

    /**
     * Set setting value
     */
    public static function set($key, $value, $type = 'string', $group = 'general', $label = null, $description = null, $isEncrypted = false)
    {
        $originalValue = $value;
        
        // Encrypt if needed and value is not empty
        if ($isEncrypted && !empty($value)) {
            $value = Crypt::encrypt($value);
        }

        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'label' => $label,
                'description' => $description,
                'is_encrypted' => $isEncrypted
            ]
        );

        // Clear cache
        Cache::forget('setting_' . $key);

        return $setting;
    }

    /**
     * Cast value to appropriate type
     */
    private static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Get settings by group
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)->get()->mapWithKeys(function ($setting) {
            return [$setting->key => self::get($setting->key)];
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        $settings = self::all();
        foreach ($settings as $setting) {
            Cache::forget('setting_' . $setting->key);
        }
    }
}
