
# Elryad's Backend Initial Project
This project serves as the foundation for future robust backend projects developed using the Laravel PHP framework. It incorporates essential features and components to provide a solid starting point.
## Requirements

- Local server, e.g., XAMPP, WAMP, Laragon (Laragon is preferred).

- PHP 8.2 or greater (Required for Laravel 11).

## Installation

- Clone the project:

```bash
  git clone https://github.com/elryad-web/initial-backend-project.git
```

- Install composer packages:

```bash
  composer install
```

- Setup your environment:

Rename `.env.example` file to `.env`, then generate your Laravel app key.

```bash
  php artisan key:generate
```

- Generate new JWT secret key:

```bash
  php artisan jwt:secret
```

- Migrate and seed database:

```bash
  php artisan migrate --seed
```

- Link your storage folder with public folder:

```bash
  php artisan storage:link
```

- Clear cache:

```bash
  php artisan optimize:clear
```

## Documentation
This initial project serves as a starting point to save time in future backend projects. It implements an up-to-date structure to handle every aspect of your project, including Service Pattern, Repository Pattern, a pre-built and customized AdminLTE dashboard with common components, along with traits and helper files to streamline routine tasks. Each new implementation is explained in detail in this documentation, covering any files that differ from typical Laravel projects.
### Service Pattern:
In the app/Http/Services folder, you'll find classes with the same structure as controllers. This is where you can write your business logic in small, separated methods to be called from controllers.
#### Example:
If we have an AuthController with methods like signUp(), signIn(), signOut(), and refreshToken(), we create routes for them. The logic of these methods should be written inside a service class, e.g., `AuthService`.

`AuthService` will have the same methods of `AuthController` but with the required business logic, then `AuthService` will be injected to `AuthController` to make its methods accessible.

**AuthController**
```php
<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\SignInRequest;
use App\Http\Requests\Api\V1\Auth\SignUpRequest;
use App\Http\Services\Api\V1\Auth\AuthService;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $auth,
    )
    {
    }

    public function signUp(SignUpRequest $request) {
        return $this->auth->signUp($request);
    }

    public function signIn(SignInRequest $request) {
        return $this->auth->signIn($request);
    }

    public function signOut()
    {
        return $this->auth->signOut();
    }

    public function whatIsMyPlatform()
    {
        return $this->auth->whatIsMyPlatform();
    }
}

```
**AuthService**

```php
<?php

namespace App\Http\Services\Api\V1\Auth;

use App\Http\Helpers\Response;use App\Http\Requests\Api\V1\Auth\SignInRequest;
use App\Http\Requests\Api\V1\Auth\SignUpRequest;
use App\Http\Resources\V1\User\UserResource;
use App\Repository\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

abstract class AuthService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    )
    {
    }

    public function signUp(SignUpRequest $request) {
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $user = $this->userRepository->create($data);

            DB::commit();
            return Response::success(message: __('messages.created successfully'), data: new UserResource($user, false));
        } catch (Exception $e) {
            DB::rollBack();
//            dd($e);
            return Response::fail(message: __('messages.Something went wrong'));
        }
    }

    public function signIn(SignInRequest $request) {
        $credentials = $request->only('email', 'password');
        $token = auth('api')->attempt($credentials);
        if ($token) {
            return Response::success(message: __('messages.Successfully authenticated'), data: new UserResource(auth('api')->user(), true));
        }

        return Response::fail(status: 401, message: __('messages.wrong credentials'));
    }

    public function signOut() {
        auth('api')->logout();
        return Response::success(message: __('messages.Successfully loggedOut'));
    }

}

```

**Note:** You can make service class for other service class as a helper, in an endless way as you want.

### Controllers:
Controllers are base classes in the MVC pattern. In this structure, controllers act as mediators between routes and service classes. They are not meant for business logic; their role is to pass the called request to the right service method.
- Controllers are not a place to any peace of business logic code.
- Controllers' job in this structure is to pass the called request to the right service method. So it's only a mediator between the routes and the service classes.

### Repository Pattern:
The Repository Design Pattern acts as a middleman between the application’s data access layer and the rest of the application. It isolates data persistence logic, allowing developers to switch between different data sources without affecting the codebase. This pattern is already implemented in the project, providing code organization, reusability, testability, and flexibility.

In Laravel, repositories are often used to encapsulate database queries and provide an extra layer of abstraction. This separation of concerns improves code maintainability, testability, and scalability.

#### Benefits of Using the Repository Design Pattern:

1. Code Organization: By employing the Repository Design Pattern, you can neatly organize your data access logic in a separate layer, making your codebase cleaner and easier to manage.

