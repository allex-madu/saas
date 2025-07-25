<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Person;

class PersonPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('people.view');
    }

    public function view(User $user, Person $person): bool
    {
        return $user->can('people.view');
    }

    public function create(User $user): bool
    {
        return $user->can('people.create');
    }

    public function update(User $user, Person $person): bool
    {
        return $user->can('people.edit');
    }

    public function delete(User $user, Person $person): bool
    {
        return $user->can('people.delete');
    }
}
