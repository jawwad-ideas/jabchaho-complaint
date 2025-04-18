<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function getServices($fields =array())
    {
        $query = Service::where('status', 1);

        if (!empty($fields)) {
            $query->select($fields);
        }

        return $query->get();
    }

}
