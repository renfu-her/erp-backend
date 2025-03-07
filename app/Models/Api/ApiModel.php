<?php

namespace App\Models\Api;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

abstract class ApiModel implements ArrayAccess, Arrayable, JsonSerializable
{
    protected array $attributes = [];
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }
    
    public function __get(string $key)
    {
        return $this->attributes[$key] ?? null;
    }
    
    public function __set(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }
    
    public function offsetExists($offset): bool
    {
        return isset($this->attributes[$offset]);
    }
    
    public function offsetGet($offset): mixed
    {
        return $this->attributes[$offset];
    }
    
    public function offsetSet($offset, $value): void
    {
        $this->attributes[$offset] = $value;
    }
    
    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }
    
    public function toArray(): array
    {
        return $this->attributes;
    }
    
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
} 