<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\mappers;

// Yii Imports
use Yii;

// CMG Imports
use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\interfaces\base\IFeatured;

use cmsgears\core\common\models\base\ModelMapper;
use cmsgears\core\common\models\resources\Gallery;

use cmsgears\core\common\models\traits\base\FeaturedTrait;

/**
 * The mapper to map Gallery Model to specific parent model for given parentId and parentType.
 *
 * @property integer $id
 * @property integer $modelId
 * @property integer $parentId
 * @property string $parentType
 * @property string $type
 * @property string $key
 * @property integer $order
 * @property boolean $active
 * @property boolean $pinned
 * @property boolean $featured
 *
 * @since 1.0.0
 */
class ModelGallery extends ModelMapper implements IFeatured {

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

		$rules[] = [ 'key', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ];
		$rules[] = [ [ 'pinned', 'featured' ], 'boolean' ];

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		$labels = parent::attributeLabels();

		$labels[ 'key' ] = 'Key';

		return $labels;
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

		return $this->hasOne( Gallery::class, [ 'id' => 'modelId' ] );
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
