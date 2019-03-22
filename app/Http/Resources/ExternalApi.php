<?php

namespace App\Http\Resources;

use GuzzleHttp\Client;
use Illuminate\Http\Resources\Json\JsonResource;

class ExternalApi extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public static function getCepAndLatLng($logradouroOrCep)
    {
        $client = new Client();
        $request = $client->request('GET', sprintf("%s?logradouroOrCep=%s", env("BASE_URI_API_GEOCODE_CEP"), urlencode($logradouroOrCep)) );

        $resultAddressDetail = json_decode($request->getBody()->getContents());

        $latAndLng = explode(",", $resultAddressDetail->LatAndLng);

        return array(
            'CEP' => $resultAddressDetail->CEP,
            'latitude' => $latAndLng[0],
            'longitude' => $latAndLng[1]
        );

    }
}
