<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['department_id', 'name', 'is_active'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
