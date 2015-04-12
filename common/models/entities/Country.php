<?php
namespace cmsgears\core\common\models\entities;

/**
 * Locale Entity
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 */
class Country extends NamedActiveRecord {
	
	// Instance Methods --------------------------------------------

	/**
	 * @return array - list of Province having all the provinces belonging to this country
	 */
	public function getProvinces() {

    	return $this->hasMany( Province::className(), [ 'countryId' => 'id' ] );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'name', 'code' ], 'required' ],
            [ 'id', 'safe' ],
            [ 'code', 'length', 'min'=>1, 'max'=>50 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

	public function attributeLabels() {

		return [
			'name' => 'Name',
			'code' => 'Code'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {
		
		return CoreTables::TABLE_COUNTRY;
	}
	
	// Country ---------------------------
	
	/**
	 * @return Country by code
	 */
	public static function findByCode( $code ) {

		return self::find()->where( 'code=:code', [ ':code' => $code ] )->one();
	}
}

?>