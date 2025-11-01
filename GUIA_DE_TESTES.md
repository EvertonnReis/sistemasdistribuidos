# 🧪 Guia de Testes - Sistema de Gestão de Cursos Online

## 📥 Como Importar no Insomnia

1. Abra o **Insomnia**
2. Clique em **Application** → **Preferences** → **Data** → **Import Data**
3. Selecione o arquivo: `insomnia_collection.json`
4. Clique em **Import**

✅ **16 requisições** serão importadas organizadas em 4 pastas!

---

## 🎯 Roteiro de Testes (SIGA ESTA ORDEM!)

### **PASSO 1: Autenticação** 🔐

#### 1.1 - Login (Obter Token)
```
POST http://127.0.0.1:8000/api/login

Body:
{
  "email": "admin@example.com",
  "password": "password123"
}

✅ Resposta esperada: Status 200
✅ Copie o valor do campo "access_token"
✅ No Insomnia, vá em Environment → Base Environment
✅ Cole o token no campo "token"
```

**Resultado esperado:**
```json
{
  "success": true,
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

---

### **PASSO 2: Testar Autenticação** ✅

#### 2.1 - Obter Usuário Logado
```
GET http://127.0.0.1:8000/api/me
Header: Authorization: Bearer SEU_TOKEN_AQUI

✅ Resposta esperada: Status 200
✅ Retorna os dados do usuário admin
```

#### 2.2 - Refresh Token
```
POST http://127.0.0.1:8000/api/refresh
Header: Authorization: Bearer SEU_TOKEN_AQUI

✅ Resposta esperada: Status 200
✅ Retorna um novo token
```

---

### **PASSO 3: CRUD de Courses** 📚

#### 3.1 - Listar Todos os Cursos
```
GET http://127.0.0.1:8000/api/courses
Header: Authorization: Bearer SEU_TOKEN_AQUI

✅ Status 200
✅ Retorna lista com 20 cursos
✅ Tem paginação (meta.current_page, meta.total)
✅ Cada curso tem: id, title, slug, description, instructor, etc.
```

#### 3.2 - Ver Curso Específico
```
GET http://127.0.0.1:8000/api/courses/1
Header: Authorization: Bearer SEU_TOKEN_AQUI

✅ Status 200
✅ Retorna 1 curso com todos os detalhes
✅ Inclui relacionamento com categoria
```

#### 3.3 - Criar Novo Curso
```
POST http://127.0.0.1:8000/api/courses
Header: Authorization: Bearer SEU_TOKEN_AQUI

Body:
{
  "title": "Curso de Teste via API",
  "description": "Este é um curso criado através da API para teste",
  "category_id": 1,
  "instructor": "Professor Teste",
  "duration_hours": 40,
  "price": 199.90,
  "level": "intermediary",
  "is_published": true
}

✅ Status 201 (Created)
✅ Retorna o curso criado com ID gerado
✅ Slug foi gerado automaticamente
✅ Mensagem: "Curso criado com sucesso"
```

#### 3.4 - Atualizar Curso
```
PUT http://127.0.0.1:8000/api/courses/1
Header: Authorization: Bearer SEU_TOKEN_AQUI

Body:
{
  "title": "Curso Atualizado via API",
  "description": "Descrição atualizada",
  "category_id": 1,
  "instructor": "Professor Atualizado",
  "duration_hours": 50,
  "price": 249.90,
  "level": "advanced",
  "is_published": true
}

✅ Status 200
✅ Retorna o curso atualizado
✅ Mensagem: "Curso atualizado com sucesso"
```

#### 3.5 - Deletar Curso
```
DELETE http://127.0.0.1:8000/api/courses/21
Header: Authorization: Bearer SEU_TOKEN_AQUI

