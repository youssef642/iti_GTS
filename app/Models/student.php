<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class student extends Model
{
   
    public function skills() {
        return $this->hasMany(Skill::class);
    }
}
