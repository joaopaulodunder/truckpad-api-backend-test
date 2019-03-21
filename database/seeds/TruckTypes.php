<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TruckTypes extends Seeder
{
    private $types = [
        'Caminhão 3/4',
        'Caminhão Toco',
        'Caminhão Truck',
        'Carreta Simples',
        'Carreta Eixo Extendido'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->types as $type){
            \Illuminate\Support\Facades\DB::table('tb_truck_types')->insert([
                'description' => $type,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
