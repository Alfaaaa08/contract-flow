<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 * @return array<string, mixed>
	 */
	public function toArray(Request $request) {
		return [
			'id'         => $this->id,
			'name'       => $this->name,
			'value'      => $this->value ? number_format((float) $this->value, 2, '.', '') : '0.00',
			'start_date' => $this->start_date?->format('Y-m-d'),
			'end_date'   => $this->end_date?->format('Y-m-d'),
			'status'     => $this->status,
			'display_status' => $this->display_status,

			'client'     => new ClientResource($this->whenLoaded('client')),
			'type'       => new ContractTypeResource($this->whenLoaded('type')),

			// Timestamps
			'created_at' => $this->created_at?->toIso8601String(),
			'updated_at' => $this->updated_at?->toIso8601String(),
		];
	}
}
