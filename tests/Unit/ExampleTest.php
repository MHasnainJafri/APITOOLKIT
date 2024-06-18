<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Mhasnainjafri\APIToolkit\API;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder as DB_Builder;
uses(Mhasnainjafri\APIToolkit\Tests\TestCase::class);


it('cached Response with QueryBuilder', function () {
    // Mocking the QueryBuilder instance
    $queryBuilder = Mockery::mock(Illuminate\Database\Query\Builder::class);
    $queryBuilder->shouldReceive('toSql')->andReturn('select * from users where id = ?');
    $queryBuilder->shouldReceive('getBindings')->andReturn(['value1', 'value2']);
    $queryBuilder->shouldReceive('get')->andReturn(collect(['data' => 'mocked data'])); // Mocking the get method

    // Mocking the Cache facade
    Cache::shouldReceive('has')->with(Mockery::any())->andReturn(false);
    Cache::shouldReceive('put')->with(Mockery::any(), Mockery::any(), Mockery::any())->andReturn(true);

    // Call the method under test
    $response = API::cachedResponse($queryBuilder);

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['meta']['cached_key'])->toBeString();
    expect($responseContent['meta']['cached_until'])->toBeString();
    expect($responseContent['data'])->toBeArray();
    expect($responseContent['data']['data'])->toBe('mocked data');
});
it('cached Response with Model', function () {
    // Mocking the Builder instance
    $builder = Mockery::mock(DB_Builder::class);
    $builder->shouldReceive('toSql')->andReturn('select * from users where id = ?');
    $builder->shouldReceive('getBindings')->andReturn(['value1', 'value2']);
    $builder->shouldReceive('get')->andReturn(collect(['data' => 'mocked data'])); // Mocking the get method

    // Mocking the Model instance
    $model = Mockery::mock(Model::class);
    $model->shouldReceive('getQuery')->andReturn($builder);
    $model->shouldReceive('get')->andReturn(collect(['data' => 'mocked data'])); // Mocking the get method for Model

    // Mocking the Cache facade
    Cache::shouldReceive('has')->with(Mockery::any())->andReturn(false);
    Cache::shouldReceive('put')->with(Mockery::any(), Mockery::any(), Mockery::any())->andReturn(true);

    // Call the method under test
    $response = API::cachedResponse($model);

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['meta']['cached_key'])->toBeString();
    expect($responseContent['meta']['cached_until'])->toBeString();
    expect($responseContent['data'])->toBeArray();
    expect($responseContent['data']['data'])->toBe('mocked data');
});

it('cached Response with DB_Builder', function () {
    // Mocking the DB_Builder instance
    $dbBuilder = Mockery::mock(DB_Builder::class);
    $dbBuilder->shouldReceive('toSql')->andReturn('select * from users where id = ?');
    $dbBuilder->shouldReceive('getBindings')->andReturn(['value1', 'value2']);
    $dbBuilder->shouldReceive('get')->andReturn(collect(['data' => 'mocked data'])); // Mocking the get method

    // Mocking the Cache facade
    Cache::shouldReceive('has')->with(Mockery::any())->andReturn(false);
    Cache::shouldReceive('put')->with(Mockery::any(), Mockery::any(), Mockery::any())->andReturn(true);

    // Call the method under test
    $response = API::cachedResponse($dbBuilder);

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['meta']['cached_key'])->toBeString();
    expect($responseContent['meta']['cached_until'])->toBeString();
    expect($responseContent['data'])->toBeArray();
    expect($responseContent['data']['data'])->toBe('mocked data');
});







it('cached Response with Query Builder', function () {
    // Mocking the Builder instance
    $builder = Mockery::mock(DB_Builder::class);
    $builder->shouldReceive('toSql')->andReturn('select * from users where id = ?');
    $builder->shouldReceive('getBindings')->andReturn(['value1', 'value2']);
    $builder->shouldReceive('get')->andReturn(collect(['data' => 'mocked data'])); // Mocking the get method

    // Mocking the Model instance
    $model = Mockery::mock(Model::class);
    $model->shouldReceive('getQuery')->andReturn($builder);
    $model->shouldReceive('get')->andReturn(collect(['data' => 'mocked data'])); // Mocking the get method for Model

    // Mocking the Cache facade
    Cache::shouldReceive('has')->with(Mockery::any())->andReturn(false);
    Cache::shouldReceive('put')->with(Mockery::any(), Mockery::any(), Mockery::any())->andReturn(true);

    // Call the method under test
    $response = API::cachedResponse($model);

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['meta']['cached_key'])->toBeString();
    expect($responseContent['meta']['cached_until'])->toBeString();
    expect($responseContent['data'])->toBeArray();
    expect($responseContent['data']['data'])->toBe('mocked data');
});

it('cached Response with Builder', function () {
    // Mocking the Builder instance
    $builder = Mockery::mock(Illuminate\Database\Eloquent\Builder::class);
    $builder->shouldReceive('toSql')->andReturn('select * from users where id = ?');
    $builder->shouldReceive('getQuery')->andReturnSelf(); // Mocking getQuery method
    $builder->shouldReceive('getBindings')->andReturn(['value1', 'value2']);
    $builder->shouldReceive('get')->andReturn(collect(['data' => 'mocked data'])); // Mocking the get method

    // Mocking the Cache facade
    Cache::shouldReceive('has')->with(Mockery::any())->andReturn(false);
    Cache::shouldReceive('put')->with(Mockery::any(), Mockery::any(), Mockery::any())->andReturn(true);

    // Call the method under test
    $response = API::cachedResponse($builder);

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['meta']['cached_key'])->toBeString();
    expect($responseContent['meta']['cached_until'])->toBeString();
    expect($responseContent['data'])->toBeArray();
    expect($responseContent['data']['data'])->toBe('mocked data');
});





