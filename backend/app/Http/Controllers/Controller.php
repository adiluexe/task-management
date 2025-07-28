<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Task Management API",
 *     version="1.0.0",
 *     description="A comprehensive task management system built with Laravel 11 and Vue 3",
 *     @OA\Contact(
 *         email="admin@taskmanagement.com",
 *         name="API Support"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Task Management API Server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Laravel Sanctum token authentication"
 * )
 */
abstract class Controller
{
    //
}
