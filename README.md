# Online Course Management System

Sistema de Gestão de Cursos Online desenvolvido com Laravel 11 e PHP 8.2, implementando uma API RESTful com autenticação JWT e arquitetura em camadas.

## 🎯 Descrição do Projeto

Este projeto é uma API completa para gerenciamento de cursos online, desenvolvida como trabalho de pós-graduação. Implementa boas práticas de desenvolvimento, separação de responsabilidades e integração com Python para geração de relatórios.

### Principais Funcionalidades

- ✅ **Autenticação JWT** - Sistema completo de login, logout e refresh token
- ✅ **CRUD de Cursos** - Gerenciamento completo de cursos com categorias
- ✅ **CRUD de Aulas** - Gerenciamento de lições vinculadas aos cursos
- ✅ **Sistema de Inscrições** - Controle de matrículas e progresso dos alunos
- ✅ **Relatórios Python** - Script Python para geração de relatórios de cursos
- ✅ **Arquitetura em Camadas** - Request → Controller → Service → Repository → Resource
- ✅ **Validação de Dados** - Form Requests para todas as operações
- ✅ **Respostas Padronizadas** - API Resources para formatação consistente

## 🏗️ Arquitetura

```
app/
├── Http/
│   ├── Controllers/        # Controllers (AuthController, CourseController, LessonController)
│   ├── Requests/          # Form Requests para validação
│   ├── Resources/         # API Resources para formatação de respostas
│   └── Middleware/        # JwtMiddleware para autenticação
├── Services/              # Camada de lógica de negócio
├── Repositories/          # Camada de acesso a dados
│   └── Contracts/         # Interfaces dos repositórios
├── Models/                # Eloquent Models
├── Jobs/                  # Background Jobs
└── Providers/             # Service Providers
```

## 📊 Modelos e Relacionamentos

### 5 Modelos Principais

1. **User** - Usuários do sistema
2. **Course** - Cursos disponíveis
3. **Lesson** - Aulas de cada curso
4. **Category** - Categorias dos cursos
5. **Enrollment** - Inscrições dos alunos nos cursos

### Relacionamentos

- `User` **hasMany** `Enrollment`
- `Course` **belongsTo** `Category`
- `Course` **hasMany** `Lesson`
- `Course` **hasMany** `Enrollment`
- `Enrollment` **belongsTo** `User` e `Course`

## 🚀 Instalação e Configuração

### Pré-requisitos

- PHP 8.2 ou superior
- Composer
- MySQL 8.0 ou superior
- Python 3.8 ou superior (para relatórios)
- Node.js (opcional, para assets)

### Passo 1: Clone o Repositório

```bash
git clone https://github.com/seu-usuario/online-course-management.git
cd online-course-management
```

### Passo 2: Instale as Dependências PHP

```bash
composer install
```

### Passo 3: Configure o Ambiente

```bash
# Copie o arquivo .env.example
cp .env.example .env

# Gere a chave da aplicação
php artisan key:generate

# Gere a chave JWT
php artisan jwt:secret
```

### Passo 4: Configure o Banco de Dados

Edite o arquivo `.env` com suas credenciais do MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=online_courses
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

Crie o banco de dados:

```bash
mysql -u root -p
CREATE DATABASE online_courses;
exit;
```

### Passo 5: Execute as Migrations

```bash
php artisan migrate
```

### Passo 6: Popule o Banco de Dados (Opcional)

```bash
php artisan db:seed
```

Isso criará:
- 1 usuário admin (admin@example.com / password123)
- 20 usuários fake
- 5 categorias
- 20 cursos
- 150+ lições
- 100+ inscrições

### Passo 7: Configure o Python para Relatórios

```bash
# Instale as dependências Python
cd scripts
pip install -r requirements.txt
cd ..
```

Configure a variável de ambiente no `.env`:

```env
PYTHON_PATH=python  # ou python3 no Linux/Mac
```

### Passo 8: Configure a Fila (Queue)

```bash
# Configure no .env
QUEUE_CONNECTION=database

# Execute a migration para jobs
php artisan migrate

# Inicie o queue worker (em outro terminal)
php artisan queue:work
```

## 🎮 Como Usar

### Iniciar o Servidor

```bash
php artisan serve
```

A API estará disponível em: `http://localhost:8000`

### Testar a API

#### 1. Login (Obter Token JWT)

```bash
POST http://localhost:8000/api/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password123"
}
```

**Resposta:**
```json
{
  "success": true,
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

#### 2. Usar o Token nas Requisições

Adicione o header em todas as requisições protegidas:

```
Authorization: Bearer {seu_token_aqui}
```

## 📚 Endpoints da API

### Autenticação (Público)

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| POST | `/api/login` | Login e obtenção do token JWT |

### Autenticação (Protegido)

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| POST | `/api/logout` | Logout e invalidação do token |
| POST | `/api/refresh` | Renovar token JWT |
| GET | `/api/me` | Dados do usuário autenticado |

### Courses (Protegido - Requer JWT)

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/api/courses` | Listar todos os cursos (paginado) |
| GET | `/api/courses/{id}` | Detalhes de um curso específico |
| POST | `/api/courses` | Criar novo curso |
| PUT | `/api/courses/{id}` | Atualizar curso existente |
| DELETE | `/api/courses/{id}` | Deletar curso (soft delete) |

### Lessons (Protegido - Requer JWT)

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| GET | `/api/lessons` | Listar todas as lições (paginado) |
| GET | `/api/lessons/{id}` | Detalhes de uma lição específica |
| POST | `/api/lessons` | Criar nova lição |
| PUT | `/api/lessons/{id}` | Atualizar lição existente |
| DELETE | `/api/lessons/{id}` | Deletar lição (soft delete) |

