Executando o Job Manualmente
Para executar o job ImportAdsJob manualmente dentro do seu ambiente Docker do Laravel, siga os passos abaixo:

Acesse o terminal do container Docker:

Primeiro, você precisa acessar o terminal do container Docker que está executando a aplicação Laravel. Você pode fazer isso usando o comando abaixo:

docker exec -it CONTAINERNAME bash

Execute o comando para rodar o Job:

Dentro do terminal do Docker, execute o seguinte comando Artisan para rodar o job manualmente:

php artisan job:import-ads

Este comando cria uma nova entrada na tabela import_jobs com o status pending e executa o job ImportAdsJob imediatamente. O job será executado de forma síncrona, ou seja, não usará fila para execução.