2. Code Reusability: Repositories facilitate code reuse since data access logic is centralized. This means you can reuse the same repository methods in multiple parts of your application.

3. Testability: With repositories, you can easily mock data access during unit testing, promoting more reliable and efficient testing practices.

4. Flexibility: Repositories allow you to switch between different data sources, such as databases or external APIs, without altering your application’s business logic.

#### Quick Implementation:

The pattern is already implemented in this project and ready to use!

You have a base `Repository` class and `RepositoryInterface` interface. The `Repository` implements `RepositoryInterface`. Both of them have the basic CRUD queries which you will mostly need.

So once you created a model, you have to create two other files, a repository interface and a repository class; with the same convention naming.

After that, you will have to bind both of them in `app/Providers/RepositoryServiceProvider`. This file is the place where we will register and bind every RepositoryInterface with its Repository.

#### Example:
If we have `User` model, we will go to `app/Repository` and create an interface called `UserRepositoryInterface` which extends `RepositoryInterface`, then we will go inside `Eloquent` folder and create `UserRepository` which extends `Repository` and implements `UserRepositoryInterface`. This is already done to be a reference to you.

In the repository class you will have to inject the model which you are dealing with, to be an accessible object you can use insdie your repository.

So each method is presenting a query with your model. In case you want to make more complex queries, e.g., for `User` model, you will have to create a method insdie `UserRepository` contains your customized query, and implement it insdie `UserRepositoryInterface`. Example:

**UserRepositoryInterface**
```php
<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends Repository implements UserRepositoryInterface
{
    protected Model $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
```

**UserRepository**
```php
<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends Repository implements UserRepositoryInterface
{
    protected Model $model;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getActiveUsers()
    {
        // Your complex query here ...
        return $this->model::query()->where('is_active', true);
    }
}
```

**UserService**
```php
<?php

namespace App\Http\Services\Dashboard\User;

use App\Repository\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    )
    {
    }

    public function index()
    {
        $users = $this->userRepository->paginate(25);
        return view('dashboard.site.users.index', compact('users'));
    }

    // ...
    // ...
    // ...
    
    public function showActiveUsers()
    {
        $users = $this->userRepository->getActiveUsers(); // here is the query which we created
        
        // do something with $users
        // ...
        // ...
        // return ...
    }
}
```

**Note:** By user repository pattern, you will not make queries using models directly at all. This pattern was implemented to make dealing with data source (database) more cleaner, flexible and scalable.

### Separating Different Platforms Logic and Responses:
When you take a look at this path `app/Http/Services/Api/V1/` and go inside `Auth` folder for example, you will see three files of classes: `AuthService`, `AuthWebService` and `AuthMobileService` classes. Also you will see that `AuthService` is an abstracted class and both other classes are extending `AuthService` class.

Here we used the power of abstraction principle!

So when we build an API for our Laravel application with two different platforms (or more), we can easily separate the logic of these two (or more) platform with the one same controller.

This approach was handled and managed thankfully by `app/Providers/PlatformServiceProvider`

**PlatformServiceProvider**
```php
<?php

namespace App\Providers;

use App\Http\Services\Api\V1\Auth\AuthMobileService;
use App\Http\Services\Api\V1\Auth\AuthService;
use App\Http\Services\Api\V1\Auth\AuthWebService;
use Illuminate\Support\ServiceProvider;

class PlatformServiceProvider extends ServiceProvider
{
    private const VERSIONS = [1];
    private const PLATFORMS = ['website', 'mobile'];
    private const DEFAULT_VERSION = 1;
    private const DEFAULT_PLATFORM = 'website';
    private const SERVICES = [
        1 => [
            AuthService::class => [
                AuthWebService::class,
                AuthMobileService::class
            ]
        ],
    ];
    private ?int $version;
    private ?string $platform;

    public function __construct($app)
    {
        parent::__construct($app);

        foreach (self::VERSIONS as $version) {
            foreach (self::PLATFORMS as $platform) {
                $pattern = app()->isProduction()
                    ? "v$version/$platform/*"
                    : "api/v$version/$platform/*";

                if (request()->is($pattern)) {
                    $this->version = $version;
                    $this->platform = $platform;
                    return;
                }
            }
        }

        $this->version = self::DEFAULT_VERSION;
        $this->platform = self::DEFAULT_PLATFORM;
    }

    private function getTargetService(array $services)
    {
        foreach ($services as $service) {
            if ($service::platform() == $this->platform) {
                return $service;
            }
        }

        return $services[0];
    }

    private function initiate(): void
    {
        $services = self::SERVICES[$this->version] ?? [];

        foreach ($services as $abstractService => $targetServices) {
            $this->app->singleton($abstractService, $this->getTargetService($targetServices));
        }
    }

    public function register(): void
    {
        $this->initiate();
    }

    public function boot(): void
    {
        //
    }
}
```

