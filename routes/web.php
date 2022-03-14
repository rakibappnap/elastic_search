<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $hosts = [
        'http://rakibhamid:welcome@localhost:9200',       // HTTP Basic Authentication
        'http://rakib55i:welcome@localhost:9300' // Different credentials on different host
    ];
  //  $client = \Elasticsearch\ClientBuilder::create()->setHosts($hosts)->build();
    /**
     * Index entries
     */
//    $params = [
//        'index' => 'theme_index_7',
//        'id'    => 'theme_7',
//        'body'  => [
//            'title' => 'Test Theme',
//            'Category' => 'Test',
//        ]
//    ];
//    $response = $client->index($params);
  //  dd($response);

    /**
     * Search
     */
/*    $params = [
        'index' => '',
        'body'  => [
            'query' => [
                'match' => [
                    'title' => [
                        'query' => 'R25',
                        'fuzziness' => 'AUTO',
                        'prefix_length' => 1
                    ],
                ]
            ]
        ]
    ];
    $params = [
        'index' => '',
        'body'  => [
            'query' => [
                'multi_match' => [
                    'fields' => [
                        'title',
                        'Category'
                    ],
                    'query' => 'text',
                    'fuzziness' => 'AUTO',
                ]
            ]
        ]
    ];

    $results = $client->search($params);
    dd($results);*/

    return view('welcome');


});
Route::get('/esIndex', [\App\Http\Controllers\EsController::class, 'index_entries']);
Route::get('/search', [\App\Http\Controllers\EsController::class, 'search']);
Route::get('/index_all', [\App\Http\Controllers\EsController::class, 'index_all']);
