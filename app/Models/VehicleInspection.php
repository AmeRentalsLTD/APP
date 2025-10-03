<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleInspection extends Model
{
    use HasFactory;

    public const TYPES = [
        'onhire',
        'weekly',
    ];

    protected $fillable = [
        'vehicle_id',
        'type',
        'inspected_at',
        'notes',
        'front_image_path',
        'rear_image_path',
        'left_image_path',
        'right_image_path',
        'tyres_image_path',
        'windscreen_image_path',
        'mirrors_image_path',
    ];

    protected $casts = [
        'inspected_at' => 'date',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
