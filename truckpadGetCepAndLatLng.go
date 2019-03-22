package main

import (
	"fmt"
	"context"
	"googlemaps.github.io/maps"
	"log"
	"net/http"
)
// Adicionar o token de autorizacao da api-geocode
var c, err = maps.NewClient(maps.WithAPIKey("API_KEY_GOOGLE_CLOUD_GEOCODE"))

// Função que interage com a api do google através do pacote googlemaps.github.io/maps
func requestToApiGoogleGeoCodeWithAddress(addressDescription string) []maps.GeocodingResult {
	if err != nil {
		log.Fatalf("fatal error: %s", err)
	}
	r := &maps.GeocodingRequest{
		Address: addressDescription,
	}

	resp, err := c.Geocode(context.Background(), r)
	if err != nil {
		log.Fatalf("fatal error: %s", err)
	}

	return resp
}

//Busca os dados de geo-localização através de um endereo ou cep
func getLatLngByCep(addressDescription string) string {
	result := requestToApiGoogleGeoCodeWithAddress(addressDescription)
	return result[0].Geometry.Location.String();
}
//Busca os dados de cep através de um endereco (rua, bairro, cidade, estado)
func getPostalCodeByAddressDescription(addressDescription string) string {
	result := requestToApiGoogleGeoCodeWithAddress(addressDescription)[0].AddressComponents
	for i, num := range result {
		if (num.Types[0] == "postal_code") {
			return result[i].LongName
		}
	}
	return "POSTAL_CODE_NOT_FOUNT"
}


func SearchCepAndLatLng(w http.ResponseWriter, r *http.Request) {
	logradouroOrCep, ok := r.URL.Query()["logradouroOrCep"]

	if !ok || len(logradouroOrCep[0]) < 1 {
		fmt.Fprint(w, "{\"status\": \"Url Param logradouroOrCep is missing\"}")
		return
	}

	fmt.Fprint(w, "{\"CEP\": \"" + getPostalCodeByAddressDescription(logradouroOrCep[0]) + "\", \"LatAndLng\": \"" + getLatLngByCep(logradouroOrCep[0]) + "\"}")
}

func main() {
	http.HandleFunc("/", SearchCepAndLatLng) // set router
	err := http.ListenAndServe(":9090", nil) // set listen port
	if err != nil {
		log.Fatal("ListenAndServe: ", err)
	}
}