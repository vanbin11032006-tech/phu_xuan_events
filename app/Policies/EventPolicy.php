<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        // Anyone, including guest, can view published event lists
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Event $event): bool
    {
        // Public can view if published
        if ($event->status === 'published') {
            return true;
        }

        // Only Admin or Organizer Owner can view if draft/cancelled
        return $user && ($user->isAdmin() || $user->id === $event->user_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Admin and Organizer can create
        return $user->isAdmin() || $user->isOrganizer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        // Admin or the owner organizer can update
        return $user->isAdmin() || $user->id === $event->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        // Admin can always delete
        if ($user->isAdmin()) {
            return true;
        }

        // Organizer owner can delete ONLY IF there are no registrations yet
        return $user->id === $event->user_id && $event->registrations()->count() === 0;
    }

    /**
     * Determine whether the user can approve registrations for the event.
     */
    public function approve(User $user): bool
    {
        // Only admin can approve registrations globally as per specification (M3.4: Admin, M3.3: Organizer view registrations)
        return $user->isAdmin();
    }
}
