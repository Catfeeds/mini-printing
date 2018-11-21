<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function show(User $user, Order $order)
    {
        return $user->id === $order->user_id;
    }

    /**
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function destroy(User $user, Order $order)
    {
        $destroableStatus = [
            Order::WAIT_PAY,
            Order::WAIT_COMMENT,
            Order::FINISH,
        ];

        if (! in_array($order->status, $destroableStatus)) {
            return false;
        }

        return $user->id === $order->user_id;
    }

    /**
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function refund(User $user, Order $order)
    {
        $refundableStatus = [
            Order::WAIT_RECV,
            Order::WAIT_DELIVER,
            Order::WAIT_COMMENT
        ];

        if (! in_array($order->status, $refundableStatus) || $order->refund) {
            return false;
        }

        return $user->id === $order->user_id;
    }

    /**
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function express(User $user, Order $order)
    {
        if ($order->tracking_no == null || $order->delivered_at == null) {
            return false;
        }

        return $user->id === $order->user_id;
    }

    /**
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function comment(User $user, Order $order)
    {
        if ($order->status != Order::WAIT_COMMENT) {
            return false;
        }

        return $user->id === $order->user_id;
    }

    /**
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function confirm(User $user, Order $order)
    {
        if ($order->status != Order::WAIT_RECV) {
            return false;
        }

        return $user->id === $order->user_id;
    }
}
