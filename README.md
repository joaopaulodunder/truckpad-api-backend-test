![TruckPad](imgs_readme/truckpad.png?raw=true)  
  
# Requisitos iniciais da aplicação.  
Ter instalado o docker, docker-compose e composer  
  
* https://docs.docker.com/install/  
* https://docs.docker.com/compose/install/  
* https://getcomposer.org/download/  
  
  
### Iniciar aplicação e infraestrutura docker.  
  
Após o clone do projeto entre no diretorio do mesmo **(truckpad-api-backend-test)** e execute os comandos abaixo seguindo a sequencia.  
  
- composer install (Responsavél por instalar as dependências do projeto)  
  
- docker-compose up -d (Ele ira subir toda a infraestrutura necessária para o projeto em containers docker)  
  
  
### Iniciando estrutura de banco de dados necessária para o projeto.  
  
Para isto foi criado Migrations e Seeds, basta executar os seguintes comandos.  
  
- php artisan migrate --database=mysql2 (Cria a estrutura do banco de dados)  
  
- php artisan db:seed --class=TruckTypes --database=mysql2 (Insere os tipos de trucks iniciais)  
  
  
## Usando a API  
  
**Criar um novo motorista.**
  
* [POST] - http://127.0.0.1:8000/api/v1/driver  

        {
	        nome:"João Silva"
	        idade:"20"
	        nascimento:"1993-03-20"
	        cpf:"57938573035"
	        sexo:"M" ['M', 'F']
	        dono:"YES" ['YES', 'NO']
	        tpCnh:"D"
        }

**Criar um novo checkin.**  
###### 1. Foi implementado uma API em GoLang, a mesma vai identificar as latitudes e longitudes dos endereços e gravar no banco de dados toda vez que um novo checkin for criado. 
###### 2. Se no cadastro do checkin não tiver o CEP do logradouro esta  mesma API GoLang vai identificar o  CEP do endereço preenche-lo automaticamente no banco de dados. 
###### 3. Esta API GoLang esta rodando através de FaaS (Function as a service) no Cloud Function do Google-Cloud, mas o código na mesma esta na raiz do projeto (truckpadGetCepAndLatLng.go).

* [POST] - http://127.0.0.1:8000/api/v1/checkin 

        {
            motoristaCpf:"57938573035"
            tipoCaminhao:"Caminhão Truck"
            carregado:"NO" ['YES', 'NO']
            origemLogradouro:"Rua Capitao Gabriel"
            origemNumero:"14"
            origemBairro:"Centro"
            origemEstado:"SP"
            origemCidade:"Guarulhos"
            origemCep:"07012-013"
            destinoLogradouro:Rua Venezuela
            destinoNumero:"40"
            destinoBairro:"Jardim Paulista"
            destinoEstado:"SP"
            destinoCidade:"Sao Paulo"
            destinoCep:"05402-000"
        }

**Consultar motoristas que NÃO possuem carga em um período.**

* [GET] - http://127.0.0.1:8000/api/v1/checkin/carregado?carregado=false&dtStart=YYYY-MM-DD&dtEnd=YYYY-MM-DD

**Consultar motoristas passaram com o truck carregado.**

* [GET] - http://127.0.0.1:8000/api/v1/checkin/carregado?carregado=true&dtStart=YYYY-MM-DD&dtEnd=YYYY-MM-DD

**Consultar motoristas que possuem seus próprios veículos.**

* [GET] - http://127.0.0.1:8000/api/v1/drivers?possuiVeiculo=true

**Consultar motoristas que NÃO possuem seus próprios veículos.**

* [GET] - http://127.0.0.1:8000/api/v1/drivers?possuiVeiculo=false

**Consultar origem e destino agrupado por tipo de truck em um determinado período.**

* [GET] - http://127.0.0.1:8000/api/v1/checkins/origemDestino?dtStart=YYYY-MM-DD&dtEnd=YYYY-MM-DD


**Realizar atualização no cadastro de motoristas**

 * [PUT] - http://127.0.0.1:8000/api/v1/driver/{id}

        {
	        nome:"João Da Silva"
	        idade:"28"
	        nascimento:"1993-05-20"
	        cpf:"57938573035"
	        sexo:"M"
	        dono:"YES"
	        tpCnh:"C"
        }

## Tecnologias utilizadas neste projeto.

 - PHP (http://php.net/)
 - Laravel (https://laravel.com/)
 - Golang (https://golang.org/)
 - Docker (https://www.docker.com/)
 - Cloud-Functions (https://cloud.google.com/functions/)
 - Geocode API (https://developers.google.com/maps/documentation/geocoding/start)

## Observações.

 1. **Deixei o arquivo .env preenchido para comodidade na hora de testar o
    projeto, pois em um cenário real o .env não deve ficar versionado no Github.**
