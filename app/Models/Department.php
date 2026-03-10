<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function stocks(): HasMany
    {
        return $this->hasMany(DepartmentStock::class);
    }

    public function transferRequestsFrom(): HasMany
    {
        return $this->hasMany(TransferRequest::class, 'from_department_id');
    }

    public function transferRequestsTo(): HasMany
    {
        return $this->hasMany(TransferRequest::class, 'to_department_id');
    }
}
