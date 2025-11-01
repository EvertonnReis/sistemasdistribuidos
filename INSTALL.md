# Guia Rápido de Instalação

## 1. Instalação Rápida (10 minutos)

```bash
# Clone o projeto
git clone https://github.com/seu-usuario/online-course-management.git
cd online-course-management

# Instale dependências
composer install

# Configure ambiente
cp .env.example .env
php artisan key:generate
php artisan jwt:secret

# Configure banco no .env (edite com suas credenciais)
# DB_DATABASE=online_courses
# DB_USERNAME=root
# DB_PASSWORD=sua_senha

# Crie o banco
mysql -u root -p -e "CREATE DATABASE online_courses;"

# Execute migrations e seeders
php artisan migrate --seed

# Inicie o servidor
php artisan serve
```

## 2. Teste a API

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password123"}'
```

### Usar o token retornado
```bash
# Copie o access_token da resposta e use:
curl -X GET http://localhost:8000/api/courses \
  -H "Authorization: Bearer SEU_TOKEN_AQUI"
```

## 3. Python Script (Opcional)

```bash
# Instale dependências Python
cd scripts
pip install -r requirements.txt

# Configure variáveis de ambiente
export DB_HOST=127.0.0.1
export DB_DATABASE=online_courses
export DB_USERNAME=root
export DB_PASSWORD=sua_senha

# Execute o script
python generate_course_report.py
```

## 4. Credenciais Padrão

- **Email:** admin@example.com
- **Password:** password123

## 5. Comandos Úteis

```bash
# Limpar cache
php artisan cache:clear
php artisan config:clear

# Verificar rotas
php artisan route:list

# Iniciar queue worker
php artisan queue:work

# Rodar seeders novamente
php artisan db:seed --force

# Criar novo migration
php artisan make:migration create_table_name

# Criar novo controller
php artisan make:controller NomeController

# Criar novo model
php artisan make:model NomeModel
```

## 6. Troubleshooting Rápido

**Erro de permissão:**
```bash
chmod -R 775 storage bootstrap/cache
```

**Erro de JWT:**
```bash
php artisan jwt:secret --force
```

**Erro de banco:**
```bash
# Verifique se MySQL está rodando
sudo service mysql status

# Teste conexão
mysql -u root -p
```

## 7. URLs Importantes

- API Base: `http://localhost:8000/api`
- Login: `http://localhost:8000/api/login`
- Courses: `http://localhost:8000/api/courses`
- Lessons: `http://localhost:8000/api/lessons`

## 8. Estrutura do Projeto

```
sistemasdistribuidos/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Requests/
│   │   ├── Resources/
│   │   └── Middleware/
│   ├── Models/
│   ├── Services/
│   ├── Repositories/
│   └── Jobs/
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
└── scripts/
    └── generate_course_report.py
```

Pronto para começar! 🚀