✅ Status 200
✅ Mensagem: "Curso deletado com sucesso"
✅ Curso foi soft deleted (não apagado permanentemente)
```

---

### **PASSO 4: CRUD de Lessons** 📝

#### 4.1 - Listar Lições de um Curso
```
GET http://127.0.0.1:8000/api/courses/1/lessons
Header: Authorization: Bearer SEU_TOKEN_AQUI

✅ Status 200
✅ Retorna lista de lições do curso 1
✅ Lições ordenadas por 'order' (sequência)
```

#### 4.2 - Ver Lição Específica
```
GET http://127.0.0.1:8000/api/courses/1/lessons/1
Header: Authorization: Bearer SEU_TOKEN_AQUI

✅ Status 200
✅ Retorna 1 lição com todos os detalhes
✅ Inclui: title, content, video_url, duration_minutes, order
```

#### 4.3 - Criar Nova Lição
```
POST http://127.0.0.1:8000/api/courses/1/lessons
Header: Authorization: Bearer SEU_TOKEN_AQUI

Body:
{
  "title": "Nova Lição de Teste",
  "description": "Esta é uma lição criada através da API",
  "content": "Conteúdo completo da lição...",
  "video_url": "https://www.youtube.com/watch?v=exemplo123",
  "duration_minutes": 45,
  "is_free": false
}

✅ Status 201 (Created)
✅ Retorna a lição criada
✅ Campo 'order' foi definido automaticamente (próximo número)
✅ Mensagem: "Lição criada com sucesso"
```

#### 4.4 - Atualizar Lição
```
PUT http://127.0.0.1:8000/api/courses/1/lessons/1
Header: Authorization: Bearer SEU_TOKEN_AQUI

Body:
{
  "title": "Lição Atualizada via API",
  "description": "Descrição atualizada",
  "content": "Conteúdo atualizado...",
  "video_url": "https://www.youtube.com/watch?v=atualizado456",
  "duration_minutes": 60,
  "is_free": true
}

✅ Status 200
✅ Retorna a lição atualizada
✅ Mensagem: "Lição atualizada com sucesso"
```

#### 4.5 - Deletar Lição
```
DELETE http://127.0.0.1:8000/api/courses/1/lessons/152
Header: Authorization: Bearer SEU_TOKEN_AQUI

✅ Status 200
✅ Mensagem: "Lição deletada com sucesso"
✅ Lição foi soft deleted
```

---

### **PASSO 5: Testes de Validação** 🧪

#### 5.1 - Erro de Validação (Campos Obrigatórios)
```
POST http://127.0.0.1:8000/api/courses
Header: Authorization: Bearer SEU_TOKEN_AQUI

Body:
{
  "title": "",
  "description": "Teste"
}

✅ Status 422 (Unprocessable Entity)
✅ Retorna erros de validação em PORTUGUÊS
✅ Lista todos os campos com erro
```

**Exemplo de resposta:**
```json
{
  "message": "The title field is required. (and 5 more errors)",
  "errors": {
    "title": ["O campo título é obrigatório."],
    "category_id": ["O campo categoria é obrigatório."],
    "instructor": ["O campo instrutor é obrigatório."],
    "duration_hours": ["O campo duração em horas é obrigatório."],
    "price": ["O campo preço é obrigatório."],
    "level": ["O campo nível é obrigatório."]
  }
}
```

#### 5.2 - Acesso Não Autorizado (Sem Token)
```
GET http://127.0.0.1:8000/api/courses
(SEM o header Authorization)

✅ Status 401 (Unauthorized)
✅ Mensagem: "Token não fornecido" ou "Unauthenticated"
```

#### 5.3 - Recurso Não Encontrado
```
GET http://127.0.0.1:8000/api/courses/99999
Header: Authorization: Bearer SEU_TOKEN_AQUI

