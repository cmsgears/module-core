<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\MessageUtil;

class Province extends ActiveRecord {

	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {

		return $this->province_id;
	}

	public function getCountryId() {

		return $this->province_country;
	}

	public function getCountry() {

		return $this->hasOne( Country::className(), [ 'country_id' => 'province_country' ] );
	}

	public function setCountryId( $countryId ) {

		$this->province_country = $countryId;
	}

	public function getCode() {

		return $this->province_code;
	}

	public function setCode( $code ) {

		$this->province_code = $code;
	}

	public function getName() {

		return $this->province_name;
	}

	public function setName( $name ) {

		$this->province_name = $name;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'province_country', 'province_code', 'province_name' ], 'required' ],
            [ 'province_name', 'alphanumspace' ],
            [ 'province_name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'province_name', 'validateNameUpdate', 'on' => [ 'update' ] ],
			[ [ 'province_id' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'province_country' => 'Country',
			'province_code' => 'Code',
			'province_name' => 'Name'
		];
	}
	
	// Check whether a province existing with the same name for same country
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByCountryIdName( $this->province_country, $this->province_name ) ) {

                $this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	// Check whether a province existing with the same name for same country
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingProvince = self::findByCountryIdName( $this->province_country, $this->user_username );

			if( isset( $existingProvince ) && $this->getCountryId() == $existingProvince->getCountryId() && 
				$this->getId() != $existingProvince->getId() && strcmp( $existingProvince->province_name, $this->province_name ) == 0 ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	public static function tableName() {

		return CoreTables::TABLE_PROVINCE;
	}

	public static function findById( $id ) {

		return self::findOne( $id );
	}

	public static function findByCountryId( $countryId ) {

		return self::find()->where( 'province_country=:cid', [ ':cid' => $countryId ] )->one();
	}

	public static function findByName( $name ) {

		return self::find()->where( 'province_name=:name', [ ':name' => $name ] )->one();
	}

	public static function isExistByName( $name ) {

		$province = self::find()->where( 'province_name=:name', [ ':name' => $name ] )->one();

		return isset( $province );
	}

	public static function findByCountryIdName( $countryId, $name ) {

		return self::find()->where( 'province_country=:cid', [ ':cid' => $countryId ] )->andWhere( 'province_name=:name', [ ':name' => $name ] )->one();
	}
	
	public static function isExistByCountryIdName( $countryId, $name ) {

		$province = self::find()->where( 'province_country=:cid', [ ':cid' => $countryId ] )->andWhere( 'province_name=:name', [ ':name' => $name ] )->one();
		
		return isset( $province );
	}
}

?>