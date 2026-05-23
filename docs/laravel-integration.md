# Laravel Integration Guide

This guide will show you how to integrate the Atlas Nexus Connector into your Laravel application using modern best practices.

## 1. Installation

Install the package via Composer:

```bash
composer require acamposm/atlas-nexus-connector
```

## 2. Configuration

First, add the connection details to your `.env` file:

```env
NEXUS_BASE_URL=https://your-nexus-instance.com
NEXUS_USERNAME=admin
NEXUS_PASSWORD=admin123
```

Next, add a new entry to your `config/services.php` file to keep your configuration organized:

```php
'nexus' => [
    'base_url' => env('NEXUS_BASE_URL'),
    'username' => env('NEXUS_USERNAME'),
    'password' => env('NEXUS_PASSWORD'),
],
```

## 3. Service Provider Binding

Register the `NexusClient` as a singleton in `app/Providers/AppServiceProvider.php`. This allows you to use dependency injection throughout your application.

```php
namespace App\Providers;

use Atlas\Connectors\Nexus\NexusClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NexusClient::class, function ($app) {
            $config = config('services.nexus');

            return new NexusClient(
                baseUrl: $config['base_url'],
                options: [
                    'auth' => [
                        $config['username'],
                        $config['password'],
                    ],
                ]
            );
        });
    }
}
```

## 4. Usage

Once the client is registered, you can inject it into your Controllers, Jobs, or Services.

```php
namespace App\Http\Controllers;

use Atlas\Connectors\Nexus\NexusClient;
use Illuminate\Http\JsonResponse;

class RepositoryController extends Controller
{
    public function __construct(
        private NexusClient $nexus
    ) {}

    /**
     * List all repositories from Nexus.
     */
    public function index(): JsonResponse
    {
        $repositories = $this->nexus->repositories()->list();

        return response()->json($repositories);
    }
}
```

## 5. Testing & Mocking

When writing tests for your application, you should avoid making real API calls to your Nexus instance. You can easily mock the `NexusClient` using Laravel's built-in mocking capabilities.

```php
use Atlas\Connectors\Nexus\NexusClient;
use Mockery\MockInterface;

public function test_it_can_list_repositories()
{
    $this->mock(NexusClient::class, function (MockInterface $mock) {
        $mock->shouldReceive('repositories->list')
            ->once()
            ->andReturn([
                ['name' => 'maven-releases', 'type' => 'hosted'],
                ['name' => 'maven-central', 'type' => 'proxy'],
            ]);
    });

    $response = $this->get('/repositories');

    $response->assertStatus(200)
        ->assertJsonCount(2);
}
```