## 🐍 Script Python - Relatório de Cursos

### Executar Manualmente

```bash
# Configure as variáveis de ambiente do banco
export DB_HOST=127.0.0.1
export DB_PORT=3306
export DB_DATABASE=online_courses
export DB_USERNAME=root
export DB_PASSWORD=sua_senha

# Execute o script
python scripts/generate_course_report.py
```

### Executar via Job do Laravel

```bash
# Despachar o job manualmente
php artisan tinker
>>> App\Jobs\GenerateCourseReportJob::dispatch();

# Ou criar um command personalizado
php artisan make:command GenerateCourseReport
```

### O que o Script Faz

1. Conecta ao banco de dados MySQL
2. Busca todos os cursos com contagem de alunos e lições
3. Gera relatório em formato JSON
4. Salva em `storage/reports/course_report_YYYYMMDD_HHMMSS.json`
5. Exibe resumo formatado no console

**Exemplo de Saída:**

```
================================================================================
========================= COURSE ENROLLMENT REPORT =========================
================================================================================

Generated at: 2024-10-27T15:30:00
Total courses: 20

--------------------------------------------------------------------------------
ID    Title                               Category             Students   Lessons   
--------------------------------------------------------------------------------
1     Laravel Advanced Techniques         Web Development      15         12        
2     React Native Mobile App             Mobile Development   12         10        
3     Python Data Science Bootcamp        Data Science         18         15        
...
--------------------------------------------------------------------------------

✓ Report saved to: storage/reports/course_report_20241027_153000.json
================================================================================
```

## 🧪 Testes

### Exemplos de Requisições (cURL)

#### Criar um Curso

```bash
curl -X POST http://localhost:8000/api/courses \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "category_id": 1,
    "title": "Laravel 11 Masterclass",
    "description": "Complete Laravel 11 course",
    "duration_hours": 40,
    "price": 199.99,
    "is_published": true
  }'
```

#### Criar uma Lição

```bash
curl -X POST http://localhost:8000/api/lessons \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "course_id": 1,
    "title": "Introduction to Laravel",
    "content": "Welcome to Laravel course...",
    "video_url": "https://youtube.com/watch?v=example",
    "duration_minutes": 25,
    "is_free": true
  }'
```

## 📁 Estrutura do Banco de Dados

### Tabelas Principais

- `users` - Usuários do sistema
- `categories` - Categorias dos cursos
- `courses` - Cursos disponíveis
- `lessons` - Aulas dos cursos
- `enrollments` - Inscrições dos alunos

### Migrations

Todas as migrations estão em `database/migrations/` e incluem:
- Chaves estrangeiras com cascata
- Índices para otimização de queries
- Soft deletes em cursos e lições
- Timestamps em todas as tabelas

## 🔒 Segurança

- Autenticação JWT com expiração configurável
- Middleware de autenticação em rotas protegidas
- Validação de dados com Form Requests
- Password hashing com bcrypt
- Proteção contra SQL injection (Eloquent ORM)

## 📝 Padrões de Código

- Código em **inglês** (classes, métodos, variáveis)
- Comentários em **português** (apenas os importantes)
- PSR-12 code style
- Arquitetura em camadas bem definida
- Injeção de dependências via Service Container

## 🛠️ Tecnologias Utilizadas

- **Laravel 11** - Framework PHP
- **PHP 8.2** - Linguagem
- **MySQL 8** - Banco de dados
- **JWT (tymon/jwt-auth)** - Autenticação
- **Python 3** - Geração de relatórios
- **Eloquent ORM** - Object-Relational Mapping
- **Faker** - Geração de dados fake

## 📦 Dependências Principais

### PHP (composer.json)
- `laravel/framework: ^11.0`
- `tymon/jwt-auth: ^2.1`
- `fakerphp/faker: ^1.23`

### Python (requirements.txt)
- `mysql-connector-python>=8.0.0`
- `python-dotenv>=1.0.0`

## 👨‍💻 Autor

Projeto desenvolvido como trabalho de pós-graduação em Sistemas Distribuídos.

## 📄 Licença

Este projeto é licenciado sob a MIT License.

## 🔗 Links Úteis

- [Laravel Documentation](https://laravel.com/docs/11.x)
- [JWT Auth Documentation](https://jwt-auth.readthedocs.io/)
- [Eloquent ORM](https://laravel.com/docs/11.x/eloquent)
- [API Resources](https://laravel.com/docs/11.x/eloquent-resources)

## 📌 Notas Importantes

1. **Segurança**: Não use `password123` em produção. Use senhas fortes.
2. **JWT Secret**: Sempre gere uma nova chave JWT com `php artisan jwt:secret`
3. **Queue**: Configure um queue driver adequado para produção (Redis, SQS, etc.)
4. **Python**: Certifique-se de ter as dependências Python instaladas
5. **Banco de Dados**: Faça backups regulares em produção

## 🐛 Troubleshooting

### Erro: "Key path does not exist"
```bash
php artisan key:generate
```

### Erro: "JWT secret not set"
```bash
php artisan jwt:secret
```

### Erro: "SQLSTATE[HY000] [2002] Connection refused"
- Verifique se o MySQL está rodando
- Confirme as credenciais no `.env`

### Erro: "Python command not found"
- Instale Python 3.8+
- Configure `PYTHON_PATH` no `.env`

---

**🎓 Trabalho de Pós-Graduação - Sistemas Distribuídos**

**📅 Outubro 2024**
