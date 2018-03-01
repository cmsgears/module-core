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
use cmsgears\core\common\models\interfaces\IPriority;

/**
 * It's useful for models having priority attribute.
 *
 * @property integer $priority
 *
 * @since 1.0.0
 */
trait PriorityTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	public static $priorityMap = [
		ISeverity::PRIORITY_DEFAULT => 'Undefined',
		IPriority::PRIORITY_LOW => 'Low',
		IPriority::PRIORITY_MEDIUM => 'Medium',
		IPriority::PRIORITY_HIGH => 'High'
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

	// PriorityTrait -------------------------

	/**
	 * @inheritdoc
	 */
	public function getPriorityStr() {

		return self::$priorityMap[ $this->severity ];
	}

	/**
	 * @inheritdoc
	 */
	public function isPriorityDefault(	$strict = true ) {

		if( $strict ) {

			return $this->priority == IPriority::PRIORITY_DEFAULT;
		}

		return $this->priority >= IPriority::PRIORITY_DEFAULT;
	}

	/**
	 * @inheritdoc
	 */
	public function isPriorityLow(	$strict = true ) {

		if( $strict ) {

			return $this->priority == IPriority::PRIORITY_LOW;
		}

		return $this->priority >= IPriority::PRIORITY_LOW;
	}

	/**
	 * @inheritdoc
	 */
	public function isPriorityMedium(  $strict = true ) {

		if( $strict ) {

			return $this->priority == IPriority::PRIORITY_MEDIUM;
		}

		return $this->priority >= IPriority::PRIORITY_MEDIUM;
	}

	/**
	 * @inheritdoc
	 */
	public function isPriorityHigh(	 $strict = true ) {

		if( $strict ) {

			return $this->priority == IPriority::PRIORITY_HIGH;
		}

		return $this->priority >= IPriority::PRIORITY_HIGH;
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// PriorityTrait -------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
