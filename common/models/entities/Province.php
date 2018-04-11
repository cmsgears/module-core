<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\entities;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IName;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\Entity;

use cmsgears\core\common\models\traits\base\NameTrait;

/**
 * Province Entity
 *
 * @property integer $id
 * @property integer $countryId
 * @property string $code
 * @property string $iso
 * @property string $name
 *
 * @since 1.0.0
 */
class Province extends Entity implements IName {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_PROVINCE;

	// Private ----------------

	// Traits ------------------------------------------------------

	use NameTrait;

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
			[ [ 'countryId', 'code', 'name' ], 'required' ],
			[ 'id', 'safe' ],
			// Unique
			[ [ 'countryId', 'code' ], 'unique', 'targetAttribute' => [ 'countryId', 'code' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			[ [ 'countryId', 'name' ], 'unique', 'targetAttribute' => [ 'countryId', 'name' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Text Limit
			[ [ 'code', 'iso' ], 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			// Other
			[ 'countryId', 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'code', 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'countryId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_COUNTRY ),
			'code' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CODE ),
			'iso' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ISO ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Province ------------------------------

	/**
	 * Return corresponding country to which this province belongs.
	 *
	 * @return Country
	 */
	public function getCountry() {

		return $this->hasOne( Country::class, [ 'id' => 'countryId' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_PROVINCE );
	}

	// CMG parent classes --------------------

	// Province ------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'country' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the province with country.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with country.
	 */
	public static function queryWithCountry( $config = [] ) {

		$config[ 'relations' ] = [ 'country' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Find and return the provinces associated with given country id.
	 *
	 * @param integer $countryId
	 * @return Province[]
	 */
	public static function findByCountryId( $countryId ) {

		return self::find()->where( 'countryId=:id', [ ':id' => $countryId ] )->all();
	}

	/**
	 * Find and return the provinces associated with given code.
	 *
	 * @param string $code
	 * @return Province[]
	 */
	public static function findByCode( $code ) {

		return self::find()->where( 'code=:code', [ ':code' => $code ] )->all();
	}

	/**
	 * Find and return the province associated with given country id and code.
	 *
	 * @param integer $countryId
	 * @param string $code
	 * @return Province
	 */
	public static function findByCountryIdCode( $countryId, $code ) {

		return self::find()->where( 'countryId=:id AND code=:code', [ ':id' => $countryId, ':code' => $code ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
