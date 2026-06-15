# Tour Management — Sistema de Gerenciamento de Viagens de Turismo

Sistema web administrativo para gerenciamento de viagens de turismo, desenvolvido como teste prático para a **COINPEL — Empresa Municipal de Informática de Pelotas**.

Permite o cadastro e o gerenciamento de **viagens, veículos, motoristas e usuários administradores**, com autenticação, troca de senha obrigatória no primeiro acesso e uma API REST para listagem das viagens.

## Tecnologias

- **[Laravel 12](https://laravel.com/)** (PHP) — back-end monolítico seguindo o padrão MVC
- **[PostgreSQL](https://www.postgresql.org/)** — banco de dados
- **[Tailwind CSS](https://tailwindcss.com/)** — estilização e layout responsivo
- **Alpine.js** — interatividade no front-end (drawers, filtros, máscaras de campo)
- **Laravel Breeze** — scaffolding de autenticação (stack Blade)
- **Vite** — empacotamento dos assets

## Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js 18+ e npm
- PostgreSQL

## Instalação e execução

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/Gustavoalves96/tour-management.git
   cd tour-management
   ```

2. **Instale as dependências:**
   ```bash
   composer install
   npm install
   ```

3. **Crie o arquivo de ambiente e a chave da aplicação:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure a conexão com o PostgreSQL** no arquivo `.env`:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=tour_db
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha
   ```
   > Crie o banco de dados (`tour_db`) no PostgreSQL antes de rodar as migrations.

5. **Rode as migrations e o seeder** (cria as tabelas e um usuário administrador inicial):
   ```bash
   php artisan migrate --seed
   ```

6. **Crie o link de storage** (necessário para as fotos de perfil dos motoristas):
   ```bash
   php artisan storage:link
   ```

7. **Compile os assets:**
   ```bash
   npm run build
   ```
   > Para desenvolvimento, use `npm run dev` em um terminal separado.

8. **Inicie o servidor:**
   ```bash
   php artisan serve
   ```
   Acesse a aplicação em `http://127.0.0.1:8000`.

## Acesso ao sistema

Após rodar o seeder, utilize o administrador inicial:

- **E-mail:** admin@coinpel.com
- **Senha:** password

> Usuários criados pelo painel recebem uma **senha provisória** e são obrigados a redefini-la no primeiro acesso.

## API REST

Endpoint para listar todas as viagens em formato JSON:

```
GET /api/trips
```

Retorna a lista completa de viagens cadastradas.

## Funcionalidades

- **Viagens** — origem, destino, horários de partida e chegada, status (Em andamento / Concluída / Cancelada), valor da passagem e relação com veículo e motorista
- **Veículos** — dados do veículo e opcionais (internet, WC, ar-condicionado, geladeira, etc.)
- **Motoristas** — dados pessoais, endereço, contato e foto de perfil
- **Usuários administradores** — cadastro, edição e **bloqueio de acesso**
- **Autenticação** com troca de senha obrigatória no primeiro acesso
- **Busca e filtros** nas listagens
- **API REST** para listagem de viagens
- Interface **responsiva** (desktop e mobile)
