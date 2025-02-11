# 📌 Comandos Úteis para Laravel, Docker e MySQL

## **1️⃣ Iniciar o Servidor Laravel**
```bash
php artisan serve --host=127.0.0.1 --port=8080
```
- Inicia o servidor Laravel na porta **8080**.
- `127.0.0.1` indica que o servidor só estará acessível localmente.

```bash
php artisan serve --host=127.0.0.1 --port=8080 &
```
- O `&` serve para rodar o comando **em background** (funciona no Git Bash).

---

## **2️⃣ Gerenciamento de Utilizadores no Laravel**
### **Criar um utilizador manualmente no Tinker**
```php
use App\Models\User;
use App\Models\CargoEnum;

User::create([
    'primeiro_nome' => 'Super',
    'ultimo_nome' => 'Admin',
    'email' => 'superadmin@test.com',
    'data_nascimento' => '1990-01-01',
    'cargo' => CargoEnum::ADMINISTRACAO,
    'funcao' => 'Gestor de Sistema',
    'password' => bcrypt('1234'),
]);
```
- Cria um **utilizador Superadmin** manualmente.
- `bcrypt('1234')` encripta a password.

### **Criar um utilizador com Role e Permissões**
```php
use App\Models\User;
use Spatie\Permission\Models\Role;

$user = User::create([
    'name' => 'Colaborador Teste',
    'email' => 'colaborador@test.com',
    'password' => bcrypt('password'),
]);

$role = Role::where('name', 'colaborador')->first();
$user->assignRole($role);
```
- Cria um utilizador e atribui a role **colaborador**.

---

## **3️⃣ Comandos Artisan Importantes**
### **Limpeza de Cache e Configurações**
```bash
php artisan cache:clear      # Limpa cache do Laravel
php artisan config:clear     # Limpa cache das configurações
php artisan view:clear       # Limpa cache das views Blade
php artisan route:clear      # Limpa cache das rotas
composer dump-autoload       # Atualiza autoload do Composer
```
- Estes comandos são úteis quando **mudanças no código não aparecem**.

---

## **4️⃣ Gerenciamento da Base de Dados**
### **Rollback e Reset de Migrations**
```bash
php artisan migrate:rollback --step=2
```
- Reverte as **últimas 2 migrations**.

```bash
php artisan migrate:fresh --seed
```
- Apaga todas as tabelas, recria e executa os seeders.

### **Executar um Seeder Específico**
```bash
php artisan db:seed --class=UserSeeder
```
- Executa **apenas o UserSeeder**.

---

## **5️⃣ Criar Recursos no Filament**
### **Instalar e Configurar o Filament**
```bash
composer require filament/filament
php artisan filament:install
php artisan filament:install --panels
```
- Instala o Filament e configura **painéis administrativos**.

### **Criar um Novo Administrador no Filament**
```bash
php artisan make:filament-user
```
- Gera um novo utilizador **Filament Admin**.

### **Criar um Recurso Filament para Departamentos**
```bash
php artisan make:filament-resource Departamento
```
- Cria `app/Filament/Resources/DepartamentoResource.php`.

---

## **6️⃣ Executar Comandos MySQL no Terminal**
### **Aceder ao MySQL dentro do Container**
```bash
docker exec -it laravel_mysql bash
```
- Entra no **container do MySQL** no Docker.

### **Conectar ao MySQL**
```bash
mysql -h mysql -u user -p
```
- Conecta ao MySQL com utilizador **user**.

### **Comandos SQL Úteis**
```sql
SHOW DATABASES;        -- Lista as bases de dados
USE gestao_ferias_db;  -- Seleciona a base de dados correta
SHOW TABLES;           -- Lista as tabelas na base de dados
SELECT * FROM users;   -- Lista todos os utilizadores
SELECT * FROM roles;   -- Lista todas as roles
SELECT * FROM permissions;  -- Lista as permissões
DROP DATABASE gestao_ferias_db;  -- Apaga a base de dados (CUIDADO!)
CREATE DATABASE gestao_ferias_db;  -- Cria a base de dados
```
---

## **7️⃣ Comandos Docker Essenciais**
### **Acessar o Container Laravel**
```bash
docker exec -it laravel_app bash
```
- Entra no container onde o Laravel está a correr.

### **Reiniciar os Containers**
```bash
docker-compose down
docker-compose up -d
```
- **Reinicia** os containers no modo **detached** (em background).

### **Construir Container sem Cache**
```bash
docker-compose build --no-cache
```
- Recria os containers **do zero**.

---

## **8️⃣ Criar Modelos, Migrations e Seeders**
### **Criar um Model com Migration**
```bash
php artisan make:model Ferias -m
```
- Cria o modelo `Ferias.php` e um ficheiro de **migration**.

### **Executar Migrations**
```bash
php artisan migrate --seed
```
- Aplica as migrations e **executa os seeders**.

### **Apagar e Recriar Base de Dados**
```bash
php artisan db:wipe
php artisan migrate --seed
```
- Apaga a base de dados e recria **todas as tabelas**.

---

## **9️⃣ Instalar Pacotes e Dependências**
### **Instalar Composer e Dependências no Docker**
```bash
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    nano \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo_mysql mbstring bcmath
```
- Instala **bibliotecas essenciais** no Docker para Laravel.

### **Instalar Composer dentro do Docker**
```bash
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
```
- Instala o **Composer** no container.

---

