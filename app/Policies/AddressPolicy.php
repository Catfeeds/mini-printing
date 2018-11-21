<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function show(User $user, Address $address)
    {
        return $user->id === $address->user_id;
    }

    /**
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function destroy(User $user, Address $address)
    {
        return $user->id === $address->user_id;
    }

    public function update(User $user, Address $address)
    {
        return $user->id === $address->user_id;
    }
}
