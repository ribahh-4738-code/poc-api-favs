## Ambiente

### Clonar o projeto com: 

```
git clone git@github.com:ribahh-4738-code/poc-api-favs.git
```

### Será criada a pasta poc-api-favs.

```
cd poc-api-favs
```

### Copie os seguintes dados para o arquivo .env

```ỳml
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=pocapifavs
DB_USERNAME=sail
DB_PASSWORD=dev098
```

### Inicialize o Docker utilizando o Laravel Sail

```bash
./vendor/bin/sail up -d
```

No browser é possível checar se a instalação está funcionando corretamente: 
http://localhost/

## API

Efetue testes nos endpoints da API utilizando uma ferramenta como o Postman ou cURL.

### Documentação da API

https://documenter.getpostman.com/view/28775668/2sB3WqtKvJ



