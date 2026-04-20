<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->isStaff()) {
            return true;
        }

        return $ticket->belongsToUser($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->isStaff()) {
            return $ticket->isAssignedTo($user);
        }

        return $ticket->belongsToUser($user) && $ticket->isEditable();
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return false;
    }

    public function restore(User $user, Ticket $ticket): bool
    {
        return false;
    }

    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return false;
    }

    public function assign(User $user, Ticket $ticket): bool
    {
        return false;
    }

    public function changeStatus(User $user, Ticket $ticket): bool
    {
        if ($user->isStaff()) {
            return $ticket->isAssignedTo($user);
        }

        return false;
    }
}