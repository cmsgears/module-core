<?php
namespace cmsgears\core\common\models\entities;

/**
 * Locale Entity
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 */
class Locale extends NamedActiveRecord {
	
	// Instance Methods --------------------------------------------

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

		return CoreTables::TABLE_LOCALE;
	}

	// Locale ----------------------------

	/**
	 * @return Locale by code
	 */
	public static function findByCode( $code ) {

		return self::find()->where( 'code=:code', [ ':code' => $code ] )->one();
	}
}

?>