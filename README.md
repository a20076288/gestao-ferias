Laravel Gestão de Férias - Guia de Instalação para Testes

Este documento fornece instruções detalhadas para configurar e testar a aplicação Laravel Gestão de Férias num ambiente local utilizando Docker.

1. Pré-requisitos
Antes de iniciar, certifique-se de que possui os seguintes requisitos instalados no seu sistema:

Docker Desktop (com suporte a WSL2 ativado, se estiver a utilizar Windows).
Git (para clonar o repositório).
Um terminal compatível (Git Bash, PowerShell ou WSL).

2. Instalação e Configuração
2.1 Clonar o Repositório
Para obter uma cópia do projeto, abra um terminal e execute o seguinte comando:

git clone https://github.com/a20076288/laravel-gestao-ferias.git
cd laravel-gestao-ferias

2.2 Iniciar a Aplicação
Com os ficheiros do projeto disponíveis, inicie os containers Docker executando:

docker-compose up --build -d

Este comando irá:

Construir e iniciar os containers necessários para a aplicação (Laravel, MySQL e Nginx).
Configurar automaticamente os serviços, permitindo acesso imediato à aplicação.
Para verificar se os containers foram iniciados corretamente, utilize:

docker ps

Os seguintes containers devem estar ativos:

laravel_app (Laravel/PHP)
laravel_mysql (Base de dados MySQL)
laravel_nginx (Servidor web Nginx)
Se algum container não estiver ativo, tente reiniciar a aplicação com:

docker-compose restart

2.3 Aceder à Aplicação
Assim que os containers estiverem a funcionar, a aplicação poderá ser acedida através do navegador nos seguintes endereços:

Página de Login do Painel de Administração (Filament):
http://localhost/admin/login
http://127.0.0.1/admin/login
Caso a página não carregue, verifique se os containers estão em execução (docker ps) e se o servidor está corretamente configurado (docker-compose logs -f).

3. Credenciais de Acesso para Testes
A aplicação inclui utilizadores pré-configurados para facilitar a validação das funcionalidades. Utilize as seguintes credenciais para aceder ao sistema:

Utilizador Superadmin:
Email: superadmin@test.com
Password: 1234

Utilizador Administrador:
Email: admin@test.com
Password: 1234

Utilizador Colaborador:
Email: colaborador@test.com
Password: 1234

Funções e Permissões:

Superadmin: Acesso total ao sistema, incluindo gestão de utilizadores e permissões.
Administrador: Pode aprovar/rejeitar pedidos de férias e gerir departamentos.
Colaborador: Apenas pode visualizar o calendário e submeter pedidos de férias para aprovação.

4. Comandos Úteis
Caso seja necessário gerir ou solucionar problemas na aplicação, os seguintes comandos podem ser utilizados:

Verificar containers ativos:
docker ps

Reiniciar os containers:
docker-compose restart

Parar os containers:
docker-compose down

Aceder ao terminal do Laravel dentro do container:
docker exec -it laravel_app bash

Executar migrações e seeders (caso os utilizadores de teste não tenham sido criados):
docker exec -it laravel_app bash -c "php artisan migrate --seed"

Verificar logs do Laravel:
docker exec -it laravel_app bash -c "tail -f storage/logs/laravel.log"

5. Resolução de Problemas
A página de login não está acessível (localhost/admin/login)
Confirme se os containers estão a correr:
docker ps
Se laravel_nginx não estiver listado, reinicie a aplicação:
docker-compose restart

Verifique os logs para identificar possíveis erros:
docker-compose logs -f

Erro de login: "Credenciais inválidas"
Confirme que os utilizadores de teste foram criados corretamente.
Se necessário, recrie a base de dados e os utilizadores:
docker exec -it laravel_app bash -c "php artisan migrate:fresh --seed"
Atenção: Este comando apagará e recriará todas as tabelas da base de dados.

Erro de permissões no Laravel
Se encontrar erros de permissões ao aceder à aplicação, execute:

docker exec -it laravel_app bash -c "chown -R www-data:www-data /var/www/html && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache"

6. Suporte
Se encontrar dificuldades na instalação ou execução do projeto, contacte o responsável pelo repositório ou abra uma issue no GitHub.

