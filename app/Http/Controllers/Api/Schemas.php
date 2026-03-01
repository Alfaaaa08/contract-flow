<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="User model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="tenant_id", type="string", example="acme-corp"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2026-02-24T20:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2026-02-24T20:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="Contract",
 *     type="object",
 *     title="Contract",
 *     description="Contract model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Alpha Project"),
 *     @OA\Property(property="value", type="string", example="50000.00"),
 *     @OA\Property(property="start_date", type="string", format="date", example="2026-01-01"),
 *     @OA\Property(property="end_date", type="string", format="date", example="2026-12-31"),
 *     @OA\Property(property="status", type="integer", example=2),
 *     @OA\Property(property="display_status", type="string", example="Active"),
 *     @OA\Property(property="client", ref="#/components/schemas/Client"),
 *     @OA\Property(property="type", ref="#/components/schemas/ContractType"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Client",
 *     type="object",
 *     title="Client",
 *     description="Client model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Acme Corporation"),
 *     @OA\Property(property="email", type="string", format="email", example="contact@acme.com"),
 *     @OA\Property(property="phone", type="string", example="11999999999"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="ContractType",
 *     type="object",
 *     title="ContractType",
 *     description="Contract Type model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Procurement"),
 *     @OA\Property(property="icon", type="string", example="file-text"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Schemas {
	//
}
