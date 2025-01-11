<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'cover', 'category', 'description'];

    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
