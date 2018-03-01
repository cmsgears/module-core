<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\base;

// CMG Imports
use cmsgears\core\common\models\interfaces\ISeverity;

/**
 * It's useful for models having severity attribute.
 *
 * @property integer $severity
 *
 * @since 1.0.0
 */
trait SeverityTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	public static $severityMap = [
		ISeverity::SEVERITY_DEFAULT => 'Undefined',
		ISeverity::SEVERITY_LOW => 'Low',
		ISeverity::SEVERITY_MEDIUM => 'Medium',
		ISeverity::SEVERITY_HIGH => 'High'
	];

	public static $revSeverityMap = [
		'Undefined' => ISeverity::SEVERITY_DEFAULT,
		'Low' => ISeverity::SEVERITY_LOW,
		'Medium' => ISeverity::SEVERITY_MEDIUM,
		'High' => ISeverity::SEVERITY_HIGH
	];

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// SeverityTrait -------------------------

	/**
	 * @inheritdoc
	 */
	public function getSeverityStr() {

		return self::$severityMap[ $this->severity ];
	}

	/**
	 * @inheritdoc
	 */
	public function isSeverityDefault(	$strict = true ) {

		if( $strict ) {

			return $this->severity == ISeverity::SEVERITY_DEFAULT;
		}

		return $this->severity >= ISeverity::SEVERITY_DEFAULT;
	}

	/**
	 * @inheritdoc
	 */
	public function isSeverityLow(	$strict = true ) {

		if( $strict ) {

			return $this->severity == ISeverity::SEVERITY_LOW;
		}

		return $this->severity >= ISeverity::SEVERITY_LOW;
	}

	/**
	 * @inheritdoc
	 */
	public function isSeverityMedium(  $strict = true ) {

		if( $strict ) {

			return $this->severity == ISeverity::SEVERITY_MEDIUM;
		}

		return $this->severity >= ISeverity::SEVERITY_MEDIUM;
	}

	/**
	 * @inheritdoc
	 */
	public function isSeverityHigh(	 $strict = true ) {

		if( $strict ) {

			return $this->severity == ISeverity::SEVERITY_HIGH;
		}

		return $this->severity >= ISeverity::SEVERITY_HIGH;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// SeverityTrait -------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
