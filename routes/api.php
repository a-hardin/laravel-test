<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->post('/userApproval', function (Request $request) {
    if (Auth::user()->cannot('viewAdmin', 'App\User')) {
        return 'Unauthorized';
    }
    $user_id = $request->input('id');

    if (empty($user_id)) {
        return response()->json([
            'status' => 'failed'
        ]);
    }

    $user = User::find($user_id);


    if (empty($user)) {
        return response()->json([
            'status' => 'failed'
        ]);
    }
    
    $user->approved = $request->approved;
    $user->save();

    return response()->json([
        'status' => 'success'
    ]);
});
