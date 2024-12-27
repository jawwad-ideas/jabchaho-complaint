<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dryer extends Model
{
    use HasFactory;

    protected $table = 'dryer';

    protected $fillable = ['status', 'before_barcodes','after_barcodes'];
}
