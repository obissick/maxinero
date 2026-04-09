<?php

namespace App\Services;

use App\Models\Setting;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class MaxScaleClient
{
    private function buildClient(): Client
    {
        return new Client();
    }

    public function getApiInfo(): ?object
    {
        return DB::table('settings')
            ->select('id', 'api_url', 'username', 'password')
            ->where('user_id', Auth::id())
            ->where('selected', true)
            ->first();
    }

    private function setting(): object
    {
        $setting = $this->getApiInfo();

        if (! $setting) {
            throw new \RuntimeException('No MaxScale server selected. Please configure a connection in Settings.');
        }

        return $setting;
    }

    private function baseOptions(object $setting): array
    {
        return [
            'auth'    => [$setting->username, Crypt::decrypt($setting->password)],
            'verify'  => false,
            'timeout' => 5.0,
        ];
    }

    public function get(string $path): string
    {
        $setting = $this->setting();
        $response = $this->buildClient()->request('GET', $setting->api_url . $path, $this->baseOptions($setting));

        return $response->getBody()->getContents();
    }

    public function post(string $path, array $data): \Psr\Http\Message\ResponseInterface
    {
        $setting = $this->setting();
        $options = array_merge($this->baseOptions($setting), [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body'    => json_encode($data),
        ]);

        return $this->buildClient()->request('POST', $setting->api_url . $path, $options);
    }

    public function put(string $path): \Psr\Http\Message\ResponseInterface
    {
        $setting = $this->setting();

        return $this->buildClient()->put($setting->api_url . $path, $this->baseOptions($setting));
    }

    public function patch(string $path, array $data): \Psr\Http\Message\ResponseInterface
    {
        $setting = $this->setting();
        $options = array_merge($this->baseOptions($setting), [
            'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
            'body'    => json_encode($data),
        ]);

        return $this->buildClient()->patch($setting->api_url . $path, $options);
    }

    public function delete(string $path): \Psr\Http\Message\ResponseInterface
    {
        $setting = $this->setting();

        return $this->buildClient()->delete($setting->api_url . $path, $this->baseOptions($setting));
    }

    /**
     * Used by the ProcessStats job which iterates all settings (not scoped to Auth user).
     */
    public function getForSetting(Setting $setting, string $path): string
    {
        $options = [
            'auth'    => [$setting->username, Crypt::decrypt($setting->password)],
            'verify'  => false,
            'timeout' => 1.0,
        ];

        $response = $this->buildClient()->request('GET', $setting->api_url . $path, $options);

        return $response->getBody()->getContents();
    }
}
