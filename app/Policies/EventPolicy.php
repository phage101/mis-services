<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return $user->can('events.list');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event)
    {
        return $user->can('events.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->can('events.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event)
    {
        // Only Admin or Organizer can edit events
        if ($user->can('events.edit')) {
            return $user->hasRole('Admin') || $event->organizer_id === $user->id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event)
    {
        // Only Admin or Organizer can delete events
        if ($user->can('events.delete')) {
            return $user->hasRole('Admin') || $event->organizer_id === $user->id;
        }
        return false;
    }

    /**
     * Determine whether the user can manage attendance.
     */
    public function manageAttendance(User $user, Event $event)
    {
        if ($user->can('events.attendance')) {
            return $user->hasRole('Admin') || $event->organizer_id === $user->id;
        }
        return false;
    }

    /**
     * Determine whether the user can view the registration form preview.
     */
    public function viewRegistrationForm(User $user, Event $event)
    {
        return $user->hasRole('Admin') || $event->organizer_id === $user->id;
    }

    /**
     * Determine whether the user can view the registered participants list.
     */
    public function viewParticipants(User $user, Event $event)
    {
        return $user->hasRole('Admin') || $event->organizer_id === $user->id;
    }
}
