# API de Processamento de Pagamentos (Multi-Gateway)

API RESTful para processamento de pagamentos utilizando múltiplos gateways com fallback automático. Desenvolvida utilizando o framework Laravel.

---

# Objetivo

Implementar uma API capaz de:

- Processar pagamentos utilizando múltiplos gateways
- Realizar fallback automático em caso de falha
- Persistir transações em banco de dados
- Permitir consulta de pagamentos
- Permitir reembolso de transações

---

# Tecnologias Utilizadas

- Laravel 12
---

# Arquitetura do Projeto

A aplicação segue uma arquitetura baseada em serviços e gateways, permitindo desacoplamento entre a lógica de negócio e os provedores de pagamento.

app
 └ Services
     └ Payments
         ├ PaymentService.php
         ├ Contracts
         │   └ PaymentGatewayInterface.php
         └ Gateways
             ├ StripeGateway.php
             └ PayPalGateway.php


### PaymentService

Responsável por:

- Orquestrar os gateways
- Implementar fallback automático
- Retornar o resultado do pagamento

### PaymentGatewayInterface

Define o contrato obrigatório para qualquer gateway.

Métodos:

    charge(array $data)
    refund(string $transactionId)


### Gateways

Implementações específicas de cada provedor de pagamento.

- StripeGateway
- PayPalGateway

---

# Estrutura do Banco de Dados

Tabela principal:

## payments

| Campo | Tipo |
|------|------|
id | integer
gateway | string
transaction_id | string
amount | integer
status | string
payload | json
created_at | timestamp
updated_at | timestamp

---

# Configuração dos Gateways

Arquivo:

config/gateways.php


Exemplo:

```php
return [

    'stripe' => [
        'url' => env('STRIPE_URL', 'http://localhost:3001'),
    ],

    'paypal' => [
        'url' => env('PAYPAL_URL', 'http://localhost:3002'),
    ]

];

Executando o Projeto
1 - Clonar o repositório
git clone https://github.com/seu-repositorio/api-pagamentos.git
2 - Instalar dependências
composer install
3 - Configurar ambiente

Copiar o arquivo:

.env.example

para

.env
4 - Configurar banco de dados

Editar no .env

DB_DATABASE=db_pg
DB_USERNAME=root
DB_PASSWORD=
5 - Executar migrations
php artisan migrate
6 - Iniciar servidor
php artisan serve

API disponível em:

http://127.0.0.1:8000
Simulação dos Gateways

Os gateways são simulados através de um container Docker.

Execute:

docker run -p 3001:3001 -p 3002:3002 -e REMOVE_AUTH='true' matheusprotzen/gateways-mock

Portas utilizadas:

Gateway	Porta
Gateway 1	3001
Gateway 2	3002
Endpoints da API
Criar pagamento

POST

/api/payments

Body:

{
 "amount": 1000,
 "name": "Joao Silva",
 "email": "joao@email.com",
 "card_number": "4111111111111111",
 "cvv": "123"
}

Resposta:

{
 "id": 1,
 "gateway": "stripe",
 "status": "paid"
}
Listar pagamentos

GET

/api/payments
Buscar pagamento

GET

/api/payments/{id}
Reembolso

POST

/api/payments/{id}/refund

Resposta:

{
 "status": "refunded"
}
Estratégia de Fallback

Fluxo de processamento:

Gateway 1
   ↓
Falha
   ↓
Gateway 2
   ↓
Sucesso

Caso todos os gateways falhem, a API retorna erro.

Testes da API

A API pode ser testada utilizando ferramentas como:

Insomnia

Postman

Possíveis Melhorias Futuras

Autenticação nos gateways

Circuit breaker

Logs estruturados

Testes automatizados

Autor

João Gabriel