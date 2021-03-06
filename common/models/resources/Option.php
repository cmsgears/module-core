<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\resources;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;

/**
 * The option model stores the options available for a category. The options can be
 * selected for a model using the category.
 *
 * @property integer $id
 * @property integer $categoryId
 * @property string $name
 * @property string $value
 * @property string $icon
 * @property boolean $active
 * @property boolean $input
 * @property integer $order
 * @property string $htmlOptions
 * @property string $content
 * @property string $data
 *
 * @since 1.0.0
 */
class Option extends \cmsgears\core\common\models\base\Resource implements IContent, IData {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_OPTION;

	// Private ----------------

	// Traits ------------------------------------------------------

	use ContentTrait;
	use DataTrait;

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

		// Model Rules
		$rules = [
			// Required, Safe
			[ 'name', 'required' ],
			[ [ 'id', 'htmlOptions', 'content' ], 'safe' ],
			// Unique
			[ 'name', 'unique', 'targetAttribute' => [ 'categoryId', 'name' ] ],
			// Text Limit
			[ [ 'name', 'icon' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'value', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			//[ 'name', 'alphanumu' ],
			[ [ 'active', 'input' ], 'boolean' ],
			[ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ 'categoryId', 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'value', 'icon' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'categoryId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CATEGORY ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'value' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
			'input' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_INPUT ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'htmlOptions' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// yii\db\BaseActiveRecord

    /**
     * @inheritdoc
     */
	public function beforeSave( $insert ) {

	    if( parent::beforeSave( $insert ) ) {

			if( empty( $this->order ) || $this->order <= 0 ) {

				$this->order = 0;
			}

	        return true;
	    }

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Option --------------------------------

	/**
	 * Returns respective category to which it is mapped.
	 *
	 * @return Category
	 */
	public function getCategory() {

		return $this->hasOne( Category::class, [ 'id' => 'categoryId' ] );
	}

	public function getActiveStr() {

		return Yii::$app->formatter->asBoolean( $this->active );
	}

	public function getInputStr() {

		return Yii::$app->formatter->asBoolean( $this->input );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_OPTION );
	}

	// CMG parent classes --------------------

	// Option --------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'category' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the option with category.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with category.
	 */
	public static function queryWithCategory( $config = [] ) {

		$config[ 'relations' ] = [ 'category' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * @return Option - by category id
	 */

	/**
	 * Return the options available for given category id.
	 *
	 * @param integer $categoryId
	 * @return Option[]
	 */
	public static function findByCategoryId( $categoryId ) {

		$optionTable = CoreTables::getTableName( CoreTables::TABLE_OPTION );

		return self::find()->where( "$optionTable.categoryId=:id", [ ':id' => $categoryId ] )->all();
	}

	/**
	 * Return the option using given name and category id.
	 *
	 * @param string $name
	 * @param integer $categoryId
	 * @return Option
	 */
	public static function findByNameCategoryId( $name, $categoryId ) {

		$optionTable = CoreTables::getTableName( CoreTables::TABLE_OPTION );

		return self::find()->where( "$optionTable.name=:name AND $optionTable.categoryId=:id", [ ':name' => $name, ':id' => $categoryId ] )->one();
	}

	/**
	 * Check whether option exist by name and category id.
	 *
	 * @return boolean
	 */
	public static function isExistByNameCategoryId( $name, $categoryId ) {

		$option = self::findByNameCategoryId( $name, $categoryId );

		return isset( $option );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete all the options for given category id.
	 *
	 * @param integer $categoryId
	 * @return integer number of rows.
	 */
	public static function deleteByCategoryId( $categoryId ) {

		return self::deleteAll( 'categoryId=:id', [ ':id' => $categoryId ] );
	}

	/**
	 * Delete all the options for given name and category id.
	 *
	 * @param string $name
	 * @param integer $categoryId
	 * @return integer number of rows.
	 */
	public static function deleteByNameCategoryId( $name, $categoryId ) {

		return self::deleteAll( "name=:name AND categoryId=:id", [ ':name' => $name, ':id' => $categoryId ] );
	}

}
