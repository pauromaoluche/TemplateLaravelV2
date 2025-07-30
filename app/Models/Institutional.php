<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

class Institutional extends BaseModel
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
