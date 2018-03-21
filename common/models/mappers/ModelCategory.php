<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\mappers;

// CMG Imports

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\ModelMapper;
use cmsgears\core\common\models\resources\Category;

/**
 * The mapper to map Category Model to specific parent model for given parentId and parentType.
 *
 * @property integer $id
 * @property integer $modelId
 * @property integer $parentId
 * @property string $parentType
 * @property string $type
 * @property integer $order
 * @property boolean $active
 * @property string $nodes
 *
 * @since 1.0.0
 */
class ModelCategory extends ModelMapper {

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

	/**
	 * @inheritdoc
	 */
	public function rules() {

		$rules = parent::rules();

		$rules[] = [ 'nodes', 'safe' ];

		return $rules;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelCategory -------------------------

	/**
	 * Return the category associated with the mapping.
	 *
	 * @return Category
	 */
	public function getModel() {

		return $this->hasOne( Category::class, [ 'id' => 'modelId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_MODEL_CATEGORY );
	}

	// CMG parent classes --------------------

	// ModelCategory -------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
