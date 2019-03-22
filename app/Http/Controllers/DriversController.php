<?php

namespace App\Http\Controllers;

use App\Drivers;
use Illuminate\Http\Request;

class DriversController extends Controller
{
    public function createDriver(Request $request)
    {
        try {
            $response = (Drivers::createDrive($request)) ? '{"status":"OK", "message":"Dados gravado com sucesso!"}' : '{"status":"OK", "message":"Dados já existe no banco de dados!"}';
            return response()->json(json_decode($response))->setStatusCode(200);
        } catch (\Exception $e) {
            return response()->json(sprintf('{"status":"ERROR", "message":"%s"}', $e->getMessage()))->setStatusCode(400);
        }
    }

    public function searchDriversOwnervehicle(Request $request)
    {
        $owner = ($request->possuiVeiculo == 'true') ? 'YES' : 'NO';

        try {
            $queryData = Drivers::where('owner_vehicle', '=', $owner);
            return response()->json(
                [
                    'total' => $queryData->get()->count(),
                    'drivers' => $queryData->get()
                ])
                ->setStatusCode(200);
        }catch (\Exception $e){
            return response()->json(sprintf('{"status":"ERROR", "message":"%s"}', $e->getMessage()))->setStatusCode(400);
        }
    }

    public function searchDriversByCpf($cpf)
    {
        try{
            return response()->json(Drivers::getDriverByCpf($cpf));
        }catch (\Exception $e){
            return response()->json(sprintf('{"status":"ERROR", "message":"%s"}', $e->getMessage()))->setStatusCode(400);
        }
    }

    public function updateDriver(Request $request, $id)
    {
        try {
            $response = (Drivers::updateDriverById($request, $id)) ? '{"status":"OK", "message":"Registro atulizado com sucesso!"}' : '{"status":"OK", "message":"Não foi possivel atualizar o registro!"}';
            return response()->json(json_decode($response))->setStatusCode(200);
        }catch (\Exception $e){
            return response()->json(sprintf('{"status":"ERROR", "message":"%s"}', $e->getMessage()))->setStatusCode(400);
        }
    }
}
