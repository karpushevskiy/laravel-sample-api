<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Events\Broadcast;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Base User Broadcast Event
 *
 * @package \App\Events\Broadcast
 */
abstract class BaseUserBroadcastEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var mixed
     */
    public $data;

    /**
     * Create a new event instance.
     *
     * @param mixed $data
     * @return void
     */
    public function __construct(mixed $data)
    {
        $this->data = $data;

//        $this->dontBroadcastToCurrentUser();
    }

    /**
     * @return Channel|array|null
     */
    public function broadcastOn() : Channel|array|null
    {
        return !empty($this->data['user_id'])
            ? new PrivateChannel('App.Models.User.' . $this->data['user_id'])
            : null;
    }
}
