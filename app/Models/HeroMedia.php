<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroMedia extends Model
{
    protected $fillable = ['type', 'file_path', 'sorting_order', 'is_active'];
}
