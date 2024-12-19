<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineImage extends Model
{
    use HasFactory;

    protected $table = 'machine_images';

    protected $fillable = ['machine_detail_id', 'file'];

    public function machineDetail()
    {
        return $this->belongsTo(MachineDetail::class);
    }
}
