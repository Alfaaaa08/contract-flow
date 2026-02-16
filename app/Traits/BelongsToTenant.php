<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant {
	protected static function bootBelongsToTenant() {
		static::addGlobalScope('tenant', function (Builder $builder) {
			if (tenancy()->initialized) {
				$builder->where('tenant_id', tenancy()->tenant->id);
			}
		});

		static::creating(function ($model) {
			if (tenancy()->initialized && empty($model->tenant_id)) {
				$model->tenant_id = tenancy()->tenant->id;
			}
		});
	}
}
