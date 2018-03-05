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
use cmsgears\core\common\models\interfaces\base\IModelMapper;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\ModelMapper;
use cmsgears\core\common\models\resources\Address;

use cmsgears\core\common\models\traits\ModelMapperTrait;

/**
 * The mapper to map Address Model to specific parent model for given parentId and parentType.
 *
 * @property integer $id
 * @property integer $modelId
 * @property integer $parentId
 * @property string $parentType
 * @property string $type
 * @property integer $order
 * @property boolean $active
 *
 * @since 1.0.0
 */
class ModelAddress extends ModelMapper implements IModelMapper {

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

	use ModelMapperTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelAddress --------------------------

	/**
	 * Return the address associated with the mapping.
	 *
	 * @return Address
	 */
	public function getModel() {

		return $this->hasOne( Address::className(), [ 'id' => 'modelId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_MODEL_ADDRESS );
	}

	// CMG parent classes --------------------

	// ModelAddress --------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
