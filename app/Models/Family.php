<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Family extends Model
{
    use HasFactory;
    protected $table = 'family';
    protected $guarded = [];

    public function entitas() : HasMany {
        return $this->hasMany(Entitas::class);
    }
}
