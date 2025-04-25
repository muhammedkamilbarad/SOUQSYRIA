<?php

namespace App\Policies;

use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdvertisementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Advertisement $advertisement): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasActiveSubscription();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Advertisement $advertisement): bool
    {
        return $user->id === $advertisement->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Advertisement $advertisement): bool
    {
        return $user->id === $advertisement->user_id || $user->hasPermission('delete_ad');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Advertisement $advertisement): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Advertisement $advertisement): bool
    {
        //
    }

    /**
     * Determine whether the user can deactivate the model.
     */
    public function deactivate(User $user, Advertisement $advertisement): bool
    {
        return $user->id === $advertisement->user_id
                && $advertisement->active_status === 'active'
                && $advertisement->ads_status === 'accepted';
    }

    /**
     * Determine whether the user can activate the model.
     */
    public function activate(User $user, Advertisement $advertisement): bool
    {
        return $user->id === $advertisement->user_id
                && $advertisement->active_status === 'inactive'
                && $advertisement->ads_status === 'accepted'
                && $user->hasActiveSubscription();
    }
}
