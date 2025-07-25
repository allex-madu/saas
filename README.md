# 🥖 Pão Com – Sistema de Gestão para Padarias

Sistema de gestão completo e modular voltado para o segmento de padarias, com frontend desacoplado e backend robusto em Laravel.

---

## 🧱 Arquitetura do Projeto

### Frontend

- **Framework:** Vue 3
- **Gerenciador de estado:** Pinia
- **Roteamento:** Vue Router
- **Estilo:** Tailwind CSS
- **Template:** [DashCode](https://codedthemes.com/item/vue-dashcode/) (customizado)
- **Componentes reutilizáveis:**  
  - `InputGroup`, `VueSelect`, `Combobox`, entre outros
- **Sidebar dinâmica:**
  - Renderiza menus com base em permissões (papel do usuário)
  - Suporte a submenus expansíveis ao clique
- **Controle de acesso:**
  - Baseado em `meta.role` nas rotas
  - Navegação e visibilidade de menus controladas por `user.role`

### Backend

- **Framework:** Laravel 12
- **API RESTful:** prefixo `/api/v1`
- **Autenticação:** Laravel Sanctum
- **Controle de acesso:**  
  - **Spatie Roles & Permissions**
  - Papéis e permissões gerenciáveis pelo painel administrativo
- **Multi-tenancy:** (em desenvolvimento)
  - Usuários vinculados a padarias
  - Isolamento de dados por padaria (escopos e filtros dinâmicos)

---

## 🔐 Autenticação e Autorização

- Login via Sanctum com CSRF token
- Sessão persistida via cookies (`SESSION_DRIVER=database`)
- Autenticação protegida por middleware `auth` (Laravel) e `authStore` (Pinia)
- Navegação protegida por papel (role-based navigation)
- Atribuição de permissões e papéis diretamente pela interface administrativa

---

## ⚙️ Funcionalidades em Andamento

- Cadastro de usuários vinculados a pessoas existentes
- Busca de pessoas com **debounce** (autocomplete reativo)
- Atribuição de **papéis (roles)** ao criar usuário
- Menu lateral com submenus expandíveis **somente ao clique**
- Melhorias no fluxo de criação:
  - Validações em tempo real
  - Tratamento de erros de API
  - Feedback com `toast.success` e `toast.error`

---

## 📦 Tecnologias Utilizadas

- **Vue 3**, **Pinia**, **Vue Router**, **Tailwind CSS**
- **Laravel 12**, **Sanctum**, **Spatie Permissions**
- **Docker** (ambiente de desenvolvimento com Nginx, PHP 8.3, MariaDB)

---


# Segurança Frontend + Backend – Sessão Recapitulativa

## ✅ Proteções Implementadas no Projeto Pão Com

### 🔐 Backend (Laravel 12)
- **Middleware Sanctum ativo** para autenticação de rotas `/admin`:
  ```php
  Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
      // Rotas administrativas
  });
  ```

- **Proteção por Roles (Spatie)**:
  - Comentado temporariamente para testes:
    ```php
    // Route::middleware(['auth:sanctum', 'role:admin|super-admin'])->prefix('admin')->group(...);
    ```

- **Uso de `authorize()` nos Controllers**:
  - Aplicado para proteger métodos como `index()` com base em Policies.
  - Exemplo:
    ```php
    $this->authorize('viewAny', Role::class);
    ```

- **Gate::policy() ou AuthServiceProvider**:
  - Política de autorização registrada corretamente para `RolePolicy`.

---

### 🛡️ Frontend (Vue 3 + Pinia)
- **Proteção por papel (meta.role)** nas rotas Vue Router:
  ```js
  meta: { requiresAuth: true, role: ['admin', 'super-admin'] }
  ```

- **Middleware `auth.js` personalizado**:
  - Busca `auth.user.roles` e verifica se o usuário tem o papel necessário.
  - Se não tiver, redireciona para `home`.

- **Links escondidos por função**:
  ```vue
  <RouterLink v-if="auth.hasRole(['admin', 'super-admin'])" ... />
  ```

---

### 🧪 Modo Debug de Permissões (Frontend)
- **Botão `Debug ON` exibido apenas em `import.meta.env.DEV`**
- **Flag `authStore.debugPermissions`** ativa exibição de links mesmo sem papel.
- **Utilizado para testar `authorize()` no backend sem bloqueios no frontend.**

---

### 🖼️ Fluxo de Segurança (Resumo Visual)
1. Frontend protege visualmente links e rotas (`meta.role` + `v-if`).
2. Backend protege endpoints com `authorize()` + `Policies`.
3. Middleware Laravel (auth + role) pode proteger rotas REST inteiras.
4. Em modo `debugPermissions`, frontend mostra tudo, mas backend ainda bloqueia.

---

### ✅ Testes Realizados
- Login com usuários sem role: links escondidos no frontend e erro 403 no backend.
- Debug ativado: links aparecem, mas backend nega se não tiver role/política.
- Teste de `authorize` com usuário sem permissão mostra:
  ```json
  { "message": "This action is unauthorized." }
  ```

---

### 📄 Sugestão para `.env` de desenvolvimento:
```env
VITE_DEBUG_PERMISSIONS=true
APP_ENV=local
```

---

**Glória a Deus! 🙌 Sistema seguro com proteção dupla e modo debug funcional!**


