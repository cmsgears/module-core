<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Country;
use cmsgears\core\common\models\entities\Province;
use cmsgears\core\common\models\entities\City;

/**
 * Address Entity
 *
 * @property long $id
 * @property long $countryId
 * @property long $provinceId
 * @property long $cityId
 * @property string $title
 * @property string $line1
 * @property string $line2
 * @property string $line3
 * @property string $city
 * @property string $zip
 * @property string $subZip
 * @property string $firstName
 * @property string $lastName
 * @property string $phone
 * @property string $email
 * @property string $fax
 * @property string $website
 * @property integer $latitude
 * @property integer $longitude
 * @property short $zoomLevel
 */
class Address extends \cmsgears\core\common\models\base\Resource {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

    const TYPE_DEFAULT      =  0;
    const TYPE_PRIMARY      = 10;
    const TYPE_RESIDENTIAL  = 20;
    const TYPE_SHIPPING     = 30;
    const TYPE_BILLING      = 40;
    const TYPE_OFFICE       = 50;   // Office/ Registered
    const TYPE_MAILING      = 60;   // Mailing/ Communication
    const TYPE_BRANCH       = 70;   // Office having multiple branches

	// Public -----------------

    public static $typeMap = [
        self::TYPE_DEFAULT => 'Default',
        self::TYPE_PRIMARY => 'Primary',
        self::TYPE_RESIDENTIAL => 'Residential',
        self::TYPE_SHIPPING => 'Shipping',
        self::TYPE_BILLING => 'Billing',
        self::TYPE_OFFICE => 'Office',
        self::TYPE_MAILING => 'Mailing',
        self::TYPE_BRANCH => 'Branch'
    ];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

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

        // model rules
        $rules = [
            [ [ 'provinceId', 'countryId', 'line1', 'zip' ], 'required' ],
            [ [ 'cityId' ], 'required', 'on' => 'cityId' ],
            [ [ 'city' ], 'required', 'on' => 'cityName' ],
            [ [ 'longitude', 'latitude' ], 'required', 'on' => 'location' ],
            [ [ 'longitude', 'latitude', 'cityId' ], 'required', 'on' => 'locationWithCityId' ],
            [ [ 'longitude', 'latitude', 'city' ], 'required', 'on' => 'locationWithCityName' ],
            [ [ 'id' ], 'safe' ],
			[ [ 'zip', 'subZip' ], 'string', 'min' => 1, 'max' => Yii::$app->core->smallText ],
			[ [ 'phone', 'fax' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'title' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
            [ [ 'line1', 'line2', 'line3', 'firstName', 'lastName', 'email', 'website' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xLargeText ],
            [ [ 'zip', 'zip4' ], 'alphanumhyphenspace' ],
            [ [ 'countryId', 'provinceId', 'cityId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'longitude', 'latitude', 'zoomLevel' ], 'number' ]
        ];

        // trim if required
        if( Yii::$app->cmgCore->trimFieldValue ) {

            $trim[] = [ [ 'line1', 'line2', 'line3', 'city', 'zip', 'subZip', 'firstName', 'lastName', 'phone', 'email', 'fax', 'website', 'latitude', 'longitude' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
            'provinceId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PROVINCE ),
            'cityId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
            'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINE1 ),
            'line1' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINE1 ),
            'line2' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINE2 ),
            'line3' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LINE3 ),
            'city' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CITY ),
            'zip' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZIP ),
            'zip4' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZIP4 ),
            'firstName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FIRSTNAME ),
            'lastName' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LASTNAME ),
            'phone' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PHONE ),
            'email' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
            'fax' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FAX ),
            'website' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_WEBSITE ),
            'longitude' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LONGITUDE ),
            'latitude' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LATITUDE ),
            'zoomLevel' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ZOOM )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Address -------------------------------

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

    /**
     * @return City
     */
    public function getCity() {

        return $this->hasOne( City::className(), [ 'id' => 'cityId' ] );
    }

    public function toString() {

        $country    = $this->country->name;
        $province   = $this->province->name;
        $address    = $this->line1;

        if( isset( $this->line2 ) && strlen( $this->line2 ) > 0 ) {

            $address .= ", $this->line2";
        }

        if( isset( $this->line3 ) && strlen( $this->line3 ) > 0 ) {

            $address .= ", $this->line3";
        }

        if( isset( $this->city ) && strlen( $this->city ) > 0 ) {

            $address .= ", $this->city";
        }

        $address .= ", $country, $province, $this->zip";

        return $address;
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_ADDRESS;
    }

	// CMG parent classes --------------------

	// Address -------------------------------

	// Read - Query -----------

	public static function queryWithAll( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'country', 'province', 'city' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithCountry( $config = [] ) {

		$config[ 'relations' ]	= [ 'country' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithProvince( $config = [] ) {

		$config[ 'relations' ]	= [ 'province' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithCountryProvince( $config = [] ) {

		$config[ 'relations' ]	= [ 'country', 'province' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>