it('success Response', function () {
    $data = ['data' => 'success data'];

    // Call the method under test
    $response = API::success($data, 'Success');

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['msg'])->toBe('Success');
    expect($responseContent['data'])->toBe($data);
    expect($responseContent['statusCode'])->toBe(200);
});

it('error Response', function () {
    // Call the method under test
    $response = API::error('An error occurred');

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['msg'])->toBe('An error occurred');
    expect($responseContent['data'])->toBe([]);
    expect($responseContent['statusCode'])->toBe(500);
});


it('validation error Response', function () {
    $errors = ['field' => 'error message'];

    // Call the method under test
    $response = API::validationError($errors);

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['msg'])->toBe('Validation failed');
    expect($responseContent['data']['errors'])->toBe($errors);
    expect($responseContent['statusCode'])->toBe(422);
});

it('not found Response', function () {
    // Call the method under test
    $response = API::notFound('Resource not found');

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['msg'])->toBe('Resource not found');
    expect($responseContent['data'])->toBe([]);
    expect($responseContent['statusCode'])->toBe(404);
});

it('unauthorized Response', function () {
    // Call the method under test
    $response = API::unauthorized('Unauthorized');

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['msg'])->toBe('Unauthorized');
    expect($responseContent['data'])->toBe([]);
    expect($responseContent['statusCode'])->toBe(401);
});


it('forbidden Response', function () {
    // Call the method under test
    $response = API::forbidden('Forbidden');

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['msg'])->toBe('Forbidden');
    expect($responseContent['data'])->toBe([]);
    expect($responseContent['statusCode'])->toBe(403);
});


it('created Response', function () {
    $data = ['data' => 'created data'];

    // Call the method under test
    $response = API::created($data, 'Resource created');

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['msg'])->toBe('Resource created');
    expect($responseContent['data'])->toBe($data);
    expect($responseContent['statusCode'])->toBe(201);
});


it('no content Response', function () {
    // Call the method under test
    $response = API::noContent();

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['msg'])->toBe('No content');
    expect($responseContent['data'])->toBe([]);
    expect($responseContent['statusCode'])->toBe(204);
});


it('paginated Response', function () {
    $items = ['item1', 'item2', 'item3'];
    $paginator = new LengthAwarePaginator($items, 3, 1, 1);

    // Call the method under test
    $response = API::paginated($paginator);

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['msg'])->toBe('Success');
    expect($responseContent['data']['items'])->toBe($items);
    expect($responseContent['data']['current_page'])->toBe(1);
    expect($responseContent['data']['last_page'])->toBe(3);
    expect($responseContent['data']['per_page'])->toBe(1);
    expect($responseContent['data']['total'])->toBe(3);
    expect($responseContent['statusCode'])->toBe(200);
});


it('custom Response', function () {
    $data = ['data' => 'custom data'];

    // Call the method under test
    $response = API::custom($data, 'Custom response');

    $responseContent = json_decode($response->getContent(), true);

    // Assert that the response contains the expected data
    expect($responseContent['msg'])->toBe('Custom response');
    expect($responseContent['data'])->toBe($data);
    expect($responseContent['statusCode'])->toBe(200);
});


// it('generates a unique query string key for QueryBuilder', function () {
//     $queryBuilder = mock(QueryBuilder::class);
//     $queryBuilder->shouldReceive('toSql')->andReturn('SELECT * FROM users');
//     $queryBuilder->shouldReceive('getBindings')->andReturn(['value1', 'value2']);

//     $key = API::generateQueryStringKey($queryBuilder);

//     expect($key)->toBeString();
//     expect(strlen($key))->toBeGreaterThan(0);
// });

// it('generates a paginated cache key', function () {
//     $queryBuilder = mock(QueryBuilder::class);
//     $queryBuilder->shouldReceive('toSql')->andReturn('SELECT * FROM users');
//     $queryBuilder->shouldReceive('getBindings')->andReturn(['value1', 'value2']);

//     $pageNumber = 1;
//     $key = API::generatePaginatedCacheKey($queryBuilder, $pageNumber);

//     expect($key)->toBeString();
//     expect(strlen($key))->toBeGreaterThan(0);
// });

// it('fetches paginated cached response', function () {
//     $queryBuilder = mock(QueryBuilder::class);
//     $pageNumber = 1;
//     $minutes = 30;

//     Cache::shouldReceive('has')->andReturn(false);
//     Cache::shouldReceive('put')->andReturn(true);

//     $expectedResponse = [
//         'data' => [
//             // Your expected paginated data structure
//         ],
//         'status' => 200,
//     ];
//     mock(API::class)
//         ->shouldReceive('formatResponse')
//         ->andReturn(json_encode($expectedResponse));

//     $response = API::paginatedCachedResponse($queryBuilder, $pageNumber, $minutes);

//     expect($response)->toBeJson($expectedResponse);
// });

// it('fetches cached response', function () {
//     $model = mock(Model::class);
//     $uniqueKey = 'your_unique_key';
//     $minutes = 30;

//     Cache::shouldReceive('has')->andReturn(false);
//     Cache::shouldReceive('put')->andReturn(true);

//     $expectedResponse = [
//         'data' => [
//             // Your expected data structure
//         ],
//         'status' => 200,
//     ];
//     mock(API::class)
//         ->shouldReceive('formatResponse')
//         ->andReturn(json_encode($expectedResponse));

//     $response = API::cachedResponse($model, $uniqueKey, $minutes);

//     expect($response)->toBeJson($expectedResponse);
// });
