<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{
    use HasFactory;


    protected $table = "cms";

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'page','url','title','content','meta_keywords','meta_description','is_enabled','created_by','updated_by','created_at','updated_at'
    ];



    function getPages()
    {
        return CMS::where(['is_enabled' => 1])->get(['url','page']);
    }

    function getCmsDetail($url='')
    {
        return CMS::where(['url'=>$url,'is_enabled' => 1])->first();
    }
}