✅ Status 404 (Not Found)
✅ Mensagem: "Curso não encontrado"
```

---

## 📊 Checklist de Validação Final

Marque conforme testar:

### **Autenticação JWT:**
- [ ] Login retorna token válido
- [ ] Token funciona nas requisições protegidas
- [ ] Endpoint /me retorna usuário correto
- [ ] Refresh token gera novo token
- [ ] Logout invalida o token
- [ ] Requisições sem token retornam 401

### **CRUD de Courses:**
- [ ] Lista todos os cursos (paginado)
- [ ] Visualiza curso específico
- [ ] Cria novo curso
- [ ] Slug é gerado automaticamente
- [ ] Atualiza curso existente
- [ ] Deleta curso (soft delete)
- [ ] Validação de campos obrigatórios funciona
- [ ] Mensagens de erro em português

### **CRUD de Lessons:**
- [ ] Lista lições de um curso
- [ ] Visualiza lição específica
- [ ] Cria nova lição
- [ ] Campo 'order' é definido automaticamente
- [ ] Atualiza lição existente
- [ ] Deleta lição (soft delete)
- [ ] Validação de campos obrigatórios funciona
- [ ] Mensagens de erro em português

### **Arquitetura:**
- [ ] Request → Controller → Service → Repository → Resource (fluxo completo)
- [ ] Form Requests validando dados
- [ ] API Resources formatando respostas
- [ ] Repositories abstraindo acesso ao BD
- [ ] Services com lógica de negócio

### **Recursos Extras:**
- [ ] Relacionamentos entre models funcionando
- [ ] Soft deletes funcionando
- [ ] Paginação configurada
- [ ] CORS configurado
- [ ] Timestamps automáticos

---

## 🐛 Troubleshooting

### Erro: "Unauthenticated"
**Solução:** Certifique-se que:
1. Fez login e copiou o token
2. O token está no header: `Authorization: Bearer SEU_TOKEN`
3. O token não expirou (validade: 60 minutos)

### Erro: "Curso não encontrado"
**Solução:** 
1. Verifique se o ID do curso existe
2. Use `GET /api/courses` para ver os IDs disponíveis
3. Lembre-se que cursos deletados não aparecem

### Erro: "The given data was invalid"
**Solução:**
1. Revise os campos obrigatórios
2. Verifique os tipos de dados (string, integer, decimal)
3. Veja a mensagem de erro detalhada em `errors`

---

## 📸 Screenshots Recomendados para o Professor

Tire prints dessas telas:

1. ✅ **Login bem-sucedido** (com token)
2. ✅ **Lista de cursos** (paginação visível)
3. ✅ **Criação de curso** (status 201)
4. ✅ **Atualização de curso** (status 200)
5. ✅ **Erro de validação** (422 com mensagens em português)
6. ✅ **Erro de autenticação** (401 sem token)
7. ✅ **Lista de lições** de um curso
8. ✅ **Criação de lição** (com order automático)

---

## 🎓 Pontos Importantes para Destacar

✅ **Arquitetura em Camadas:**
- Request → Controller → Service → Repository → Resource
- Separação clara de responsabilidades
- Código organizado e manutenível

✅ **JWT Authentication:**
- Token seguro com expiração
- Middleware protegendo rotas
- Refresh token implementado

✅ **Validação Robusta:**
- Form Requests com regras claras
- Mensagens de erro em português
- Validação de relacionamentos (category_id, course_id)

✅ **CRUD Completo:**
- 2 entidades com CRUD completo (Courses e Lessons)
- Soft deletes implementado
- Relacionamentos funcionando

✅ **Boas Práticas:**
- API RESTful
- Códigos de status HTTP corretos
- Respostas padronizadas (success, data, message)
- Paginação implementada
- Slug automático
- Ordenação automática de lições

---

## ⏱️ Tempo Estimado de Testes

- **Testes Básicos:** 10-15 minutos
- **Testes Completos:** 30-40 minutos
- **Com Screenshots:** +15 minutos

**Total:** ~1 hora para validação completa

---

**🎯 Boa sorte na apresentação! Todos os requisitos foram implementados!** 🚀
