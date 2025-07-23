<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'description',
        'guard_name',
    ];

    public const PERMISSIONS = [
        [
            'name' => 'users.view',
            'description' => 'Visualizar usuários',
        ],
        [
            'name' => 'users.create',
            'description' => 'Criar usuários',
        ],
        [
            'name' => 'users.edit',
            'description' => 'Editar usuários',
        ],
        [
            'name' => 'users.delete',
            'description' => 'Excluir usuários',
        ],
        // você pode continuar com outras permissões...
    ];


   public static function treeStructure(): array
   {
      return [
      [
        'title' => 'Cadastros',
        'items' => [
            [
               'title' => 'Gerais',
               'items' => [
                  [
                     'title' => 'Centros de Custo',
                     'items' => [
                           ['name' => 'costcenters.view', 'description' => 'Visualizar'],
                           ['name' => 'costcenters.create', 'description' => 'Criar'],
                           ['name' => 'costcenters.edit', 'description' => 'Editar'],
                           ['name' => 'costcenters.delete', 'description' => 'Deletar'],
                     ]
                  ]
               ]
            ],
            [
               'title' => 'Financeiros',
               'items' => [
                  [
                     'title' => 'Contas Bancárias',
                     'items' => [
                           ['name' => 'accounts.view', 'description' => 'Visualizar'],
                           ['name' => 'accounts.create', 'description' => 'Criar'],
                           ['name' => 'accounts.edit', 'description' => 'Editar'],
                           ['name' => 'accounts.delete', 'description' => 'Deletar'],
                     ]
                  ]
               ]
            ],
            [
               'title' => 'Produtos',
               'items' => [
                  [
                     'title' => 'Grupos',
                     'items' => [
                           ['name' => 'groupproducts.view', 'description' => 'Visualizar'],
                           ['name' => 'groupproducts.create', 'description' => 'Criar'],
                           ['name' => 'groupproducts.edit', 'description' => 'Editar'],
                           ['name' => 'groupproducts.delete', 'description' => 'Deletar'],
                     ]
                  ],
                  [
                     'title' => 'Categorias',
                     'items' => [
                           ['name' => 'categoryproducts.view', 'description' => 'Visualizar'],
                           ['name' => 'categoryproducts.create', 'description' => 'Criar'],
                           ['name' => 'categoryproducts.edit', 'description' => 'Editar'],
                           ['name' => 'categoryproducts.delete', 'description' => 'Deletar'],
                     ]
                  ],
                  [
                     'title' => 'Classes',
                     'items' => [
                           ['name' => 'kinds.view', 'description' => 'Visualizar'],
                           ['name' => 'kinds.create', 'description' => 'Criar'],
                           ['name' => 'kinds.edit', 'description' => 'Editar'],
                           ['name' => 'kinds.delete', 'description' => 'Deletar'],
                     ]
                  ],
                  [
                     'title' => 'Produtos',
                     'items' => [
                           ['name' => 'products.view', 'description' => 'Visualizar'],
                           ['name' => 'products.create', 'description' => 'Criar'],
                           ['name' => 'products.edit', 'description' => 'Editar'],
                           ['name' => 'products.delete', 'description' => 'Deletar'],
                     ]
                  ],
                  [
                     'title' => 'Un. de Medida',
                     'items' => [
                           ['name' => 'measurements.view', 'description' => 'Visualizar'],
                           ['name' => 'measurements.create', 'description' => 'Criar'],
                           ['name' => 'measurements.edit', 'description' => 'Editar'],
                           ['name' => 'measurements.delete', 'description' => 'Deletar'],
                     ]
                  ]
               ]
            ],
            [
                'title' => 'Pessoas',
                'items' => [
                  [
                     'title' => 'Perfil Usuário',
                     'items' => [
                           ['name' => 'roles.view', 'description' => 'Visualizar'],
                           ['name' => 'roles.create', 'description' => 'Criar'],
                           ['name' => 'roles.edit', 'description' => 'Editar'],
                           ['name' => 'roles.delete', 'description' => 'Deletar'],
                     ]
                  ],
                  [
                     'title' => 'Proprietários',
                     'items' => [
                           ['name' => 'proprietaries.view', 'description' => 'Visualizar'],
                           ['name' => 'proprietaries.create', 'description' => 'Criar'],
                           ['name' => 'proprietaries.edit', 'description' => 'Editar'],
                           ['name' => 'proprietaries.delete', 'description' => 'Deletar'],
                     ]
                  ],
                  [
                     'title' => 'Funcionários',
                     'items' => [
                           ['name' => 'employees.view', 'description' => 'Visualizar'],
                           ['name' => 'employees.create', 'description' => 'Criar'],
                           ['name' => 'employees.edit', 'description' => 'Editar'],
                           ['name' => 'employees.delete', 'description' => 'Deletar'],
                     ]
                  ],
                  [
                     'title' => 'Fornecedores',
                     'items' => [
                           ['name' => 'providers.view', 'description' => 'Visualizar'],
                           ['name' => 'providers.create', 'description' => 'Criar'],
                           ['name' => 'providers.edit', 'description' => 'Editar'],
                           ['name' => 'providers.delete', 'description' => 'Deletar'],
                     ]
                  ],
                  [
                     'title' => 'Clientes',
                     'items' => [
                           ['name' => 'clients.view', 'description' => 'Visualizar'],
                           ['name' => 'clients.create', 'description' => 'Criar'],
                           ['name' => 'clients.edit', 'description' => 'Editar'],
                           ['name' => 'clients.delete', 'description' => 'Deletar'],
                     ]
                  ],
                  [
                     'title' => 'Usuários',
                     'items' => [
                           ['name' => 'users.view', 'description' => 'Visualizar'],
                           ['name' => 'users.create', 'description' => 'Criar'],
                              ['name' => 'users.edit', 'description' => 'Editar'],
                              ['name' => 'users.delete', 'description' => 'Deletar'],
                           ]
                     ]
                  ]
               ]
            ]
         ]
      ];

   }//end function
}//end class
