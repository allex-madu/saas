<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<h1 align="center">Pão Com - Sistema de Gestão para Padarias</h1>

<p align="center">
  Um sistema robusto para controle administrativo de padarias, desenvolvido com Laravel, Vue.js, Tailwind CSS e Docker.
</p>

---

## 🧰 Tecnologias Utilizadas

- **Backend**: Laravel 12 + Sanctum + Spatie Permissions
- **Frontend**: Vue 3 + Pinia + Vue Router + Tailwind CSS
- **Template UI**: DashCode Vue (versão gratuita)
- **Ambiente**: Docker (PHP 8.3, MariaDB, Nginx, Adminer)
- **Banco de Dados**: MariaDB
- **ORM**: Eloquent com Seeders para dados iniciais

---

## 🔐 Funcionalidades de Segurança

- Autenticação com Laravel Sanctum (cookies e CSRF)
- Middleware `auth:sanctum` e `authorize` nos controllers
- Proteção de rotas no Vue Router com `meta.requiresAuth` e `meta.role`
- Filtros de menus baseados nas permissões do usuário
- Modo de Debug para visualização de todas as permissões (dev)

---

## 👤 Gestão de Usuários

- CRUD completo de usuários
- Atribuição de papéis (roles)
- Página de detalhes com layout unificado

## 🛡️ Gestão de Papéis (Roles)

- Criar, listar, editar e excluir papéis
- Atribuição de permissões via árvore colapsável com checkboxes
- Agrupamento por módulo (ex: "Usuários", "Produtos", etc.)

## ✅ Gestão de Permissões

- CRUD completo de permissões
- Organização hierárquica (módulo → subgrupo → ações)
- Botões "Expandir todos", "Marcar todos", "Limpar todos"
- Endpoint `/api/v1/admin/permissions/grouped` disponível

---

## 📊 Tabelas de Listagem

- Padronizadas com vue-good-table
- Busca com debounce
- Paginação
- Dropdown de ações: Visualizar, Editar, Excluir

---

## 📁 Estrutura do Projeto

```
my-project/
├── backend/                # Laravel 12
│   ├── routes/api.php      # API REST protegida
│   ├── app/Http/Controllers/Admin
│   ├── database/seeders    # Seeders de roles, users, permissions
│   └── ...
├── frontend/              # Vue 3 + Pinia + Tailwind
│   ├── views/auth/         # Login
│   ├── views/admin/roles/  # Roles CRUD
│   ├── views/admin/permissions/
│   ├── components/         # Cards, PermissionTree, EntityShowCard
│   ├── stores/             # Pinia Stores
│   └── router/routes.js    # Rotas com meta.role
├── docker/                # Nginx + PHP
└── docker-compose.yml     # Orquestração de serviços
```

---

## ⚙️ Modo de Debug de Permissões

Para habilitar o modo de debug no frontend (exibir todos os menus e rotas ignorando roles):

```env
# frontend/.env
VITE_DEBUG_PERMISSIONS=true
```

---

## 🗃️ Comandos Úteis

```bash
# Backend
cd backend
composer install
php artisan migrate --seed

# Frontend
cd frontend
npm install
npm run dev

# Docker
docker-compose up -d --build
```

---

## 📄 Licença

Este projeto utiliza o framework Laravel sob a licença [MIT](https://opensource.org/licenses/MIT).

---

## ✝️ Soli Deo Gloria