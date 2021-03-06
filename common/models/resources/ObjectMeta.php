<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\resources;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\entities\ObjectData;

/**
 * The meta model used to store object meta data and attributes.
 *
 * @property integer $id
 * @property integer $modelId
 * @property string $icon
 * @property string $name
 * @property string $label
 * @property string $type
 * @property boolean $active
 * @property string $valueType
 * @property string $value
 * @property string $data
 *
 * @since 1.0.0
 */
class ObjectMeta extends \cmsgears\core\common\models\base\Meta {

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

	// ObjectMeta ----------------------------

	/**
	 * Returns the object model using one-to-one(hasOne) relationship.
	 *
	 * @return \cmsgears\core\common\models\entities\ObjectData Object to which this meta belongs.
	 */
	public function getParent() {

		return $this->hasOne( ObjectData::class, [ 'id' => 'modelId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_OBJECT_META );
	}

	// CMG parent classes --------------------

	// ObjectMeta ----------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
