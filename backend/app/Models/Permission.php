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


    // Pode estar no mesmo model, em outro método, ou até um service
    public static function treeStructure(): array
    {
        return [
            [
                'title' => 'Usuários',
                'items' => [
                    ['name' => 'users.view', 'description' => 'Visualizar usuários'],
                    ['name' => 'users.create', 'description' => 'Criar usuários'],
                    ['name' => 'users.edit', 'description' => 'Editar usuários'],
                    ['name' => 'users.delete', 'description' => 'Excluir usuários'],
                ],
            ],
            // ... outros grupos
        ];
    }

}
