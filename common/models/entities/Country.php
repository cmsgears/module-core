<?php
namespace cmsgears\core\common\models\entities;

class Country extends NamedActiveRecord {
	
	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {

		return $this->country_id;
	}

	public function getCode() {

		return $this->country_code;
	}

	public function setCode( $code ) {

		$this->country_code = $code;
	}

	public function getName() {

		return $this->country_name;
	}

	public function setName( $name ) {

		$this->country_name = $name;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'country_name', 'country_code' ], 'required' ],
            [ 'country_name', 'alphanumhyphenspace' ],
            [ 'country_name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'country_name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

	public function attributeLabels() {

		return [
			'country_name' => 'Name',
			'country_code' => 'Code'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {
		
		return CoreTables::TABLE_COUNTRY;
	}
	
	// Country

	public static function findById( $id ) {

		return self::find()->where( 'country_id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByCode( $code ) {

		return self::find()->where( 'country_code=:code', [ ':code' => $code ] )->one();
	}

	public static function findByName( $name ) {

		return self::find()->where( 'country_name=:name', [ ':name' => $name ] )->one();
	}
}

?>