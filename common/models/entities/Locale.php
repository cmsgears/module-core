<?php
namespace cmsgears\core\common\models\entities;

/**
 * Locale Entity
 *
 * @property int $id
 * @property string $code
 * @property string $name
 */
class Locale extends NamedCmgEntity {
	
	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'code', 'name' ], 'required' ],
            [ 'id', 'safe' ],
            [ 'code', 'string', 'min'=>1, 'max'=>25 ],
            [ 'name', 'string', 'min'=>1, 'max'=>100 ],
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

		return CoreTables::TABLE_LOCALE;
	}

	// Locale ----------------------------

	/**
	 * @return Locale - by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	/**
	 * @return Locale - by code
	 */
	public static function findByCode( $code ) {

		return self::find()->where( 'code=:code', [ ':code' => $code ] )->one();
	}
}

?>