<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

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
	 * @return Country - parent country for province
	 */
	public function getCountry() {

		return $this->hasOne( Country::className(), [ 'id' => 'countryId' ] );
	}

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'countryId', 'code', 'name' ], 'required' ],
            [ 'id', 'safe' ],
            [ 'countryId', 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ 'code', 'string', 'min'=>1, 'max'=>10 ],
            [ 'name', 'string', 'min'=>1, 'max'=>150 ],
            [ 'name', 'alphanumspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'countryId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_COUNTRY ),
			'code' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CODE ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME )
		];
	}
	
	// Province --------------------------

	/**
	 * Validates whether a province existing with the same name for same country.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameCountryId( $this->countryId, $this->name ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
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

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_PROVINCE;
	}

	// Province --------------------------

	/**
	 * @return Province - by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	/**
	 * @return array - by country id
	 */
	public static function findByCountryId( $countryId ) {

		return self::find()->where( 'countryId=:id', [ ':id' => $countryId ] )->all();
	}

	/**
	 * @return Province - by name and country id
	 */
	public static function findByNameCountryId( $name, $countryId ) {

		return self::find()->where( 'countryId=:id AND name=:name', [ ':id' => $countryId, ':name' => $name ] )->one();
	}

	/**
	 * @return Province - check whether a province exist by the provided name and country id
	 */
	public static function isExistByNameCountryId( $name, $countryId ) {

		$province = self::findByNameCountryId( $name, $countryId );

		return isset( $province );
	}

	/**
	 * @return Province - by code and country id
	 */
	public static function findByCodeCountryId( $code, $countryId ) {

		return self::find()->where( 'countryId=:id AND code=:code', [ ':id' => $countryId, ':code' => $code ] )->one();
	}
}

?>