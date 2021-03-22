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
use cmsgears\core\common\config\CoreGlobal;
use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\interfaces\base\IFeatured;

use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\models\traits\base\FeaturedTrait;

/**
 * The mapper to map File Model to specific parent model for given parentId and parentType.
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
 * @property boolean $popular
 *
 * @since 1.0.0
 */
class ModelFile extends \cmsgears\core\common\models\base\ModelMapper implements IFeatured {

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
		$rules[] = [ [ 'pinned', 'featured', 'popular' ], 'boolean' ];

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

	// ModelFile -----------------------------

	/**
	 * Return the file associated with the mapping.
	 *
	 * @return File
	 */
	public function getModel() {

		return $this->hasOne( File::class, [ 'id' => 'modelId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_MODEL_FILE );
	}

	// CMG parent classes --------------------

	// ModelFile -----------------------------

	// Read - Query -----------

	// Read - Find ------------

	/**
	 * Find and return the mappings for given title. It's useful in cases where unique
	 * title is required for file. The column tag can be used as title in such cases.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $fileCode
	 * @return ModelFile
	 */
	public static function findByFileCode( $parentId, $parentType, $fileCode, $type = null ) {

		$mapTable	= static::tableName();
		$fileTable	= CoreTables::getTableName( CoreTables::TABLE_FILE );
		$type		= $type ?? CoreGlobal::TYPE_DEFAULT;

		return self::queryByParent( $parentId, $parentType )->andWhere( "$fileTable.code=:code AND $mapTable.type=:type", [ ':code' => $fileCode, ':type' => $type ] )->one();
	}

	/**
	 * Find and return the mappings for given title.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $fileTitle
	 * @return ModelFile[]
	 */
	public static function findByFileTitle( $parentId, $parentType, $fileTitle ) {

		$fileTable = CoreTables::getTableName( CoreTables::TABLE_FILE );

		return self::queryByParent( $parentId, $parentType )->andFilterWhere( [ 'like', "$fileTable.title", $fileTitle ] )->all();
	}

	/**
	 * Find and return the mappings for file type.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $fileType
	 * @return ModelFile[]
	 */
	public static function findByFileType( $parentId, $parentType, $fileType ) {

		$fileTable = CoreTables::getTableName( CoreTables::TABLE_FILE );

		return self::queryByParent( $parentId, $parentType )->andWhere( "$fileTable.type=:type", [ ':type' => $fileType ] )->all();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
