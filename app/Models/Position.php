<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'base_salary',
        'benefits',
        'requirements',
        'is_active',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'benefits' => 'array',
        'requirements' => 'array',
        'is_active' => 'boolean',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
} 