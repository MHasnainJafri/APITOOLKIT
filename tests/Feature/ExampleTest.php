<?php

// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Facades\Artisan;
// use Illuminate\Database\Eloquent\Model;
// use Mhasnainjafri\APIToolkit\APIToolkit;
// use Mhasnainjafri\APIToolkit\QueryBuilder\QueryBuilder;

// beforeEach(function () {
//     Artisan::call('migrate');
//     Artisan::call('db:seed');
//     Artisan::call('cache:clear');
// });

// it('can retrieve paginated data with caching', function () {
//     $response = $this->get('/api/users?page=1');

//     $response->assertStatus(200);
//     $response->assertJsonStructure([
//         'data' => [
//             // Define your expected paginated data structure
//         ],
//         'cached_key',
//         'cached_until',
//     ]);
// });

// it('can retrieve cached data for a specific resource', function () {
//     $response = $this->get('/api/users/1');

//     $response->assertStatus(200);
//     $response->assertJsonStructure([
//         'data' => [
//             // Define your expected data structure
//         ],
//         'cached_key',
//         'cached_until',
//     ]);
// });
