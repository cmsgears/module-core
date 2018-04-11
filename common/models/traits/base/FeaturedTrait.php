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
 * FeaturedTrait can be used to mark a model as featured or pinned.
 *
 * @property integer $createdBy
 * @property integer $modifiedBy
 *
 * @since 1.0.0
 */
trait FeaturedTrait {

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

	// FeaturedTrait -------------------------

	/**
	 * @inheritdoc
	 */
	public function getPinnedStr() {

		return Yii::$app->formatter->asBoolean( $this->pinned );
	}

	/**
	 * @inheritdoc
	 */
	public function getFeaturedStr() {

		return Yii::$app->formatter->asBoolean( $this->featured );
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// FeaturedTrait -------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
