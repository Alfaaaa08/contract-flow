<?php

namespace App\Enums;

enum ContractStatus: int {
	case DRAFT      = 1;
	case ACTIVE     = 2;
	case EXPIRED    = 3;
	case TERMINATED = 4;
	case EXPIRING   = 5;

	public function label(): string {
		return match ($this) {
			self::DRAFT      => 'Draft',
			self::ACTIVE     => 'Active',
			self::EXPIRED    => 'Expired',
			self::TERMINATED => 'Terminated',
			self::EXPIRING   => 'Expiring',
		};
	}
}
