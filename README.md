# Marketplace Connector

O **Marketplace Connector** é um sistema desenvolvido para integrar a API de um Marketplace à API de um HUB. A aplicação recebe uma solicitação para importação de anúncios, agenda um job que realiza a busca dos anúncios no Marketplace (utilizando paginação e processamento assíncrono via filas) e, em seguida, envia os anúncios para o HUB.

## Índice

- [Introdução](#introdução)
- [Funcionalidades](#funcionalidades)
- [Arquitetura e Design](#arquitetura-e-design)
- [Instalação](#instalação)
- [Configuração e Execução](#configuração-e-execução)
- [Endpoints e Uso](#endpoints-e-uso)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Observabilidade](#observabilidade)
- [Extras](#Extras)
- [Licença](#licença)

## Introdução

O Marketplace Connector foi desenvolvido utilizando o framework Laravel, com foco em boas práticas de desenvolvimento e utilização de conceitos como filas (jobs/queues), eventos/listeners e padrões de projeto (como o State Pattern). O sistema foi criado para receber requisições HTTP que iniciam a importação de anúncios do Marketplace e, de forma assíncrona, processar e enviar esses anúncios para o HUB, garantindo escalabilidade e robustez no processamento.

## Funcionalidades

- **Recepção de Solicitação:** Endpoint para iniciar a importação de anúncios.
- **Agendamento de Jobs:** Utilização de filas para processar a importação de forma assíncrona e paralela.
- **Integração com Marketplace:** Busca paginada de anúncios a partir de endpoints mock.
- **Integração com HUB:** Envio dos anúncios importados para o HUB.
- **Observabilidade:** Logs e monitoramento em pontos críticos do fluxo.
- **Uso de Padrões de Projeto:** Implementação do Design Pattern State para controle do fluxo de importação.

## Estrutura do Projeto

A estrutura de diretórios principal do projeto é organizada da seguinte forma:

```
marketplace_connector/
│-- app/
│   ├── Console/
│   │   ├── Commands
│   ├── Events/
│   ├── Http/
│   │   ├── Controllers/
│   ├── Jobs/
│   ├── Listeners/
│   ├── Models/
│   ├── Providers/
│   ├── States/
│-- config/
│-- database/
│   ├── migrations/
│-- routes/
│   ├── web.php
│-- tests/
│   ├── Unit
│-- .env.example
│-- docker-compose.yml
│-- mocketplace.json
│-- README.md
```

## Instalação

### Clonando o Repositório

```bash
git clone https://github.com/JoaoA1egre/marketplace_connector.git
cd marketplace_connector
```

### Instalação das Dependências

```bash
composer install
```

### Configuração do Ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### Migrações e Seeders

```bash
php artisan migrate
```

## Configuração e Execução

### Docker

```bash
./vendor/bin/sail up
```

### Execução das Filas

```bash
php artisan queue:work
```

### Configuração do Mock da API

```bash
docker run -d --mount type=bind,source=./mocketplace.json,target=/data,readonly -p 3000:3000 mockoon/cli:latest -d data -p 3000
```

## Endpoints e Uso

### Importação de Anúncios

**GET** `/import-offers`

<!-- Esta rota permite verificar o status de uma job de importação de ofertas ao fornecer o identificador único (id) da job. -->
**GET** `/import-offers\{id}`

### Endpoints do Marketplace (Mock)

- **GET** `http://localhost:3000/offers`
- **GET** `http://localhost:3000/offers/{reference}`
- **GET** `http://localhost:3000/offers/{reference}/images`
- **GET** `http://localhost:3000/offers/{reference}/prices`

### Envio ao HUB (Mock)

**POST** `http://localhost:3000/hub/create-offer`

```json
{
    "title": "string",
    "description": "string",
    "status": "string",
    "stock": 999999
}
```

## Tecnologias Utilizadas

- Laravel
- PostgreSQL
- Redis
- Laravel Sail
- Mockoon
- Padrões: Clean Architecture, State Pattern

## Observabilidade

- **Logs:** Utiliza o sistema de logs do Laravel para registrar eventos críticos.

## Extras

### Execução Manual da Job `ImportAdsJob`

É possível executar a job `ImportAdsJob` manualmente dentro do seu ambiente Docker do Laravel. Esta funcionalidade foi desenvolvida para facilitar alguns testes manuais.

#### Passo 1: Acessar o terminal do container Docker

```bash
docker exec -it CONTAINERNAME bash
```

#### Passo 2: Executar o comando Artisan para rodar a job manualmente

Dentro do terminal do Docker, execute o seguinte comando:

```bash
php artisan job:import-ads
```

Este comando cria uma nova entrada na tabela `import_jobs` com o status `pending` e executa a job `ImportAdsJob` imediatamente. O job será executado de forma síncrona, ou seja, não usará fila para execução.

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).

