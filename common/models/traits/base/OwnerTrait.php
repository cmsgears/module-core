<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\base;

// Yii Imports
use Yii;

/**
 * It will be useful for models whose owner is identified by createdBy column. Rest of the
 * models must implement the method having appropriate logic to identify the owner and must
 * not use this trait.
 *
 * @property integer $createdBy
 *
 * @since 1.0.0
 */
trait OwnerTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// OwnerTrait ----------------------------

	/**
	 * @inheritdoc
	 */
	public function isOwner( $user = null, $strict = false ) {

		if( !isset( $user ) && !$strict ) {

			$user = Yii::$app->core->getUser();
		}

		if( isset( $user ) && isset( $this->userId ) ) {

			return $user->id == $this->userId;
		}

		if( isset( $user ) && isset( $this->createdBy ) ) {

			return $user->id == $this->createdBy;
		}

		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function getOwner() {

		if( isset( $this->userId ) ) {

			return $this->user;
		}

		if( isset( $this->createdBy ) ) {

			return $this->creator;
		}
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// OwnerTrait ----------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
