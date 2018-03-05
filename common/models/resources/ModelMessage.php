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

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Locale;

use cmsgears\core\common\models\traits\base\ResourceTrait;

/**
 * Stores messages and templates in different languages apart from primary language.
 *
 * The message can belong either to main or child sites and other models. These messages are
 * ideally templates, warnings, text or errors.
 *
 * Other models can also store their messages. These can be either model property or content.
 *
 * These messages can be further categorised using the type attribute.
 *
 * @property integer $id
 * @property integer $localeId
 * @property integer $parentId
 * @property string $parentType
 * @property string $name
 * @property string $type
 * @property string $value
 *
 * @since 1.0.0
 */
class ModelMessage extends \cmsgears\core\common\models\base\Resource {

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

	use ResourceTrait;

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
			[ [ 'localeId', 'parentId', 'parentType', 'name' ], 'required' ],
			[ [ 'id', 'value' ], 'safe' ],
			// Unique
			[ [ 'parentId', 'parentType', 'localeId', 'name' ], 'unique', 'targetAttribute' => [ 'parentId', 'parentType', 'localeId', 'name' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Text Limit
			[ [ 'parentType', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			// Other
			[ 'localeId', 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ 'parentId', 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'value' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'localeId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LOCALE ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'value' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ModelMessage --------------------------

	/**
	 * @return Locale - parent locale.
	 */
	public function getLocale() {

		return $this->hasOne( Locale::className(), [ 'id' => 'localeId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_LOCALE_MESSAGE );
	}

	// CMG parent classes --------------------

	// ModelMessage --------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'locale' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the message with locale.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with locale.
	 */
	public static function queryWithLocale( $config = [] ) {

		$config[ 'relations' ]	= [ 'locale' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Find and return the message specific to given name, type and locale id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param int $localeId
	 * @return ModelMessage by name, type and locale id
	 */
	public static function findByNameTypeLocaleId( $parentId, $parentType, $name, $type, $localeId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND name=:name AND type=:type AND localeId=:lid' )
			->addParams( [ ':pid' => $parentId, ':ptype' => $parentType, ':name' => $name, ':type' => $type, ':lid' => $localeId ] )
			->one();
	}

	/**
	 * Check whether the message exists for given name, type and locale id.
	 *
	 * @param integer $parentId
	 * @param string $parentType
	 * @param string $name
	 * @param int $localeId
	 * @return boolean
	 */
	public static function isExistByNameTypeLocaleId( $parentId, $parentType, $name, $type, $localeId ) {

		return isset( self::findByNameLocaleId( $parentId, $parentType, $name, $type, $localeId ) );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Delete all messages related to a locale.
	 *
	 * @return int the number of rows deleted.
	 */
	public static function deleteByLocaleId( $localeId ) {

		return self::deleteAll( 'localeId=:id', [ ':id' => $localeId ] );
	}
}
