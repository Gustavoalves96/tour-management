# Tour Management — Sistema de Gerenciamento de Viagens de Turismo

Sistema web administrativo para gerenciamento de viagens de turismo, desenvolvido como teste prático para a COINPEL — Empresa Municipal de Informática de Pelotas.

Permite o cadastro e o gerenciamento de viagens, veículos, motoristas e usuários administradores, com autenticação, troca de senha obrigatória no primeiro acesso e uma API REST para listagem das viagens. Além dos módulos obrigatórios, foram propostos os módulos de **Clientes**, **Pacotes** e **Contratos** (que conectam clientes a pacotes), e um **dashboard de estatísticas** como tela inicial.

A interface segue o design do Figma fornecido pela COINPEL e é totalmente responsiva (desktop e mobile, até 320px).

## Tecnologias

* [Laravel 12](https://laravel.com/) (PHP) — back-end monolítico seguindo o padrão MVC
* [PostgreSQL](https://www.postgresql.org/) — banco de dados
* [Tailwind CSS](https://tailwindcss.com/) — estilização e layout responsivo
* Alpine.js — interatividade no front-end (drawers, filtros, máscaras de campo)
* Laravel Breeze — scaffolding de autenticação (stack Blade)
* Vite — empacotamento dos assets

## Requisitos

* PHP 8.2 ou superior
* Composer
* Node.js 18+ e npm
* PostgreSQL

## Instalação e execução

1. Clone o repositório:

```
git clone https://github.com/Gustavoalves96/tour-management.git
cd tour-management
```

2. Instale as dependências:

```
composer install
npm install
```

3. Crie o arquivo de ambiente e a chave da aplicação:

```
cp .env.example .env
php artisan key:generate
```

4. Configure a conexão com o PostgreSQL no arquivo `.env` (ajuste a porta conforme sua instalação):

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tour_db
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

Crie o banco de dados (`tour_db`) no PostgreSQL antes de rodar as migrations.

5. Rode as migrations e o seeder (cria as tabelas e um usuário administrador inicial):

```
php artisan migrate --seed
```

6. Crie o link de storage (necessário para as fotos de perfil dos motoristas):

```
php artisan storage:link
```

7. Compile os assets:

```
npm run build
```

Para desenvolvimento, use `npm run dev` em um terminal separado.

8. Inicie o servidor:

```
php artisan serve
```

Acesse a aplicação em `http://127.0.0.1:8000`.

## Acesso ao sistema

Após rodar o seeder, utilize o administrador inicial:

* E-mail: admin@coinpel.com
* Senha: password

Usuários criados pelo painel recebem uma senha provisória e são obrigados a redefini-la no primeiro acesso.

## API REST

Endpoint para listar todas as viagens em formato JSON:

```
GET /api/trips
```

Retorna a lista completa de viagens cadastradas.

## Funcionalidades

### Módulos obrigatórios (requisitos do teste)

* **Viagens** — origem, destino, horários de partida e chegada, status (Em andamento / Concluída / Cancelada), valor da passagem e relação com veículo e motorista
* **Veículos** — dados do veículo e opcionais (internet, WC, ar-condicionado, geladeira, etc.)
* **Motoristas** — dados pessoais, endereço, contato e foto de perfil; filtro por situação (Disponível / Em viagem), derivado das viagens em andamento
* **Usuários administradores** — cadastro, edição e bloqueio de acesso
* Autenticação com troca de senha obrigatória no primeiro acesso
* API REST para listagem de viagens
* Interface responsiva (desktop e mobile)

### Funcionalidades extras propostas

* **Clientes** — cadastro completo (nome, e-mail, telefone, CPF, nascimento, cidade, observações) com filtro por cidade
* **Pacotes** — origem, destino, preço, duração e capacidade, com filtro por destino
* **Contratos** — conectam um cliente a um pacote, com valor total, número de pessoas, status, datas e observações; o valor total e a data de término são preenchidos automaticamente a partir do pacote escolhido
* **Dashboard de estatísticas** — tela inicial após o login, agregando as contagens de viagens por status e os totais de motoristas, veículos, clientes, pacotes e contratos, além do valor total em contratos (sem criar tabelas novas)
* **Meu perfil** — área onde o próprio administrador gerencia seus dados, altera a senha e pode excluir a própria conta (não prevista no design)


## Detalhes de implementação

* Código em inglês (classes, métodos, variáveis) com comentários em português
* Padrão MVC e reutilização de código (ex.: componentes Blade como o botão "Adicionar")
* Cada listagem segue o mesmo padrão de tela: busca, filtros, drawer lateral para cadastro/edição, e visões separadas para desktop (tabela) e mobile (cards)
