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

use cmsgears\core\common\models\interfaces\base\IFeatured;

use cmsgears\core\common\models\base\ModelMapper;
use cmsgears\core\common\models\entities\ObjectData;

use cmsgears\core\common\models\traits\base\FeaturedTrait;

/**
 * The mapper to map Object Model to specific parent model for given parentId and parentType.
 *
 * @property integer $id
 * @property integer $modelId
 * @property integer $parentId
 * @property string $parentType
 * @property string $type
 * @property integer $order
 * @property boolean $active
 * @property boolean $pinned
 * @property boolean $featured
 * @property string $nodes
 *
 * @since 1.0.0
 */
class ModelObject extends ModelMapper implements IFeatured {

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

	use FeaturedTrait;

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
		$rules[] = [ [ 'pinned', 'featured' ], 'boolean' ];

		return $rules;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelObject ---------------------------

	/**
	 * Return the object associated with the mapping.
	 *
	 * @return ObjectData
	 */
	public function getModel() {

		return $this->hasOne( ObjectData::class, [ 'id' => 'modelId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_MODEL_OBJECT );
	}

	// CMG parent classes --------------------

	// ModelObject ---------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
