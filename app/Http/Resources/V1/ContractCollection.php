<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContractCollection extends ResourceCollection {
	/**
	 * Transform the resource collection into an array.
	 * @return array<int|string, mixed>
	 */
	public function toArray(Request $request): array {
		if ($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
			return [
				'data' => $this->collection,
				'links' => [
					'first' => $this->url(1),
					'last'  => $this->url($this->lastPage()),
					'prev'  => $this->previousPageUrl(),
					'next'  => $this->nextPageUrl(),
				],
				'meta' => [
					'current_page' => $this->currentPage(),
					'from'         => $this->firstItem(),
					'last_page'    => $this->lastPage(),
					'per_page'     => $this->perPage(),
					'to'           => $this->lastItem(),
					'total'        => $this->total(),
				],
			];
		}

		return [
			'data' => $this->collection,
			'meta' => [
				'total' => $this->collection->count(),
			],
		];
	}
}
