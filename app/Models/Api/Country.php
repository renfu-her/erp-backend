<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'id',
        'name',
        'e_name',
        'code',
        'tel_area',
        'continent_id',
    ];

    public $timestamps = false;

    public static function fromApiResponse(array $data, int $continentId): self
    {
        return new self([
            'id' => $data['id'],
            'name' => $data['name'],
            'e_name' => $data['e_name'],
            'code' => (object)$data['code'],
            'tel_area' => $data['tel_area'],
            'continent_id' => $continentId,
        ]);
    }
    
    public function getKeyName(): string
    {
        return 'id';
    }

    public function exists(): bool
    {
        return isset($this->attributes['id']);
    }
} 