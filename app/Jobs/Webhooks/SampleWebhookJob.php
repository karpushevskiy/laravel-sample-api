<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Jobs\Webhooks;

/**
 * Sample Webhook Job
 *
 * @package \App\Jobs\Webhooks
 */
class SampleWebhookJob extends BaseWebhookJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $eventPayload = $this->webhookCall->payload;

        //
    }
}
