# APITOOLKIT

APITOOLKIT is a comprehensive package for Laravel that provides powerful tools for building queries, handling API responses, and managing caching efficiently.

## Installation

You can install the package via composer:

```bash
composer require mhasnainjafri/apitoolkit
```

## Features

APITOOLKIT provides a variety of features for building and handling API responses efficiently:

- **Query Building**: Construct queries with filters, sorts, includes, and more.
- **API Response Helpers**: Standardize API responses.
- **Caching**: Cache API responses for a specified duration.
- **Custom Responses**: Standardized methods for success, error, validation, and other types of responses.

## Usage

### Query Building

APITOOLKIT helps you build complex queries easily.

```php
use Mhasnainjafri\APIToolkit\QueryBuilder\QueryBuilder;

// Example usage
$users = QueryBuilder::for(User::class)
    ->allowedFilters(['name', 'email'])
    ->allowedSorts(['name', 'created_at'])
    ->paginate();
```

### API Responses

APITOOLKIT offers various methods to handle API responses efficiently.

#### Success Response

```php
use Mhasnainjafri\APIToolkit\API;

return API::success($data, 'Data retrieved successfully');
```

#### Error Response

```php
use Mhasnainjafri\APIToolkit\API;

return API::error('An error occurred', 500);
```

#### Validation Error Response

```php
use Mhasnainjafri\APIToolkit\API;

$errors = ['email' => 'The email field is required.'];
return API::validationError($errors);
```

#### Not Found Response

```php
use Mhasnainjafri\APIToolkit\API;

return API::notFound('User not found');
```

### Caching

You can cache responses to improve performance and reduce load on your database.

#### Cached Response

```php
use Mhasnainjafri\APIToolkit\API;
use App\Models\User;

$resource = User::query();
$cacheKey = 'users_list';

return API::cachedResponse($resource, $cacheKey);
```

#### Paginated Cached Response

```php
use Mhasnainjafri\APIToolkit\API;
use App\Models\User;

$resource = User::query();
$pageNumber = 1;

return API::paginatedCachedResponse($resource, $pageNumber);
```

#### Clearing Cache

```php
use Mhasnainjafri\APIToolkit\API;

$cacheKey = 'users_list';
API::clearCacheKey($cacheKey);
```

### Custom Responses

You can also create custom responses as needed.

```php
use Mhasnainjafri\APIToolkit\API;

$data = ['key' => 'value'];
return API::custom($data, 'Custom response message');
```

## Constants

APITOOLKIT provides various HTTP status codes as constants for convenience:

- `API::SUCCESS`: 200
- `API::CREATED`: 201
- `API::NO_CONTENT`: 204
- `API::BAD_REQUEST`: 400
- `API::UNAUTHORIZED`: 401
- `API::FORBIDDEN`: 403
- `API::NOT_FOUND`: 404
- `API::METHOD_NOT_ALLOWED`: 405
- `API::UNPROCESSABLE_ENTITY`: 422
- `API::INTERNAL_SERVER_ERROR`: 500
- `API::NOT_IMPLEMENTED`: 501
- `API::BAD_GATEWAY`: 502
- `API::SERVICE_UNAVAILABLE`: 503

## Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue on GitHub.

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
