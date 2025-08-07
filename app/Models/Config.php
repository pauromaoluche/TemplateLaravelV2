<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends BaseModel
{
    use HasFactory;

    protected $fillable = ['title', 'value', 'code', 'active'];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
