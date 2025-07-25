<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

/**
 * Seeder responsável por popular o banco com as permissões definidas
 * de forma estruturada em árvore (hierarquia de módulos).
 *
 * A estrutura usada vem do método:
 *    App\Models\Permission::treeStructure()
 *
 * Esse método retorna um array aninhado representando:
 *    - Grupos (ex: "Cadastros", "Gerenciamento")
 *      - Seções (ex: "Pessoas")
 *        - Módulos (ex: "Usuários")
 *          - Permissões (ex: "users.view", "users.create", ...)
 */
class PermissionSeederTree extends Seeder
{
    /**
     * Método principal chamado ao executar:
     *   php artisan db:seed --class=PermissionSeederTree
     *
     * Ele carrega a estrutura e cria as permissões correspondentes.
     */
    public function run(): void
    {
        $structure = Permission::treeStructure(); // ← busca estrutura definida no model Permission

        if (!is_array($structure)) {
            $this->command->error('Estrutura de permissões inválida.');
            return;
        }

        $this->createPermissions($structure); // percorre e insere no banco
    }

    /**
     * Percorre a estrutura em até 4 níveis:
     * grupo → seção → módulo → permissões
     *
     * Para cada permissão encontrada (com 'name' e 'description'),
     * ela é criada no banco se ainda não existir.
     */
    private function createPermissions(array $structure): void
    {
        foreach ($structure as $group) {
            if (!isset($group['items']) || !is_array($group['items'])) continue;

            foreach ($group['items'] as $section) {
                if (!isset($section['items']) || !is_array($section['items'])) continue;

                foreach ($section['items'] as $module) {
                    if (!isset($module['items']) || !is_array($module['items'])) continue;

                    foreach ($module['items'] as $permission) {
                        if (!isset($permission['name']) || !isset($permission['description'])) continue;

                        // Cria a permissão se ainda não existir no banco
                        Permission::firstOrCreate(
                            ['name' => $permission['name'], 'guard_name' => 'web'],
                            ['description' => $permission['description']]
                        );
                    }
                }
            }
        }
    }
}
