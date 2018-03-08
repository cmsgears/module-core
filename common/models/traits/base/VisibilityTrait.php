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
	public function isVisibilityPrivate(  $strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_PRIVATE;
		}

		return $this->visibility >= IVisibility::VISIBILITY_PRIVATE;
	}

	/**
	 * @inheritdoc
	 */
	public function isVisibilitySecured(  $strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_SECURED;
		}

		return $this->visibility >= IVisibility::VISIBILITY_SECURED;
	}

	/**
	 * @inheritdoc
	 */
	public function isVisibilityPublic(	 $strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_PUBLIC;
		}

		return $this->visibility >= IVisibility::VISIBILITY_PUBLIC;
	}

	/**
	 * @inheritdoc
	 */
	public function isVisibilityProtected(	$strict = true ) {

		if( $strict ) {

			return $this->visibility == IVisibility::VISIBILITY_PROTECTED;
		}

		return $this->visibility >= IVisibility::VISIBILITY_PROTECTED;
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
