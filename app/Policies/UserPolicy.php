<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function edit(User $viewer, User $subject): bool
    {
        return $viewer->can('edit-users') || $viewer->id === $subject->id;
    }

    public function view(User $viewer, User $subject): bool
    {
        return $viewer->can('view-users') || $viewer->id === $subject->id;
    }

    public function update(User $viewer, User $subject): bool
    {
        return $viewer->can('edit-users') || $viewer->id === $subject->id;
    }
}
