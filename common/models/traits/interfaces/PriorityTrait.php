<?php
namespace cmsgears\core\common\models\traits\interfaces;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\IPriority;

trait PriorityTrait {

	public static $priorityMap = [
		ISeverity::PRIORITY_DEFAULT => 'Undefined',
		IPriority::PRIORITY_LOW => 'Low',
		IPriority::PRIORITY_MEDIUM => 'Medium',
		IPriority::PRIORITY_HIGH => 'High'
	];

	public function getPriorityStr() {

		return self::$priorityMap[ $this->severity ];
	}

	public function isPriorityDefault(	$strict = true ) {

		if( $strict ) {

			return $this->priority == IPriority::PRIORITY_DEFAULT;
		}

		return $this->priority >= IPriority::PRIORITY_DEFAULT;
	}

	public function isPriorityLow(	$strict = true ) {

		if( $strict ) {

			return $this->priority == IPriority::PRIORITY_LOW;
		}

		return $this->priority >= IPriority::PRIORITY_LOW;
	}

	public function isPriorityMedium(  $strict = true ) {

		if( $strict ) {

			return $this->priority == IPriority::PRIORITY_MEDIUM;
		}

		return $this->priority >= IPriority::PRIORITY_MEDIUM;
	}

	public function isPriorityHigh(	 $strict = true ) {

		if( $strict ) {

			return $this->priority == IPriority::PRIORITY_HIGH;
		}

		return $this->priority >= IPriority::PRIORITY_HIGH;
	}
}
