<?php

namespace App\Policies;

use App\Models\Signalement;
use App\Models\User;

class SignalementPolicy
{
    /**
     * Citizen and Agent can access signalements list
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['citizen', 'agent']);
    }


    /**
     * User can view his own signalement
     * Agent can view all signalements
     */
    public function view(User $user, Signalement $signalement): bool
    {
        if ($user->role === 'agent') {
            return true;
        }

        return $user->id === $signalement->user_id;
    }


    /**
     * Only citizen can create signalement
     */
    public function create(User $user): bool
    {
        return $user->role === 'citizen';
    }


    /**
     * Only agent can update signalement
     */
    public function update(User $user, Signalement $signalement): bool
    {
        return $user->role === 'agent';
    }

    /**
 * Only agent can change signalement status
 */
public function updateStatus(User $user, Signalement $signalement): bool
{
    return $user->role === 'agent';
}

    
    public function delete(User $user, Signalement $signalement): bool
    {
        return $user->role === 'citizen'
            && $user->id === $signalement->user_id;
    }


    public function restore(User $user, Signalement $signalement): bool
    {
        return false;
    }


    public function forceDelete(User $user, Signalement $signalement): bool
    {
        return false;
    }
}