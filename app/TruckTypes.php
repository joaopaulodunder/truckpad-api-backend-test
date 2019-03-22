<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TruckTypes extends Model
{
    protected $table = 'tb_truck_types';

    public static function getIdTruckTypeByDescription($description)
    {
        return self::where('description', '=', $description)->get('id')->toArray()[0]['id'];
    }
}
