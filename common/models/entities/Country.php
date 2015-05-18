<?php
namespace cmsgears\core\common\models\entities;

/**
 * Country Entity
 *
 * @property int $id
 * @property string $code
 * @property string $name
 */
class Country extends NamedCmgEntity {
	
	// Instance Methods --------------------------------------------

	/**
	 * @return array - list of Province having all the provinces belonging to this country
	 */
	public function getProvinces() {

    	return $this->hasMany( Province::className(), [ 'countryId' => 'id' ] );
	}

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'name', 'code' ], 'required' ],
            [ 'id', 'safe' ],
            [ 'code', 'string', 'min'=>1, 'max'=>10 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'code' => 'Code',
			'name' => 'Name'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_COUNTRY;
	}

	// Country ---------------------------

	/**
	 * @return Country by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	/**
	 * @return Country by code
	 */
	public static function findByCode( $code ) {

		return self::find()->where( 'code=:code', [ ':code' => $code ] )->one();
	}
}

?>