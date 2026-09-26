<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model {
    use HasFactory;

    protected $fillable = [ 'title','banner','highlights','details','notes','tips','professionals','needs' ];

    protected $casts = [
        'details' => 'array',
        'notes' => 'array',
        'tips' => 'array',
        'professionals' => 'array',
        'needs' => 'array',
    ];
}
