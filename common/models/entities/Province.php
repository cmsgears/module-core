<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Province Entity
 *
 * @property integer $id
 * @property integer $countryId
 * @property string $code
 * @property string $name
 */
class Province extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Country to which province belongs.
	 */
	public function getCountry() {

		return $this->hasOne( Country::className(), [ 'id' => 'countryId' ] );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'countryId', 'code', 'name' ], 'required' ],
            [ 'id', 'safe' ],
            [ 'name', 'alphanumspace' ],
            [ 'name', 'string', 'min'=>1, 'max'=>10 ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

	public function attributeLabels() {

		return [
			'countryId' => 'Country',
			'code' => 'Code',
			'name' => 'Name'
		];
	}
	
	// Province --------------------------

	/**
	 * Validates whether a province existing with the same name for same country.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByCountryIdName( $this->countryId, $this->name ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates whether a province existing with the same name for same country.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingProvince = self::findByCountryIdName( $this->countryId, $this->user_username );

			if( isset( $existingProvince ) && $this->countryId == $existingProvince->countryId && 
				$this->id != $existingProvince->id && strcmp( $existingProvince->name, $this->name ) == 0 ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\base\Model --------------------

	public static function tableName() {

		return CoreTables::TABLE_PROVINCE;
	}

	// Province --------------------------

	public static function findById( $id ) {

		return self::findOne( $id );
	}

	public static function findByCountryId( $countryId ) {

		return self::find()->where( 'countryId=:id', [ ':id' => $countryId ] )->one();
	}

	public static function findByName( $name ) {

		return self::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}

	public static function isExistByName( $name ) {

		$province = self::find()->where( 'name=:name', [ ':name' => $name ] )->one();

		return isset( $province );
	}

	public static function findByCountryIdName( $countryId, $name ) {

		return self::find()->where( 'countryId=:id AND name=:name', [ ':id' => $countryId, ':name' => $key ] )->one();
	}
	
	public static function isExistByCountryIdName( $countryId, $name ) {

		$province = self::findByCountryIdName( $countryId, $name );

		return isset( $province );
	}
}

?>