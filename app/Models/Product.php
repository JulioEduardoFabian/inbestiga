<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'amount', 'term'];

    public function activities(){
        return $this->belongsToMany("App\Models\Activity");
    }
}
