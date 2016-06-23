<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

/**
 * City Entity
 *
 * @property long $id
 * @property long $countryId
 * @property long $provinceId
 * @property string $name
 */
class City extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Constants/Statics --

	// Public -------------

	// Private/Protected --

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance Methods --------------------------------------------

	// yii\base\Component ----------------

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		// model rules
        $rules = [
            [ [ 'countryId', 'provinceId', 'name' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'countryId', 'provinceId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ 'name', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->largeText ],
            [ 'name', 'alphanumpun' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME )
		];
	}

	// City ------------------------------

	/**
	 * Validates whether a province existing with the same name for same country.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameCountryIdProvinceId( $this->name, $this->countryId, $this->provinceId ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates whether a province existing with the same name for same country.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingProvince = self::findByNameCountryIdProvinceId( $this->name, $this->countryId, $this->provinceId );

			if( isset( $existingProvince ) && $this->id != $existingProvince->id ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	/**
	 * @return Country - parent country for province
	 */
	public function getCountry() {

		return $this->hasOne( Country::className(), [ 'id' => 'countryId' ] );
	}

	/**
	 * @return Province - parent province for city
	 */
	public function getProvince() {

		return $this->hasOne( Province::className(), [ 'id' => 'provinceId' ] );
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_PROVINCE;
	}

	// City ------------------------------

	// Create -------------

	// Read ---------------

	/**
	 * @return array - by country id
	 */
	public static function findByCountryId( $countryId ) {

		return self::find()->where( 'countryId=:id', [ ':id' => $countryId ] )->all();
	}

	/**
	 * @return array - by province id
	 */
	public static function findByProvinceId( $provinceId ) {

		return self::find()->where( 'provinceId=:id', [ ':id' => $provinceId ] )->all();
	}

	/**
	 * @return Province - by country id and province id
	 */
	public static function findByCountryIdProvinceId( $countryId, $provinceId ) {

		return self::find()->where( 'countryId=:cid AND provinceId=:pid', [ ':cid' => $countryId, ':pid' => $provinceId ] )->one();
	}

	/**
	 * @return Province - by name, country id and province id
	 */
	public static function findByNameCountryIdProvinceId( $name, $countryId, $provinceId ) {

		return self::find()->where( 'name=:name AND countryId=:cid AND provinceId=:pid', [ ':name' => $name, ':cid' => $countryId, ':pid' => $provinceId ] )->one();
	}

	/**
	 * @return Province - check whether a province exist by the provided name and country id
	 */
	public static function isExistByNameCountryIdProvinceId( $name, $countryId, $provinceId ) {

		$city = self::findByNameCountryIdProvinceId( $name, $countryId, $provinceId );

		return isset( $city );
	}

	// Update -------------

	// Delete -------------
}

?>