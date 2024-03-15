<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Services\ExternalApi;

/**
 * Service for working with Sample API
 *
 * @link
 * @package App\Services
 */
class SampleApiService extends BaseExternalApiService
{
    /**
     * SampleApiService constructor.
     *
     * @throws \App\Exceptions\Custom\ServiceNotConfiguredException
     * @return void
     */
    public function __construct()
    {
        parent::__construct('sample_api', 'Sample API Service');
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
     * @return mixed
     */
    public function getTestData()
    {
        return $this->makeRequest(
            'GET',
            '/test',
            [],
            [],
            [],
            true,
        );
    }
}
