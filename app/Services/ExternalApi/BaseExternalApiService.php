<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\ExternalApi;

use App\Exceptions\Custom\ServiceNotConfiguredException;
use App\Http\Traits\ConsumesExternalServices;

/**
 * Service for working with Bet365 API
 *
 * @package App\Services
 */
class BaseExternalApiService
{
    use ConsumesExternalServices;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * BaseExternalApiService constructor.
     *
     * @param string $serviceConfigKey
     * @param string $serviceName
     * @throws \App\Exceptions\Custom\ServiceNotConfiguredException
     * @return void
     */
    public function __construct(string $serviceConfigKey, string $serviceName)
    {
        /*
         * Prepare configs
         */
        $config = config("services.{$serviceConfigKey}");

        if (!$config) {
            throw new ServiceNotConfiguredException($serviceName);
        }

        $this->config  = $config;
        $this->baseUri = $config['base_uri'];
    }

    /**
     * @param array $queryParams
     * @param array $formParams
     * @param array $headers
     */
    protected function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        //
    }

    /**
     * @param mixed $response
     * @return mixed
     */
    protected function decodeResponse($response)
    {
        return json_decode($response, true);
    }
}
