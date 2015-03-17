<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\MessageUtil;

class Province extends CmgEntity {

	// Instance Methods --------------------------------------------

	// db columns

	public function getCountry() {

		return $this->hasOne( Country::className(), [ 'id' => 'countryId' ] );
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'countryId', 'code', 'name' ], 'required' ],
            [ 'name', 'alphanumspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
			[ [ 'id' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'countryId' => 'Country',
			'code' => 'Code',
			'name' => 'Name'
		];
	}
	
	// Check whether a province existing with the same name for same country
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByCountryIdName( $this->countryId, $this->name ) ) {

                $this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	// Check whether a province existing with the same name for same country
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingProvince = self::findByCountryIdName( $this->countryId, $this->user_username );

			if( isset( $existingProvince ) && $this->countryId == $existingProvince->countryId && 
				$this->id != $existingProvince->id && strcmp( $existingProvince->name, $this->name ) == 0 ) {

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

		return self::find()->where( 'countryId=:cid', [ ':cid' => $countryId ] )->one();
	}

	public static function findByName( $name ) {

		return self::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}

	public static function isExistByName( $name ) {

		$province = self::find()->where( 'name=:name', [ ':name' => $name ] )->one();

		return isset( $province );
	}

	public static function findByCountryIdName( $countryId, $name ) {

		return self::find()->where( 'countryId=:cid', [ ':cid' => $countryId ] )->andWhere( 'name=:name', [ ':name' => $name ] )->one();
	}
	
	public static function isExistByCountryIdName( $countryId, $name ) {

		$province = self::find()->where( 'countryId=:cid', [ ':cid' => $countryId ] )->andWhere( 'name=:name', [ ':name' => $name ] )->one();
		
		return isset( $province );
	}
}

?>