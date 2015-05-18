<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Province Entity
 *
 * @property int $id
 * @property int $countryId
 * @property string $code
 * @property string $name
 */
class Province extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Country - parent country for province
	 */
	public function getCountry() {

		return $this->hasOne( Country::className(), [ 'id' => 'countryId' ] );
	}

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'countryId', 'code', 'name' ], 'required' ],
            [ 'id', 'safe' ],
            [ 'code', 'string', 'min'=>1, 'max'=>10 ],
            [ 'name', 'alphanumspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

	/**
	 * Model attributes
	 */
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

            if( self::isExistByNameCountryId( $this->countryId, $this->name ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates whether a province existing with the same name for same country.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingProvince = self::findByNameCountryId( $this->countryId, $this->user_username );

			if( isset( $existingProvince ) && $this->countryId == $existingProvince->countryId && 
				$this->id != $existingProvince->id && strcmp( $existingProvince->name, $this->name ) == 0 ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\base\Model --------------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_PROVINCE;
	}

	// Province --------------------------

	/**
	 * @return Province - by id
	 */
	public static function findById( $id ) {

		return self::findOne( $id );
	}

	/**
	 * @return array - by country id
	 */
	public static function findByCountryId( $countryId ) {

		return self::find()->where( 'countryId=:id', [ ':id' => $countryId ] )->all();
	}

	/**
	 * @return Province - by name
	 */
	public static function findByName( $name ) {

		return self::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}

	/**
	 * @return Province - check whether a province exist by the provided name
	 */
	public static function isExistByName( $name ) {

		$province = self::find()->where( 'name=:name', [ ':name' => $name ] )->one();

		return isset( $province );
	}

	/**
	 * @return Province - by code
	 */
	public static function findByCode( $code ) {

		return self::find()->where( 'code=:code', [ ':code' => $code ] )->one();
	}

	/**
	 * @return Province - by name and country id
	 */
	public static function findByNameCountryId( $name, $countryId ) {

		return self::find()->where( 'countryId=:id AND name=:name', [ ':id' => $countryId, ':name' => $key ] )->one();
	}

	/**
	 * @return Province - check whether a province exist by the provided name and country id
	 */
	public static function isExistByNameCountryId( $name, $countryId ) {

		$province = self::findByNameCountryId( $name, $countryId );

		return isset( $province );
	}
}

?>