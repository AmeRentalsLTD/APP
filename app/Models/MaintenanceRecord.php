<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRecord extends Model
{
    use HasFactory;

    public const TYPES = [
        'service',
        'inspection',
        'repair',
        'mot',
        'road_tax',
        'valeting',
        'other',
    ];

    public const STATUSES = [
        'scheduled',
        'in_progress',
        'completed',
        'cancelled',
    ];

    protected $fillable = [
        'vehicle_id',
        'title',
        'type',
        'status',
        'odometer',
        'scheduled_at',
        'completed_at',
        'cost',
        'vendor',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'date',
        'completed_at' => 'date',
        'cost' => 'decimal:2',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
