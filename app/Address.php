<?php

namespace App;

use App\Http\Resources\ExternalApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use League\Flysystem\Exception;


class Address extends Model
{
    protected $table = 'tb_address';

    public static function createAddress(array $dadosDeEndereco)
    {
        $addressModel = new Address();

        try {
            foreach ($dadosDeEndereco as $enderecoOrigemDestino) {
                $addressModel->street = $enderecoOrigemDestino['street'];
                $addressModel->number = $enderecoOrigemDestino['number'];
                $addressModel->neighborhood = $enderecoOrigemDestino['neighborhood'];
                $addressModel->state = $enderecoOrigemDestino['state'];
                $addressModel->city = $enderecoOrigemDestino['city'];

                if (is_null($enderecoOrigemDestino['cep'])) {
                    $addressDescription = trim(sprintf(
                        "%s, %s, %s, %s %s",
                        $enderecoOrigemDestino['street'],
                        $enderecoOrigemDestino['number'],
                        $enderecoOrigemDestino['neighborhood'],
                        $enderecoOrigemDestino['state'],
                        $enderecoOrigemDestino['city']
                    ));
                    $requestLatAndLngCep = ExternalApi::getCepAndLatLng($addressDescription);
                    $addressModel->cep = $requestLatAndLngCep['CEP'];
                } else {
                    $addressDescription = trim(sprintf(
                        "%s, %s, %s, %s %s",
                        $enderecoOrigemDestino['street'],
                        $enderecoOrigemDestino['number'],
                        $enderecoOrigemDestino['neighborhood'],
                        $enderecoOrigemDestino['state'],
                        $enderecoOrigemDestino['city'],
                        $enderecoOrigemDestino['cep']
                    ));

                    $requestLatAndLngCep = ExternalApi::getCepAndLatLng($addressDescription);
                    $addressModel->cep = $enderecoOrigemDestino['cep'];
                }

                $addressModel->latitude = $requestLatAndLngCep['latitude'];
                $addressModel->longitude = $requestLatAndLngCep['longitude'];

                $resultCount = Address::where('cep', '=', $addressModel->cep)
                    ->where('street', '=', $enderecoOrigemDestino['street'])
                    ->where('number', '=', $enderecoOrigemDestino['number'])
                    ->where('neighborhood', '=', $enderecoOrigemDestino['neighborhood'])
                    ->where('state', '=', $enderecoOrigemDestino['state'])
                    ->where('city', '=', $enderecoOrigemDestino['city'])
                    ->count();

                if (!$resultCount) {
                    $addressModel->save();
                }
            }
            return true;
        }catch (Exception $exception){
            return false;
        }
    }

    public static function getIdAddressByCepAndLogradouroNumber($cep, $numero)
    {
        return Address::where('cep', '=', $cep)
            ->where('number', '=', $numero)
            ->get('id')->toArray()[0]['id'];
    }

    public static function getCepByAddressDesription(array $addressDescription)
    {
        return Address::where('street', '=', $addressDescription['street'])
        ->where('number', '=', $addressDescription['number'])
        ->where('neighborhood', '=', $addressDescription['neighborhood'])
        ->where('state', '=', $addressDescription['state'])
        ->where('city', '=', $addressDescription['city'])
        ->get('cep')->toArray()[0]['cep'];
    }

}
