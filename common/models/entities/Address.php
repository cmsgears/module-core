<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Address Entity
 *
 * @property int $id
 * @property int $countryId
 * @property int $provinceId
 * @property string $line1
 * @property string $line2
 * @property string $line3
 * @property string $city
 * @property string $zip
 * @property string $firstName
 * @property string $lastName
 * @property string $phone
 * @property string $email
 * @property string $fax
 */
class Address extends CmgEntity {

	const TYPE_RESIDENTIAL	=  0;
	const TYPE_OFFICE		=  5;
	const TYPE_MAILING		= 10;
	const TYPE_SHIPPING		= 15;
	const TYPE_BILLING		= 20;

	public static $statusMap = [
		self::TYPE_RESIDENTIAL => "Residential",
		self::TYPE_OFFICE => "Office",
		self::TYPE_MAILING => "Mailing",
		self::TYPE_SHIPPING => "Shipping",
		self::TYPE_BILLING => "Billing"
	];

	// Instance methods --------------------------------------------------

	/**
	 * @return Country
	 */
	public function getCountry() {

		return $this->hasOne( Country::className(), [ 'id' => 'countryId' ] );
	}

	/**
	 * @return Province
	 */
	public function getProvince() {

		return $this->hasOne( Province::className(), [ 'id' => 'provinceId' ] );
	}

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

		return  [
			[ [ 'provinceId', 'countryId', 'line1', 'city', 'zip' ], 'required' ],
			[ [ 'id', 'firstname', 'lastname', 'phone', 'email', 'fax' ], 'safe' ],
			[ [ 'line1', 'line2', 'line3' ], 'alphanumpun' ],
			[ 'city', 'alphanumspace' ],
			[ 'zip','alphanumhyphen' ],
			[ [ 'countryId', 'provinceId' ], 'number', 'integerOnly'=>true, 'min'=>1, 'tooSmall' => Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_SELECT ) ]
		];
	}

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'countryId' => 'Country',
			'provinceId' => 'Province',
			'line1' => 'Line 1',
			'line2' => 'Line 2',
			'line3' => 'Line 3',
			'city' => 'City',
			'zip' => 'Postal Code',
			'firstName' => 'First Name',
			'lastName' => 'Last Name',
			'phone' => 'Phone',
			'email' => 'Email',
			'fax' => 'Fax'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_ADDRESS;
	}

	// Address --------------------------

	/**
	 * @return Address - by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}
}

?>