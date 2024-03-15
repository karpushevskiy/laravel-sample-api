<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Events\Broadcast;

use Illuminate\Broadcasting\Channel;

/**
 * User Verified Email Event
 *
 * @package \App\Events\Broadcast
 */
class UserVerifiedEmailEvent extends BaseUserBroadcastEvent
{
    /**
     * Create a new event instance.
     *
     * @param mixed $data
     * @return void
     */
    public function __construct(mixed $data)
    {
        parent::__construct($data);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn() : Channel|array
    {
        return parent::broadcastOn();
    }
}
