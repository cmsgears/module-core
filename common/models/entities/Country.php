<?php
namespace cmsgears\core\common\models\entities;

class Country extends NamedActiveRecord {
	
	// Instance Methods --------------------------------------------

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'name', 'code' ], 'required' ],
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

	// yii\db\ActiveRecord

	public static function tableName() {
		
		return CoreTables::TABLE_COUNTRY;
	}
	
	// Country

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByCode( $code ) {

		return self::find()->where( 'code=:code', [ ':code' => $code ] )->one();
	}

	public static function findByName( $name ) {

		return self::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}
}

?>