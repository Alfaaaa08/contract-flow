<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="ContractFlow API",
 *     description="Multi-tenant Contract Management System API"
 * )
 *
 * @OA\Server(
 *     url="http://localhost/api/v1",
 *     description="Local Development Server"
 * )
 *
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *         bearerFormat="JWT",
 *         description="Enter JWT token"
 *     )
 * )
 */
class OpenApiSpec
{
    //
}