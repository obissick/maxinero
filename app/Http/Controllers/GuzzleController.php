<?php

namespace App\Http\Controllers;

use App\Services\MaxScaleClient;

/**
 * @deprecated Inject MaxScaleClient directly. This class exists only during transition.
 */
class GuzzleController extends Controller
{
    public function __construct(protected MaxScaleClient $client)
    {
        $this->middleware('auth');
    }

    public function get_request(string $location): string
    {
        return $this->client->get($location);
    }

    public function post_request(array $data, string $location): \Psr\Http\Message\ResponseInterface
    {
        return $this->client->post($location, $data);
    }

    public function delete_request(string $location): \Psr\Http\Message\ResponseInterface
    {
        return $this->client->delete($location);
    }

    public function put_request(string $location): \Psr\Http\Message\ResponseInterface
    {
        return $this->client->put($location);
    }

    public function put_data(array $data, string $location): \Psr\Http\Message\ResponseInterface
    {
        return $this->client->patch($location, $data);
    }

    public function get_api_info(): ?object
    {
        return $this->client->getApiInfo();
    }
}
