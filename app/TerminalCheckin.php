<?php

namespace App;

use App\Http\Resources\ExternalApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class TerminalCheckin extends Model
{
    protected $table = 'tb_terminal_checkins';

    public function driver(){
        return $this->belongsTo("Drivers");
    }
    public function truckTypes(){
        return $this->belongsTo('TruckTypes');
    }

    public static function createCheckin(Request $request)
    {
           $checkinOrigemDestino = array(
                'EnderecoOrigem' => [
                    'street' => $request->origemLogradouro,
                    'number' => $request->origemNumero,
                    'neighborhood' => $request->origemBairro,
                    'state' => $request->origemEstado,
                    'city' => $request->origemCidade,
                    'cep' => $request->origemCep,
                ],
                'EnderecoDestino' => [
                    'street' => $request->destinoLogradouro,
                    'number' => $request->destinoNumero,
                    'neighborhood' => $request->destinoBairro,
                    'state' => $request->destinoEstado,
                    'city' => $request->destinoCidade,
                    'cep' => $request->destinoCep,
                ],
            );

            // Cria os endereços no banco de dados caso não exista, se não ocorrer nenhum erro neste processo insere os dados do checkin
           if (Address::createAddress($checkinOrigemDestino)){
               $terminalCheckinModel = new self();

               $terminalCheckinModel->driver_id = Drivers::getIdDriverByCpf($request->motoristaCpf); // identificar automatico pelo cpf
               $terminalCheckinModel->truck_type_id = TruckTypes::getIdTruckTypeByDescription($request->tipoCaminhao); // identificar pelo nome

               $terminalCheckinModel->source_address = Address::getIdAddressByCepAndLogradouroNumber(
                   Address::getCepByAddressDesription($checkinOrigemDestino['EnderecoOrigem']),
                   $checkinOrigemDestino['EnderecoOrigem']['number']
               );
               $terminalCheckinModel->destiny_address = Address::getIdAddressByCepAndLogradouroNumber(
                   Address::getCepByAddressDesription($checkinOrigemDestino['EnderecoDestino']),
                   $checkinOrigemDestino['EnderecoDestino']['number']);

               $terminalCheckinModel->loaded = $request->carregado;
               $terminalCheckinModel->save();
           }

    }

    public static function getCheckinByPeriodAndLoaded($dtStart, $dtEnd, $loaded = 'NO')
    {
         $queryData = self::where('loaded', '=', $loaded)
            ->whereBetween('tb_terminal_checkins.created_at', [$dtStart, $dtEnd])
            ->join('tb_drivers', 'tb_drivers.id', '=', 'tb_terminal_checkins.driver_id')
            ->join('tb_truck_types', 'tb_truck_types.id', '=', 'tb_terminal_checkins.truck_type_id')
            ->join('tb_address as tb_address_source', 'tb_address_source.id', '=', 'tb_terminal_checkins.source_address')
            ->join('tb_address as tb_address_destiny', 'tb_address_destiny.id', '=', 'tb_terminal_checkins.destiny_address');

        return array(
            'total' => $queryData->get()->count(),
            'drivers' => $queryData->get(
                [
                    'tb_drivers.name',
                    'tb_drivers.cpf',
                    'tb_drivers.sex',
                    'tb_drivers.cnh_type',
                    'tb_truck_types.description as type_truck',
                    'tb_address_source.latitude as source_latitude',
                    'tb_address_source.longitude as source_longitude',
                    'tb_address_destiny.latitude as destiny_latitude',
                    'tb_address_destiny.longitude as destiny_longitude',
                    'tb_terminal_checkins.loaded'
                ]
            )
        );
    }

    public static function getCheckinSourceAndDestinyByPeriod($dtStart, $dtEnd)
    {
        $checkinsData = self::whereBetween('tb_terminal_checkins.created_at', [$dtStart, $dtEnd])
            ->orderBy('tb_terminal_checkins.truck_type_id')
            ->join('tb_truck_types', 'tb_truck_types.id', '=', 'tb_terminal_checkins.truck_type_id')
            ->join('tb_address as tb_address_source', 'tb_address_source.id', '=', 'tb_terminal_checkins.source_address')
            ->join('tb_address as tb_address_destiny', 'tb_address_destiny.id', '=', 'tb_terminal_checkins.destiny_address')
            ->get(
                [
                    'tb_truck_types.description as type_truck',
                    'tb_address_source.latitude as source_latitude',
                    'tb_address_source.longitude as source_longitude',
                    'tb_address_destiny.latitude as destiny_latitude',
                    'tb_address_destiny.longitude as destiny_longitude',
                    'tb_terminal_checkins.loaded'
                ]
            );

        $sourceAndDestiny = [];

        $checkinsData->each(function($item) use (&$sourceAndDestiny){
            $sourceAndDestiny[strtolower(str_replace(" ", "_", self::removeAccentuation($item->type_truck)))]['origens'][] =  [
                "type_truck" => $item->type_truck,
                "loaded" => $item->loaded,
                "origem_latitude" => $item->source_latitude,
                "origem_longitude" => $item->source_longitude,
            ];
            $sourceAndDestiny[strtolower(str_replace(" ", "_", self::removeAccentuation($item->type_truck)))]['destinos'][] = [
                "type_truck" => $item->type_truck,
                "loaded" => $item->loaded,
                "destino_latitude" => $item->destiny_latitude,
                "destino_longitude" => $item->destiny_longitude,
            ];
        });

        return $sourceAndDestiny;
    }

    private static function removeAccentuation($string){
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
    }
}
