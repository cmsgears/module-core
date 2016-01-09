<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Address Entity
 *
 * @property integer $id
 * @property integer $countryId
 * @property integer $provinceId
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
 * @property string $longitude
 * @property string $latitude
 */
class Address extends CmgEntity {

	const TYPE_RESIDENTIAL	=  0;
	const TYPE_PRIMARY		=  5;
	const TYPE_OFFICE		= 10;
	const TYPE_MAILING		= 20;
	const TYPE_SHIPPING		= 30;
	const TYPE_BILLING		= 40;

	public static $typeMap = [
		self::TYPE_RESIDENTIAL => 'Residential',
		self::TYPE_PRIMARY => 'Primary',
		self::TYPE_OFFICE => 'Office',
		self::TYPE_MAILING => 'Mailing',
		self::TYPE_SHIPPING => 'Shipping',
		self::TYPE_BILLING => 'Billing'
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

	public function toString() {

		$country 	= $this->country->name;
		$province 	= $this->province->name;
		$address	= $this->line1;

		if( isset( $this->line2 ) && strlen( $this->line2 ) > 0 ) {

			$address .= ", $this->line2";
		}

		if( isset( $this->line3 ) && strlen( $this->line2 ) > 0 ) {

			$address .= ", $this->line3";
		}

		$address .= ", $country, $province, $this->zip";

		return $address;
	}

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		// model rules
        $rules = [
			[ [ 'provinceId', 'countryId', 'line1', 'city', 'zip' ], 'required' ],
			[ [ 'id', 'firstName', 'lastName', 'email' ], 'safe' ],
			[ [ 'line1', 'line2', 'line3' ], 'alphanumpun' ],
			[ 'city', 'alphanumspace' ],
			[ 'zip', 'alphanumhyphenspace' ],
			[ [ 'countryId', 'provinceId' ], 'number', 'integerOnly'=>true, 'min'=>1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'phone', 'fax', 'longitude', 'latitude' ], 'string', 'min' => 1, 'max' => 100 ]
		];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'line1', 'line2', 'line3', 'city', 'zip', 'firstName', 'lastName', 'phone', 'email', 'fax', 'longitude', 'latitude' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
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
			'fax' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_FAX ),
			'longitude' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LONGITUDE ),
			'latitude' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LATITUDE )
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

}

?>