<?php
namespace cmsgears\core\common\models\traits\interfaces;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\ISeverity;

trait SeverityTrait {

	public static $severityMap = [
        ISeverity::SEVERITY_DEFAULT => 'Undefined',
        ISeverity::SEVERITY_LOW => 'Low',
        ISeverity::SEVERITY_MEDIUM => 'Medium',
        ISeverity::SEVERITY_HIGH => 'High'
	];

	public function getSeverityStr() {

		return self::$severityMap[ $this->severity ];
	}

	public function isSeverityDefault(  $strict = true ) {

		if( $strict ) {

			return $this->severity == ISeverity::SEVERITY_DEFAULT;
		}

		return $this->severity >= ISeverity::SEVERITY_DEFAULT;
	}

	public function isSeverityLow(  $strict = true ) {

		if( $strict ) {

			return $this->severity == ISeverity::SEVERITY_LOW;
		}

		return $this->severity >= ISeverity::SEVERITY_LOW;
	}

	public function isSeverityMedium(  $strict = true ) {

		if( $strict ) {

			return $this->severity == ISeverity::SEVERITY_MEDIUM;
		}

		return $this->severity >= ISeverity::SEVERITY_MEDIUM;
	}

	public function isSeverityHigh(  $strict = true ) {

		if( $strict ) {

			return $this->severity == ISeverity::SEVERITY_HIGH;
		}

		return $this->severity >= ISeverity::SEVERITY_HIGH;
	}
}
