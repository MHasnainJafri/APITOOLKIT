<?php

namespace Mhasnainjafri\APIToolkit;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as DB_Builder;
use Mhasnainjafri\APIToolkit\API\APIHelper;
use Mhasnainjafri\APIToolkit\QueryBuilder\QueryBuilder;

class APIToolkit
{  // Success Codes
    const SUCCESS = 200;
    const CREATED = 201;
    const NO_CONTENT = 204;

    // Client Error Codes
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const UNPROCESSABLE_ENTITY = 422;

    // Server Error Codes
    const INTERNAL_SERVER_ERROR = 500;
    const NOT_IMPLEMENTED = 501;
    const BAD_GATEWAY = 502;
    const SERVICE_UNAVAILABLE = 503;



    public static function paginatedCachedResponse(QueryBuilder|Model|Builder|DB_Builder $resource, int $pageNumber = 1, $minutes = 30, int $statusCode = self::SUCCESS) {
        // Generate a cache key based on the query string and pagination page number
        $cacheKey = self::generatePaginatedCacheKey($resource, $pageNumber);

        // Check if the cache exists
        if (Cache::has($cacheKey)) {
            $cachedData = Cache::get($cacheKey);
            $cachedUntil = Cache::get($cacheKey . '_until');
        } else {
            // Calculate the offset based on pagination
            $perPage = $resource instanceof Model ? $resource->getPerPage() : 15; // Assuming a default per page limit
            $offset = ($pageNumber - 1) * $perPage;

            // Fetch the paginated data from the resource
            $paginatedData = $resource instanceof Model
                ? $resource->skip($offset)->take($perPage)->get()
                : $resource->skip($offset)->take($perPage)->get()->toArray();

            // Store the data in the cache
            $cachedUntil = now()->addMinutes($minutes);
            Cache::put($cacheKey, $paginatedData, $cachedUntil); // Cache the paginated data
            Cache::put($cacheKey . '_until', $cachedUntil, $cachedUntil); // Store the expiration time
        }

        return APIHelper::formatResponse($cachedData ?? [], 'paginated data', $statusCode, [
            'cached_key' => $cacheKey,
            'cached_until' => $cachedUntil
        ]);
    }

    public static function cachedResponse(QueryBuilder|Model|Builder|DB_Builder $resource, String|bool $uniquekey = false, $minutes = 30, int $statusCode = self::SUCCESS) {
        // If the unique key is false, generate a key based on the query string
        if ($uniquekey === false) {
            $uniquekey = self::generateQueryStringKey($resource);
        }

        // Check if the cache exists
        if (Cache::has($uniquekey)) {
            $cachedData = Cache::get($uniquekey);
            $cachedUntil = Cache::get($uniquekey . '_until');
        } else {
            // If not, fetch the data from the resource
            $cachedData = $resource instanceof Model ? $resource->get() : $resource->get()->toArray();
            // Store the data in the cache
            $cachedUntil = now()->addMinutes($minutes);
            Cache::put($uniquekey, $cachedData, $cachedUntil); // Cache for the specified minutes
            Cache::put($uniquekey . '_until', $cachedUntil, $cachedUntil); // Store the expiration time
        }

        return APIHelper::formatResponse($cachedData, 'data', $statusCode, [
            'cached_key' => $uniquekey,
            'cached_until' => $cachedUntil
        ]);
    }

    private static function generateQueryStringKey(QueryBuilder|Model|Builder|DB_Builder $resource) {
        // Generate a unique key based on the query string
        if ($resource instanceof Model) {
            $query = $resource->getQuery();
        } elseif ($resource instanceof Builder) {
            $query = $resource->getQuery();
        } else {
            $query = $resource;
        }
        return md5($query->toSql() . serialize($query->getBindings()));
    }

    private static function generatePaginatedCacheKey(QueryBuilder|Model|Builder|DB_Builder $resource, int $pageNumber) {
        // Generate a cache key based on the query string and pagination page number
        $query = $resource instanceof Model || $resource instanceof Builder ? $resource->getQuery() : $resource;
        $queryString = $query->toSql() . serialize($query->getBindings()) . '_page_' . $pageNumber;
        return md5($queryString);
    }





