<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'image'];

    public function masterClasses()
    {
        return $this->hasMany(MasterClass::class);
    }
}