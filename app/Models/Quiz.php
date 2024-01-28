<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'questions'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('answered_questions');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
