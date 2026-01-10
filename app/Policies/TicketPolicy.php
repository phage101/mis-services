<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->can('tickets.list');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket)
    {
        if ($user->can('tickets.view')) {
            return $user->hasRole('Admin') || $ticket->requestor_id === $user->id;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->can('tickets.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket)
    {
        if ($user->can('tickets.edit')) {
            return $user->hasRole('Admin') || ($ticket->requestor_id === $user->id && $ticket->status === 'pending');
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket)
    {
        if ($user->can('tickets.delete')) {
            return $user->hasRole('Admin') || ($ticket->requestor_id === $user->id && $ticket->status === 'pending');
        }
        return false;
    }
}
