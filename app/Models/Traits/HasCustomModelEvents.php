<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Models\Traits;

/**
 * Trait for adding method to trigger custom Model events
 *
 * @package \App\Models\Traits
 */
trait HasCustomModelEvents
{
    /**
     * @var array
     */
    public array $eventData = [];

    /**
     * Fire the given event for the model.
     *
     * @param string $event
     * @param bool   $halt
     * @param array  $data
     * @return mixed
     */
    public function triggerCustomModelEvent(string $event, bool $halt = true, array $data = []) : mixed
    {
        $this->eventData[$event] = $data;

        return parent::fireModelEvent($event, $halt);
    }


    /**
     * Get the event data by event
     *
     * @param string $event
     * @return array|null
     */
    public function getCustomModelEventData(string $event) : array|null
    {
        return array_key_exists($event, $this->eventData)
            ? $this->eventData[$event]
            : null;
    }
}
