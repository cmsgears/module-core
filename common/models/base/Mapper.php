<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\base;

/**
 * Base model of all the mapper mapping parent model to model being mapped.
 *
 * The parent model can be either entity or resource. Similarly the model being mapped
 * can be both entity or resource.
 *
 * @property integer $modelId Id of model being mapped.
 * @property integer $parentId Id of parent model.
 * @property integer $parentType Type of parent model.
 * @property boolean $active Flag to check whether mapping is still active.
 *
 * @since 1.0.0
 */
abstract class Mapper extends ActiveRecord {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Mapper --------------------------------

	/**
	 * Returns primary model of the mapper.
	 *
	 * @return ActiveRecord Primary model.
	 */
	abstract public function getModel();

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// Mapper --------------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
