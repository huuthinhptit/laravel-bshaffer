<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (\Request $request) {
    return $request->user();
});

App::singleton('oauth2', function() {

    $storage = new OAuth2\Storage\Pdo(\Illuminate\Support\Facades\DB::connection()->getPdo());
    $server = new OAuth2\Server($storage);

    $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
    $server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));

    return $server;
});

Route::post('oauth/token', function()
{
    $bridgedRequest  = OAuth2\HttpFoundationBridge\Request::createFromRequest(\Request::instance());
    $bridgedResponse = new OAuth2\HttpFoundationBridge\Response();

    $bridgedResponse = App::make('oauth2')->handleTokenRequest($bridgedRequest, $bridgedResponse);

    return $bridgedResponse;
});

Route::get('private', function()
{
    $bridgedRequest  = OAuth2\HttpFoundationBridge\Request::createFromRequest(\Request::instance());
    $bridgedResponse = new OAuth2\HttpFoundationBridge\Response();

    if (App::make('oauth2')->verifyResourceRequest($bridgedRequest, $bridgedResponse)) {

        $token = App::make('oauth2')->getAccessTokenData($bridgedRequest);

        return Response::json(array(
            'private' => 'stuff',
            'user_id' => $token['user_id'],
            'client'  => $token['client_id'],
            'expires' => $token['expires'],
            'token' => $token,
        ));
    }
    else {
        return Response::json(array(
            'error' => 'Unauthorized'
        ), $bridgedResponse->getStatusCode());
    }
});