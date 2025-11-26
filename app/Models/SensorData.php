<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class SensorData extends Model
{
    protected $table = 'sensor_data';
    
    // No usem timestamps automàtics de Laravel (created_at, updated_at)
    public $timestamps = false;
    
    // Camps que es poden omplir massivament
    protected $fillable = [
        'topic',
        'sensor_type',
        'temperatura',
        'humitat',
        'pressio',
        'location',
        'timestamp'
    ];

    // Conversió de tipus
    protected $casts = [
        'temperatura' => 'decimal:2',
        'humitat' => 'decimal:2',
        'pressio' => 'decimal:2',
        'timestamp' => 'datetime'
    ];
}