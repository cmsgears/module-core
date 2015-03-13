<?php
namespace cmsgears\core\common\models\entities;

class Locale extends NamedActiveRecord {
	
	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {

		return $this->locale_id;
	}

	public function getCode() {

		return $this->locale_code;
	}

	public function setCode( $code ) {

		$this->locale_code = $code;
	}

	public function getName() {

		return $this->locale_name;
	}

	public function setName( $name ) {

		$this->locale_name = $name;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'locale_name', 'locale_code' ], 'required' ],
            [ 'locale_name', 'alphanumhyphenspace' ],
            [ 'locale_name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'locale_name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

	public function attributeLabels() {

		return [
			'locale_name' => 'Name',
			'locale_code' => 'Code'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {

		return CoreTables::TABLE_LOCALE;
	}

	// Locale

	public static function findById( $id ) {

		return self::find()->where( 'locale_id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByCode( $code ) {

		return self::find()->where( 'locale_code=:code', [ ':code' => $code ] )->one();
	}

	public static function findByName( $name ) {

		return self::find()->where( 'locale_name=:name', [ ':name' => $name ] )->one();
	}
}

?>