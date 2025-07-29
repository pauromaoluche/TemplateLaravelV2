<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institutional extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'value',
        'code',
        'active'
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
