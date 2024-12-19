<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineBarcode extends Model
{
    use HasFactory;

    protected $table = 'machine_barcodes';

    protected $fillable = ['machine_detail_id', 'barcode'];

    public function machineDetail()
    {
        return $this->belongsTo(MachineDetail::class);
    }
}