    /**
     * Standard success response.
     *
     * @param array|Model|Collection $resource
     * @param string $msg
     * @param int $statusCode
     * @param array|bool|string $cache
     * @return JsonResponse
     */
    public static function success($resource = [], string $msg = 'Success', int $statusCode = self::SUCCESS): JsonResponse
    {


        return APIHelper::formatResponse($resource, $msg, $statusCode);
    }

    /**
     * Cached response based on key, model, or collection.
     *
     * @param string $cacheKey
     * @param \Closure $query
     * @param int $ttl
     * @param string $msg
     * @param int $statusCode
     * @return JsonResponse
     */
    // public static function cachedResponse(string $cacheKey, \Closure $query, int $ttl = 3600, string $msg = 'Success', int $statusCode = self::SUCCESS): JsonResponse
    // {
    //     $resource = Cache::remember($cacheKey, $ttl, $query);
    //     return APIHelper::formatResponse($resource, $msg, $statusCode);
    // }

    /**
     * Standard error response.
     *
     * @param string $msg
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function error(string $msg = 'An error occurred', int $statusCode = self::INTERNAL_SERVER_ERROR): JsonResponse
    {
        return APIHelper::formatResponse([], $msg, $statusCode);
    }

    /**
     * Exception response.
     *
     * @param Throwable $e
     * @return JsonResponse
     */
    public static function exception(Throwable $e): JsonResponse
    {
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : self::INTERNAL_SERVER_ERROR;
        $msg = $e->getMessage() ?: 'An error occurred';
        return APIHelper::formatResponse([], $msg, $statusCode);
    }

    /**
     * Validation error response.
     *
     * @param array $errors
     * @param string $msg
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function validationError(array $errors, string $msg = 'Validation failed', int $statusCode = self::UNPROCESSABLE_ENTITY): JsonResponse
    {
        return APIHelper::formatResponse(['errors' => $errors], $msg, $statusCode);
    }

    /**
     * Not found response.
     *
     * @param string $msg
     * @return JsonResponse
     */
    public static function notFound(string $msg = 'Resource not found'): JsonResponse
    {
        return APIHelper::formatResponse([], $msg, self::NOT_FOUND);
    }

    /**
     * Unauthorized response.
     *
     * @param string $msg
     * @return JsonResponse
     */
    public static function unauthorized(string $msg = 'Unauthorized'): JsonResponse
    {
        return APIHelper::formatResponse([], $msg, self::UNAUTHORIZED);
    }

    /**
     * Forbidden response.
     *
     * @param string $msg
     * @return JsonResponse
     */
    public static function forbidden(string $msg = 'Forbidden'): JsonResponse
    {
        return APIHelper::formatResponse([], $msg, self::FORBIDDEN);
    }

    /**
     * Created response.
     *
     * @param array|Model|Collection $resource
     * @param string $msg
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function created($resource, string $msg = 'Resource created', int $statusCode = self::CREATED): JsonResponse
    {
        return APIHelper::formatResponse($resource, $msg, $statusCode);
    }

    /**
     * No content response.
     *
     * @param string $msg
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function noContent(string $msg = 'No content', int $statusCode = self::NO_CONTENT): JsonResponse
    {
        return APIHelper::formatResponse([], $msg, $statusCode);
    }

    /**
     * Paginated response.
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
     * @param string $msg
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function paginated($paginator, string $msg = 'Success', int $statusCode = self::SUCCESS): JsonResponse
    {
        $resource = [
            'items' => $paginator->items(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];
        return APIHelper::formatResponse($resource, $msg, $statusCode);
    }

    /**
     * Custom response.
     *
     * @param array|Model|Collection $resource
     * @param string $msg
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function custom($resource, string $msg = 'Custom response', int $statusCode = self::SUCCESS): JsonResponse
    {
        return APIHelper::formatResponse($resource, $msg, $statusCode);
    }

    public static function clearCacheKey($cacheKey)
{
    Cache::forget($cacheKey);
    Cache::forget($cacheKey . '_until');
}
}
