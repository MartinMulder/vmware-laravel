<?php

namespace MartinMulder\VMWare\Laravel;

use MartinMulder\VMWare\Laravel\Exceptions\ConfigNotFound;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Log;
use MartinMulder\VMWare\VcenterApi;

class VMWare extends VcenterApi
{
    //use Incidents, OperatorStats, Changes, Counts, Persons;

    /**
     * VMWare constructor.
     *
     * @param string $endpoint
     * @param int    $retries
     * @param array  $guzzleOptions
     */
    public function __construct($endpoint = 'https://vcenter.local/rest/', $retries = 5, $guzzleOptions = [])
    {
        $this->checkConfig();

        parent::__construct($this->endpointWithTrailingSlash(), $retries, $guzzleOptions);
        $this->login(
            config('vmware.application_username'),
            config('vmware.application_password')
        );
    }

    /**
     * Let the User know if they have forgotten to update their .env file.
     *
     * @throws ConfigNotFound
     */
    private function checkConfig()
    {
        foreach (config('vmware') as $key => $config) {
            if ($config === null) {
                throw new ConfigNotFound("You need to set the config for env('vmware.".$key."')", 400);
            }
            if ($config === '') {
                throw new ConfigNotFound("It seems unlikely that the env('vmware.".$key."') should be an empty string!? I don't work with people like that!",
                    400);
            }
        }
    }

    /**
     * Let's hold the end users hands,
     * and if they fall at the first hurdle,
     * we won't say a thing!
     *
     * @return string
     */
    private function endpointWithTrailingSlash(): string
    {
        return rtrim(config('vmware.endpoint'), '/\\').'/';
    }

    /**
     * Shorthand function to create requests with JSON body and query parameters.
     * @param $method
     * @param string $uri
     * @param array $json
     * @param array $query
     * @param array $options
     * @param bool $decode JSON decode response body (defaults to true).
     * @return mixed|ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($method, $uri = '', array $json = [], array $query = [], array $options = [], $decode = true)
    {
        try {
            $response = $this->client->request($method, $this->module . '/' . $uri, array_merge([
                'json' => $json,
                'query' => $query,
            ], $options));

            return $decode ? json_decode((string) $response->getBody(), true) : (string) $response->getBody();
        } catch (ServerException $exception) {
            Log::error('VMWare Server Exception', [
                'status' => $exception->getCode(),
                'method' => $method,
                'uri' => $uri,
            ]);

            return $exception;
        }
    }
}
