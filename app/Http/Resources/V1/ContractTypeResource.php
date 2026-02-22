<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractTypeResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request) {
		return [
			'id'         => $this->id,
			'name'       => $this->name,
			'icon'       => $this->icon,
			'created_at' => $this->created_at?->toIso8601String(),
			'updated_at' => $this->updated_at?->toIso8601String(),
		];
	}
}
