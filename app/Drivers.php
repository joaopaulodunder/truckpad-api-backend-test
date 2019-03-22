<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Drivers extends Model
{
    protected $table = 'tb_drivers';


    public static function createDrive(Request $request)
    {
        $driverModel = self::where('cpf', '=', $request->cpf)->get()->count();

        if ($driverModel == 0) {
            $driverModel = new self();

            $driverModel->name = $request->nome;
            $driverModel->age = $request->idade;
            $driverModel->birth = $request->nascimento;
            $driverModel->cpf = $request->cpf;
            $driverModel->sex = $request->sexo;
            $driverModel->owner_vehicle = $request->dono;
            $driverModel->cnh_type = $request->tpCnh;

            return true;
        }
        return false;
    }

    public static function getIdDriverByCpf($cpf)
    {
        return self::where('cpf', '=', $cpf)->get('id')->toArray()[0]['id'];
    }

    public static function updateDriverById(Request $request, $id)
    {
        $driverModel = self::findOrFail($id);

        $driverModel->name = $request->nome;
        $driverModel->age = $request->idade;
        $driverModel->birth = $request->nascimento;
        $driverModel->cpf = $request->cpf;
        $driverModel->sex = $request->sexo;
        $driverModel->owner_vehicle = $request->dono;
        $driverModel->cnh_type = $request->tpCnh;

        return $driverModel->save();
    }

}
