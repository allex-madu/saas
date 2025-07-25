<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
   protected $guard_name = 'web'; 
   
   protected $fillable = [
      'name',
      'description',
      'guard_name',
   ];

   //  public const PERMISSIONS = [
   //      [
   //          'name' => 'users.view',
   //          'description' => 'Visualizar usuários',
   //      ],
   //      [
   //          'name' => 'users.create',
   //          'description' => 'Criar usuários',
   //      ],
   //      [
   //          'name' => 'users.edit',
   //          'description' => 'Editar usuários',
   //      ],
   //      [
   //          'name' => 'users.delete',
   //          'description' => 'Excluir usuários',
   //      ],
   //      // você pode continuar com outras permissões...
   //  ];


   public static function treeStructure(): array
   {
      return [
         [
               'title' => 'Gerenciamento',
               'items' => [
                  [
                     'title' => 'Papéis',
                     'items' => [
                           ['name' => 'roles.view', 'description' => 'Visualizar'],
                           ['name' => 'roles.create', 'description' => 'Criar'],
                           ['name' => 'roles.edit', 'description' => 'Editar'],
                           ['name' => 'roles.delete', 'description' => 'Deletar'],
                     ]
                  ],
                  [
                     'title' => 'Permissões',
                     'items' => [
                           ['name' => 'permissions.view', 'description' => 'Visualizar'],
                           ['name' => 'permissions.create', 'description' => 'Criar'],
                           ['name' => 'permissions.edit', 'description' => 'Editar'],
                           ['name' => 'permissions.delete', 'description' => 'Deletar'],
                     ]
                  ],
               ]
         ]
      ];
   }// end function
}//end class
