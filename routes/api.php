<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/userApproval', function (Request $request) {
    $user_id = $request->input('id');
    if (empty($user_id)) {
        return response()->json([
            'status' => 'failed'
        ]);
    }

    $user = User::where('id',$user_id)->get();
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
