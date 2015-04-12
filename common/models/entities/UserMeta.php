<?php
namespace cmsgears\core\common\models\entities;

/**
 * UserMeta Entity
 *
 * @property integer $userId
 * @property string $key
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
            [ [ 'userId', 'key' ], 'required' ],
			[ [ 'value' ], 'safe' ],
            [ 'userId', 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'key', 'alphanumhyphenspace' ],
            [ 'key', 'length', 'min'=>1, 'max'=>100 ]
        ];
    }

	public function attributeLabels() {

		return [
			'key' => 'Key',
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

	public static function findByUserKey( $user, $key ) {

		return self::find()->where( [ 'userId=:id', 'key=:key' ] )
							->addParams( [ ':id' => $user->id, ':key' => $key ] )
							->one();
	}

	public static function findByUserIdKey( $userId, $key ) {

		return self::find()->where( [ 'userId=:id', 'key=:key' ] )
							->addParams( [ ':id' => $userId, ':key' => $key ] )
							->one();
	}

	// Delete

	public static function deleteByUser( $user ) {

		self::deleteAll( 'userId=:id', [ ':id' => $user->id ] );
	}

	public static function deleteByUserId( $id ) {

		self::deleteAll( 'userId=:id', [ ':id' => $userId ] );
	}

	public static function deleteByUserKey( $user, $key ) {

		self::deleteAll( 'userId=:id AND key=:key', [ ':id' => $user->id, ':key' => $key ] );
	}

	public static function deleteByUserIdKey( $id, $key ) {

		self::deleteAll( 'userId=:id AND key=:key', [ ':id' => $id, ':key' => $key ] );
	}
}

?>