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
- **Multi-tenancy:** em desenvolvimento
  - Usuários vinculados a padarias (`bakery_id`)
  - Isolamento de dados por padaria via escopo global (`BackeryScope`)

---

## 🔐 Autenticação e Autorização

- Login via Sanctum com CSRF token
- Sessão persistida via cookies (`SESSION_DRIVER=database`)
- Autenticação protegida por middleware `auth` (Laravel) e `authStore` (Pinia)
- Navegação protegida por papel (role-based navigation)
- Atribuição de permissões e papéis diretamente pela interface administrativa

---

## 🏢 Multi-Tenancy e Controle por Padaria

### 🧩 Visão Geral

O sistema **Pão Com** é um SaaS multi-tenant onde **cada padaria tem seus próprios usuários e dados isolados**.

- O **super-admin (você)** é o único com acesso a todas as padarias.
- Cada padaria tem um **usuário admin**, que gerencia exclusivamente sua própria operação.
- Todos os registros (usuários, produtos, pedidos, etc.) são automaticamente vinculados à `bakery_id`.

---

### 🏗️ Fluxo de Criação de Padaria

1. O super-admin cria uma nova **padaria**.
2. Um **usuário admin** é criado junto, com papel `admin` e vinculado à nova padaria.
3. Esse usuário será o “pai” de todos os dados daquela padaria: ele poderá criar outros usuários, produtos, etc.

---

### 🛡️ Isolamento e Segurança

- Todos os modelos usam escopos (`BackeryScope`) para restringir acesso à padaria do usuário.
- Apenas o `super-admin` pode ver e gerenciar **todas** as padarias.
- O frontend esconde menus e rotas de forma dinâmica com base no papel do usuário (`meta.role`, `v-if`).
- Dados são protegidos no backend por políticas e escopos globais.

---

### 🔄 Exemplo de Herança de Dados

| Entidade     | Ação                                     | `bakery_id` atribuído? |
|--------------|------------------------------------------|--------------------------|
| Usuário      | Criado pelo admin da padaria             | ✅ Sim                   |
| Produto      | Criado dentro da padaria                 | ✅ Sim                   |
| Pedido       | Realizado por funcionário                | ✅ Sim                   |
| Pessoa       | Associada ao cadastro de cliente/usuário | ✅ Sim                   |

---

### 🖼️ Diagrama: Fluxo de Criação de Padaria + Admin

```mermaid
flowchart TD
    SA[Super Admin (você)] -->|Cria| P(Padaria)
    P -->|Gera| A[Usuário Admin]
    A -->|Cria| U1[Usuário Funcionário]
    A -->|Cadastra| Pr[Produto]
    A -->|Gera| Pe[Pedido]
    U1 -->|Opera| Pe

    classDef green fill:#dcfce7,stroke:#16a34a,color:#065f46;
    class SA,P,A,U1,Pr,Pe green;
