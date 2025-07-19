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
  - Suporte a menu lateral com até **3 níveis**
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

## 📁 Estrutura de Diretórios

