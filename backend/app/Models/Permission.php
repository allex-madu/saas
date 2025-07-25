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


   public static function treeStructure(): array
   {
      return [
         [
            'title' => 'Cadastros',
            'items' => [
               [
                  'title' => 'Pessoas',
                  'items' => [
                     [
                        'title' => 'Usuários',
                        'items' => [
                              ['name' => 'users.view', 'description' => 'Visualizar'],
                              ['name' => 'users.create', 'description' => 'Criar'],
                              ['name' => 'users.edit', 'description' => 'Editar'],
                              ['name' => 'users.delete', 'description' => 'Deletar'],
                        ]
                     ],
                  ]
               ],
               

            ]
            ],

            [
            'title' => 'Gerenciamento',
            'items' => [
               [
                  'title' => 'Atribuições',
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
