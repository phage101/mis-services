<?php

namespace App\Policies;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->can('meetings.list');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Meeting $meeting)
    {
        if ($user->can('meetings.view')) {
            return $user->hasRole('Admin') || $meeting->requestor_id === $user->id;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->can('meetings.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Meeting $meeting)
    {
        if ($user->can('meetings.edit')) {
            return $user->hasRole('Admin') || ($meeting->requestor_id === $user->id && $meeting->status === 'pending');
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Meeting $meeting)
    {
        if ($user->can('meetings.delete')) {
            return $user->hasRole('Admin') || ($meeting->requestor_id === $user->id && $meeting->status === 'pending');
        }
        return false;
    }
}
