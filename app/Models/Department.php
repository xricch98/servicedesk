<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'email',
        'is_active',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
