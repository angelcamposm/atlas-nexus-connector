# Laravel Integration Tutorial

This tutorial will guide you through integrating the `atlas-connectors-nexus` package into a Laravel project.

## 1. Installation

First, install the package via Composer:

```bash
composer require acamposm/atlas-nexus-connector
```

## 2. Configuration

Add the Nexus configuration to your `config/services.php` file:

```php
'nexus' => [
    'base_url' => env('NEXUS_BASE_URL', 'https://nexus.example.com'),
    'username' => env('NEXUS_USERNAME'),
    'password' => env('NEXUS_PASSWORD'),
],
```

Then, add these variables to your `.env` file:

```env
NEXUS_BASE_URL=https://your-nexus-instance.com
NEXUS_USERNAME=your-username
NEXUS_PASSWORD=your-password
```

## 3. Service Provider Binding

To use the `NexusClient` via dependency injection, bind it in your `app/Providers/AppServiceProvider.php`:

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
                    'auth' => [$config['username'], $config['password']],
                ]
            );
        });
    }
}
```

## 4. Usage in Controllers

Now you can inject the `NexusClient` into your controllers:

```php
namespace App\Http\Controllers;

use Atlas\Connectors\Nexus\NexusClient;
use Illuminate\Http\JsonResponse;

class NexusController extends Controller
{
    public function __construct(
        protected NexusClient $nexus
    ) {}

    /**
     * List all repositories.
     */
    public function index(): JsonResponse
    {
        try {
            $repositories = $this->nexus->repositories()->list();
            
            return response()->json($repositories);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get details for a specific asset.
     */
    public function showAsset(string $id): JsonResponse
    {
        try {
            $asset = $this->nexus->assets()->get($id);
            
            return response()->json($asset);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
```

## 5. Handling Exceptions

The package provides specific exceptions that you can catch for more granular error handling:

```php
use Atlas\Connectors\Nexus\Exceptions\AuthenticationException;
use Atlas\Connectors\Nexus\Exceptions\ApiException;

try {
    $this->nexus->system()->status();
} catch (AuthenticationException $e) {
    // Handle invalid credentials
} catch (ApiException $e) {
    // Handle API errors (e.g., 404, 500)
}
```

## 6. Real-time Status Check

You can also use it in a health check or dashboard:

```php
public function health()
{
    return response()->json([
        'nexus' => $this->nexus->system()->status() === 'OK',
    ]);
}
```
