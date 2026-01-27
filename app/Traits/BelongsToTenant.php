<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant {
	protected static function bootBelongsToTenant() {
		static::addGlobalScope('tenant', function (Builder $builder) {
			if (Auth::check()) {
				$builder->where('tenant_id', Auth::user()->tenant_id);
			}
		});

		static::creating(function ($model) {
			if (Auth::check()) {
				$model->tenant_id = Auth::user()->tenant_id;
			}
		});
	}
}
