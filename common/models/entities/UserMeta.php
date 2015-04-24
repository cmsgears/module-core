<?php
namespace cmsgears\core\common\models\entities;

/**
 * UserMeta Entity
 *
 * @property integer $userId
 * @property string $name
 * @property string $value
 */
class UserMeta extends CmgEntity {

	// Instance methods --------------------------------------------------

	/**
	 * @return User set as meta parent.
	 */
	public function getUser() {

		return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'userId', 'name' ], 'required' ],
			[ [ 'value' ], 'safe' ],
            [ 'userId', 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'string', 'min'=>1, 'max'=>100 ]
        ];
    }

	public function attributeLabels() {

		return [
			'name' => 'Name',
			'value' => 'Value'
		];
	}

	// Static methods --------------------------------------------------

	// yii\db\ActiveRecord ----------------

	public static function tableName() {

		return CoreTables::TABLE_USER_META;
	}

	// UserMeta ---------------------------

	// Find

	public static function findAllByUser( $user ) {

		return self::find()->where( 'userId=:id', [ ':id' => $user->id ] )->all();
	}

	public static function findAllByUserId( $userId ) {

		return self::find()->where( 'userId=:id', [ ':id' => $userId ] )->all();
	}

	public static function findByUsername( $user, $name ) {

		return self::find()->where( 'userId=:id AND name=:name', [ ':id' => $user->id, ':name' => $name ] )->one();
	}

	public static function findByNameUserId( $name, $userId ) {

		return self::find()->where( 'name=:name AND userId=:id', [ ':name' => $name, ':id' => $userId ] )->one();
	}

	// Delete

	public static function deleteByUser( $user ) {

		self::deleteAll( 'userId=:id', [ ':id' => $user->id ] );
	}

	public static function deleteByUserId( $id ) {

		self::deleteAll( 'userId=:id', [ ':id' => $userId ] );
	}

	public static function deleteByNameUser( $name, $user ) {

		self::deleteAll( 'name=:name AND userId=:id', [ ':name' => $name, ':id' => $user->id ] );
	}

	public static function deleteByNameUserId( $name, $id ) {

		self::deleteAll( 'name=:name AND userId=:id', [ ':name' => $name, ':id' => $id ] );
	}
}

?>