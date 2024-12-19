<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineDetail extends Model
{
    use HasFactory;

    protected $table = 'machine_details';

    protected $fillable = ['machine_id']; //, 'created_at', 'updated_at'

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function machineBarcodes()
    {
        return $this->hasMany(MachineBarcode::class);
    }

    public function machineImages()
    {
        return $this->hasMany(MachineImage::class);
    }
}
