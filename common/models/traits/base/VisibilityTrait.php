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

// CMG Imports
use cmsgears\core\common\models\interfaces\base\IVisibility;

/**
 * It's useful for models having visibility attribute.
 *
 * @property integer $visibility
 *
 * @since 1.0.0
 */
trait VisibilityTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

	public static $visibilityMap = [
		IVisibility::VISIBILITY_PRIVATE => 'Private',
		IVisibility::VISIBILITY_SECURED => 'Secured',
		IVisibility::VISIBILITY_PROTECTED => 'Protected',
		IVisibility::VISIBILITY_PUBLIC => 'Public'
	];

	public static $revVisibilityMap = [
		'Private' => IVisibility::VISIBILITY_PRIVATE,
		'Secured' => IVisibility::VISIBILITY_SECURED,
		'Protected' => IVisibility::VISIBILITY_PROTECTED,
		'Public' => IVisibility::VISIBILITY_PUBLIC
	];

	public static $urlRevVisibilityMap = [
		'private' => IVisibility::VISIBILITY_PRIVATE,
		'secured' => IVisibility::VISIBILITY_SECURED,
		'protected' => IVisibility::VISIBILITY_PROTECTED,
		'public' => IVisibility::VISIBILITY_PUBLIC
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

	// VisibilityTrait -----------------------

	/**
	 * @inheritdoc
	 */
	public function getVisibilityStr() {

		return self::$visibilityMap[ $this->visibility ];
	}

	/**
	 * @inheritdoc
	 */
	public function isVisibilityPrivate( $strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_PRIVATE;
		}

		return $this->visibility <= IVisibility::VISIBILITY_PRIVATE; // At most private
	}

	/**
	 * @inheritdoc
	 */
	public function isVisibilitySecured( $strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_SECURED;
		}

		return $this->visibility <= IVisibility::VISIBILITY_SECURED; // At most secure
	}

	/**
	 * @inheritdoc
	 */
	public function isVisibilityProtected( $strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_PROTECTED;
		}

		return $this->visibility <= IVisibility::VISIBILITY_PROTECTED; // At most protected
	}

	/**
	 * @inheritdoc
	 */
	public function isVisibilityPublic( $strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_PUBLIC;
		}

		return $this->visibility >= IVisibility::VISIBILITY_PUBLIC; // At least public
	}

	/**
	 * @inheritdoc
	 */
	public function isVisible() {

		$user = Yii::$app->core->getUser();

		// Always visible
		if( $this->isVisibilityPublic( false ) ) {

			return true;
		}
		// Visible to logged in users in strictly protected mode excluding secure and private modes
		else if( isset( $user ) && $this->isVisibilityProtected() ) {

			return true;
		}
		// Always visible to owner irrespective of visibility
		else {

			return $this->createdBy == $user->id;
		}
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// VisibilityTrait -----------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
