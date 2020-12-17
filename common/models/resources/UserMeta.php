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

use cmsgears\core\common\models\entities\User;

/**
 * The meta model used to store user meta data and attributes.
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
class UserMeta extends \cmsgears\core\common\models\base\Meta {

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

	// UserMeta ------------------------------

	/**
	 * Returns the site model using one-to-one(hasOne) relationship.
	 *
	 * @return \cmsgears\core\common\models\entities\User User to which this meta belongs.
	 */
	public function getParent() {

		return $this->hasOne( User::class, [ 'id' => 'modelId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_USER_META );
	}

	// CMG parent classes --------------------

	// UserMeta ------------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}