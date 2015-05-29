<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

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

	public static $typeMap = [
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

		return $this->hasOne( Country::className(), [ 'id' => 'countryId' ] )->from( CoreTables::TABLE_COUNTRY . ' country' );
	}

	/**
	 * @return Province
	 */
	public function getProvince() {

		return $this->hasOne( Province::className(), [ 'id' => 'provinceId' ] )->from( CoreTables::TABLE_PROVINCE . ' province' );
	}

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		return  [
			[ [ 'provinceId', 'countryId', 'line1', 'city', 'zip' ], 'required' ],
			[ [ 'id', 'firstname', 'lastname', 'phone', 'email', 'fax' ], 'safe' ],
			[ [ 'line1', 'line2', 'line3' ], 'alphanumpun' ],
			[ 'city', 'alphanumspace' ],
			[ 'zip','alphanumhyphenspace' ],
			[ [ 'countryId', 'provinceId' ], 'number', 'integerOnly'=>true, 'min'=>1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ]
		];
	}

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'countryId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_COUNTRY ),
			'provinceId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PROVINCE ),
			'line1' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LINE1 ),
			'line2' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LINE2 ),
			'line3' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LINE3 ),
			'city' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CITY ),
			'zip' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ZIP ),
			'firstName' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
			'lastName' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
			'phone' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PHONE ),
			'email' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'fax' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_FAX )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_ADDRESS;
	}

	// Address --------------------------
	
	// Read ----

	/**
	 * @return Address - by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}
}

?>