#### Quick Explantion:
This provider was made to handle and manage all service classes which was made to be for different platforms.
It starts with defining the API versions in `VERSION` constant, defining platforms in `PLATFORMS` constant and defining our service classes which will be included in this approach in `SERVICES` constant, then the provider starts to collect the right version and the right service classes depending on requested route. Finally, it binds the abstracted service class with the right platform service to be initiatable in our Laravel application by pointing to the right platform service.

So in `SERVICES` constant, we make a key of abstracted service class and an array value of platform services.

#### Example:
In `routes/api/v1/website.php` and `routes/api/v1/mobile.php` files, you can find that there is a route called `what-is-my-platform` points to a method called `whatIsMyPlatform` inside `AuthController`.

**routes/api/v1/website.php**
```php
<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
    Route::group(['prefix' => 'sign'], function () {
        Route::post('in', 'signIn');
        Route::post('up', 'signUp');
        Route::post('out', 'signOut');
    });
    Route::get('what-is-my-platform', 'whatIsMyPlatform'); // returns 'platform: website!'
});

```

**routes/api/v1/mobile.php**
```php
<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
    Route::group(['prefix' => 'sign'], function () {
        Route::post('in', 'signIn');
        Route::post('up', 'signUp');
        Route::post('out', 'signOut');
    });
    Route::get('what-is-my-platform', 'whatIsMyPlatform'); // returns 'platform: mobile!'
});

```

**AuthController**
```php
<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\SignInRequest;
use App\Http\Requests\Api\V1\Auth\SignUpRequest;
use App\Http\Services\Api\V1\Auth\AuthService;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $auth,
    )
    {
    }

    // ...
    // ...
    // ...

    public function whatIsMyPlatform()
    {
        return $this->auth->whatIsMyPlatform();
    }
}
```

**AuthWebService**

```php
<?php

namespace App\Http\Services\Api\V1\Auth;

class AuthWebService extends AuthService
{
    public static function platform(): string
    {
        return 'website';
    }

    public function whatIsMyPlatform() : string // will be invoked if the request came from website endpoints
    {
        return 'platform: website!';
    }
}
```

**AuthMobileService**

```php
<?php

namespace App\Http\Services\Api\V1\Auth;

class AuthMobileService extends AuthService
{
    public static function platform(): string
    {
        return 'mobile';
    }

    public function whatIsMyPlatform() : string // will be invoked if the request came from mobile endpoints
    {
        return 'platform: mobile!';
    }
}

```

### Useful Helper Classes:
#### app/Http/Helpers/Response
A class to standardize API response formatting in your Laravel application.
```php
<?php

namespace App\Http\Helpers;

class Response
{
    public static function success($status = Http::OK, $message = 'Success', $data = []) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function fail($status = Http::UNPROCESSABLE_ENTITY, $message = 'Error', $data = []) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function custom($status, $message, $data = []) {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $status);
    }
}

```


#### app/Http/Helpers/Get

A class that simplifies building JSON responses for query results. Pass a resource class, repository object, method, parameters, and other options to get a standardized JSON response.

```php
<?php

namespace App\Http\Helpers;

use App\Repository\RepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class Get
{
    public static function handle($resource, RepositoryInterface $repository, $method = 'getAll', $parameters = [], $is_instance = false, $message = 'Success', $resource_parameters = [])
    {
        try {
            $executable = $repository->$method(...$parameters);
            $records = $is_instance ? new $resource($executable, ...$resource_parameters) : $resource::collection($executable);
            return Response::success(message: $message, data: $records);
        } catch (Exception $e) {
            Log::error('CATCH: '. $e);
//            return $e;
            return Response::fail(status: 404, message: __('messages.No data found'));
        }
    }
}

```

```php
<?php

namespace App\Http\Services\Api\V1\Auth;

use App\Http\Helpers\Get;
use App\Http\Resources\V1\User\UserResource;
use App\Repository\UserRepositoryInterface;
use App\Http\Services\PlatformService;


abstract class AuthService extends PlatformService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    )
    {
    }
    
    public function getAllUsers()
    {
        return Get::handle(UserResource::class, $this->userRepository, 'getAll')
    }
}
```
