# Projeto API RESTful com Docker

Este projeto é uma API RESTful desenvolvida em PHP 8 com PostgreSQL, 
utilizando Docker para containerização. A API inclui autenticação JWT e 
endpoints para gerenciamento de produtos, tags e categorias.

## Requisitos

- Docker
- Docker Compose

## Requisitos locais

- php 8.1
- composer
- postgresSQL

## Como Executar

1. Clone o repositório:
2. instale o docker-compose
3. Rode no diretorio: docker-compose up -d --build
4. caso executando sem container no servidor embutido 
rode no diretorio: php -S localhost:8000 -t app/public
5. usar postman ou outro parecido para gerenciar requisições


## FLUXO -- Como Usar

## login
- **POST /login**: Cria o login.


## register
- **POST /register**: Cria um novo usuario.

## products

- **GET /products**: Lista todos os produtos.
- **GET /products/{id}**: Exibe detalhes de um produto específico.
- **GET /products/categories/{id}**: Exibe detalhes de produtos com essa categoria
- **GET /products?sortBy={sort_type}**: Lista produtos com ordenação.(tipos de ordenacao : preço, nome, curtidas)
- **GET /products?discount={discount_percentage}**: Lista produtos com desconto.
- **GET /products?sortBy={sort_type}&&discount={discount_percentage}**: Lista produtos com desconto e ordenação escolhida.
- **POST /products**: Cria um novo produto.
- **PUT /products/{id}**: Atualiza as informações de um produto existente.
- **DELETE /products/{id}**: Remove um produto.

## productsTag
- **GET /productsTag/{product_id}/tags**: Lista todas as tags de um produto.
- **POST /productsTag/{product_id}/tags**: Vincula tags a um produto
- **GET /products/{tag_id}**: Exibe detalhes de um produto específico.
- **DELETE /productsTag/{product_id}/tags/{tag_id}**: Lista produtos associados a uma tag específica.

## Tags
- **GET /tags**: Lista todos as tags.
- **GET /tags/{id}**: Exibe detalhes de uma tag específica.
- **POST /tags**: Cria uma novo tag.
- **PUT /tags/{id}**: Atualiza as informações de uma tag existente.
- **DELETE /tags/{id}**: Remove uma tag.

## Categories
- **GET /Categories**: Lista todos as categorias.
- **GET /Categories/{id}**: Exibe detalhes de uma categoria específica.
- **POST /Categories**: Cria uma nova categoria.
- **PUT /Categories/{id}**: Atualiza as informações de uma categoria existente.
- **DELETE /Categories/{id}**: Remove uma categoria.

Cada operação é acessível através de requisições HTTP padrão (GET, POST, PUT, DELETE).

## Estrutura do Projeto
├── app/ 
- Em app estão todos os codigos e arquivos desenvolvidos diretamente para a API, dentro de app
em public esta o index.php

├── docker/ 
- È onde esta localizado o dockerfile e arquivo de instancia 


├── scripts/ 
- È onde esta localizado o init que defina as tabelas do banco

│ ├── vendor/ 
- é onde estão os codigos e ferramentas armazenados pelo composer


## Testes
Para rodar os testes automatizados, use:
./vendor/bin/phpunit


## Tecnologias Usadas
- php
- composer
- postgresSql
- Docker
- PHPUnit
- jwt
- postman

## Autor
- Sergio Antoniolli filho(sergioantoniolli302@gmail.com)



## Changelog
### v1.0.0
- Primeira versão do projeto.
- Funcionalidades CRUD para produtos, categorias e tags.