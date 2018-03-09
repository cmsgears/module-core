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
use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\models\traits\base\ModelMapperTrait;

/**
 * The mapper to map File Model to specific parent model for given parentId and parentType.
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
class ModelFile extends ModelMapper implements IModelMapper {

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

	// ModelFile -----------------------------

	/**
	 * Return the file associated with the mapping.
	 *
	 * @return File
	 */
	public function getModel() {

		return $this->hasOne( File::class, [ 'id' => 'modelId' ] );
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

		return CoreTables::getTableName( CoreTables::TABLE_MODEL_FILE );
	}

	// CMG parent classes --------------------

	// ModelFile -----------------------------

	// Read - Query -----------

	// Read - Find ------------

	/**
	 * Find and return the mappings for given title. It's useful in cases where unique
	 * title is required for file.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $title
	 * @return ModelFile
	 */
	public static function findByFileTitle( $parentId, $parentType, $title ) {

		$fileTable = CoreTables::getTableName( CoreTables::TABLE_FILE );

		return self::queryByParent( $parentId, $parentType )->andWhere( "$fileTable.title=:title", [ ':title' => $title ] )->one();
	}

	/**
	 * Find and return the mappings for given title.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $title
	 * @return ModelFile[]
	 */
	public static function findByFileTitleLike( $parentId, $parentType, $title ) {

		$fileTable = CoreTables::getTableName( CoreTables::TABLE_FILE );

		return self::queryByParent( $parentId, $parentType )->andFilterWhere( [ 'like', "$fileTable.title", $title ] )->all();
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

		return self::queryByParent( $parentId, $parentType )->andFilterWhere( [ 'like', "$fileTable.type", $fileType ] )->all();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
