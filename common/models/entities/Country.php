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

use cmsgears\core\common\models\traits\base\NameTrait;

/**
 * It represents Country on world map.
 *
 * @property integer $id
 * @property string $code
 * @property string $iso
 * @property string $name
 * @property string $title
 *
 * @since 1.0.0
 */
class Country extends \cmsgears\core\common\models\base\Entity implements IName {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_COUNTRY;

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
			[ [ 'name', 'code' ], 'required' ],
			[ 'id', 'safe' ],
			// Unique
			[ 'code', 'unique' ],
			[ 'iso', 'unique' ],
			[ 'name', 'unique' ],
			// Text Limit
			[ [ 'code', 'iso' ], 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'code', 'iso', 'name', 'title' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'code' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CODE ),
			'iso' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ISO ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Country -------------------------------

	/**
	 * Return list of provinces belonging to this country.
	 *
	 * @return Province[]
	 */
	public function getProvinces() {

		return $this->hasMany( Province::class, [ 'countryId' => 'id' ] );
	}

	/**
	 * Return list of regions belonging to this country.
	 *
	 * @return Region[]
	 */
	public function getRegions() {

		return $this->hasMany( Region::class, [ 'countryId' => 'id' ] );
	}

	/**
	 * Return list of cities belonging to this country.
	 *
	 * @return City[]
	 */
	public function getCities() {

		return $this->hasMany( City::class, [ 'countryId' => 'id' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_COUNTRY );
	}

	// CMG parent classes --------------------

	// Country -------------------------------

	// Read - Query -----------

	/**
	 * Return query to find the country with provinces.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with provinces.
	 */
	public static function queryWithProvinces( $config = [] ) {

		$config[ 'relations' ] = [ 'provinces' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the country with regions.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with provinces.
	 */
	public static function queryWithRegions( $config = [] ) {

		$config[ 'relations' ] = [ 'regions' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the country with cities.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with cities.
	 */
	public static function queryWithCities( $config = [] ) {

		$config[ 'relations' ] = [ 'cities' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Find and return the country associated with given code.
	 *
	 * @param string $code
	 * @return Country
	 */
	public static function findByCode( $code ) {

		return self::find()->where( 'code=:code', [ ':code' => $code ] )->one();
	}

	/**
	 * Find and return the country associated with given iso.
	 *
	 * @param string $iso
	 * @return Country
	 */
	public static function findByIso( $iso ) {

		return self::find()->where( 'iso=:iso', [ ':iso' => $iso ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
