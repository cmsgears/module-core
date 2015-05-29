<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

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
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'code', 'name' ], 'required' ],
            [ 'id', 'safe' ],
            [ 'code', 'string', 'min'=>1, 'max'=>50 ],
            [ 'name', 'string', 'min'=>1, 'max'=>100 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'code' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CODE ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_LOCALE;
	}

	// Locale ----------------------------

	// Read ----

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