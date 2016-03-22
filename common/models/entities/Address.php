<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Address Entity
 *
 * @property long $id
 * @property long $countryId
 * @property long $provinceId
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
 * @property string $website
 * @property integer $longitude
 * @property integer $latitude
 * @property short $zoomLevel
 */
class Address extends CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    const TYPE_PRIMARY      =  0;
    const TYPE_RESIDENTIAL  = 10;
    const TYPE_SHIPPING     = 20;
    const TYPE_BILLING      = 30;
    const TYPE_OFFICE       = 40;   // Office/ Registered
    const TYPE_MAILING      = 50;   // Mailing/ Communication
    const TYPE_BRANCH       = 60;   // Office having multiple branches

    public static $typeMap = [
        self::TYPE_PRIMARY => 'Primary',
        self::TYPE_RESIDENTIAL => 'Residential',
        self::TYPE_SHIPPING => 'Shipping',
        self::TYPE_BILLING => 'Billing',
        self::TYPE_OFFICE => 'Office',
        self::TYPE_MAILING => 'Mailing',
        self::TYPE_BRANCH => 'Branch'
    ];

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

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

        $country    = $this->country->name;
        $province   = $this->province->name;
        $address    = $this->line1;

        if( isset( $this->line2 ) && strlen( $this->line2 ) > 0 ) {

            $address .= ", $this->line2";
        }

        if( isset( $this->line3 ) && strlen( $this->line3 ) > 0 ) {

            $address .= ", $this->line3";
        }

        $address .= ", $country, $province, $this->zip";

        return $address;
    }

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    /**
     * @inheritdoc
     */
    public function rules() {

        // model rules
        $rules = [
            [ [ 'provinceId', 'countryId', 'line1', 'city', 'zip' ], 'required' ],
            [ [ 'longitude', 'latitude' ], 'required', 'on' => 'location' ],
            [ [ 'id', 'firstName', 'lastName', 'email', 'website' ], 'safe' ],
            [ [ 'line1', 'line2', 'line3' ], 'alphanumpun' ],
            [ 'city', 'alphanumspace' ],
            [ 'zip', 'alphanumhyphenspace' ],
            [ [ 'countryId', 'provinceId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'phone', 'fax' ], 'string', 'min' => 1, 'max' => 100 ],
            [ [ 'longitude', 'latitude', 'zoomLevel' ], 'number' ]
        ];

        // trim if required
        if( Yii::$app->cmgCore->trimFieldValue ) {

            $trim[] = [ [ 'line1', 'line2', 'line3', 'city', 'zip', 'firstName', 'lastName', 'phone', 'email', 'fax', 'website', 'longitude', 'latitude' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
            'website' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_WEBSITE ),
            'longitude' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LONGITUDE ),
            'latitude' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LATITUDE ),
            'zoomLevel' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ZOOM )
        ];
    }

    // Address ---------------------------

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_ADDRESS;
    }

    // Address ---------------------------

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------
}

?>