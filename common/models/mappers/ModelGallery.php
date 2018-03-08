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
use cmsgears\core\common\models\resources\Gallery;

use cmsgears\core\common\models\traits\ModelMapperTrait;

/**
 * The mapper to map Gallery Model to specific parent model for given parentId and parentType.
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
 *
 * @since 1.0.0
 */
class ModelGallery extends ModelMapper implements IModelMapper {

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

	/**
	 * @inheritdoc
	 */
	public function rules() {

		$rules = parent::rules();

		$rules[] = [ [ 'pinned', 'featured' ], 'boolean' ];

		return $rules;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelGallery --------------------------

	/**
	 * Return the gallery associated with the mapping.
	 *
	 * @return Gallery
	 */
	public function getModel() {

		return $this->hasOne( Gallery::className(), [ 'id' => 'modelId' ] );
	}

	/**
	 * Returns string representation of pinned flag.
	 *
	 * @return boolean
	 */
	public function getPinnedStr() {

		return Yii::$app->formatter->asBoolean( $this->pinned );
	}

	/**
	 * Returns string representation of featured flag.
	 *
	 * @return boolean
	 */
	public function getFeaturedStr() {

		return Yii::$app->formatter->asBoolean( $this->featured );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_MODEL_GALLERY );
	}

	// CMG parent classes --------------------

	// ModelGallery --------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
