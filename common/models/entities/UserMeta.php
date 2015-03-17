<?php
namespace cmsgears\core\common\models\entities;

class UserMeta extends CmgEntity {

	// Instance methods --------------------------------------------------

	public function getUser() {

		return $this->hasOne( User::className(), [ 'id' => 'parentIdId' ] );
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'parentId', 'key' ], 'required' ],
            [ 'parentId', 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'key', 'alphanumhyphenspace' ],
			[ [ 'value' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'key' => 'Key',
			'value' => 'Value'
		];
	}

	// Static methods --------------------------------------------------

	public static function tableName() {

		return CoreTables::TABLE_USER_META;
	}

	// UserMeta ----------

	// Find

	public static function findAllByUser( $user ) {

		return self::find()->where( [ 'parentId' => $user->getId() ] )->all();
	}

	public static function findAllByUserId( $userId ) {

		return self::find()->where( 'parentId=:id', [ ':id' => $userId ] )->all();
	}

	public static function findByUserKey( $user, $key ) {

		return self::find()->where( [ 'parentId' => $user->getId() ] )
							->andWhere( 'key=:key', [ ':key' => $key ] )->one();
	}

	public static function findByUserIdKey( $userId, $key ) {

		return self::find()->where( [ 'parentId=:id', [ ':id' => $userId ] ] )
							->andWhere( 'key=:key', [ ':key' => $key ] )->one();
	}

	// Delete

	public static function deleteByUser( $user ) {

		self::deleteAll()->where( [ 'parentId' => $user->getId() ] );
	}

	public static function deleteByUserId( $id ) {

		self::deleteAll( [ 'parentId=:id', [ ':id' => $userId ] ] );
	}

	public static function deleteByUserKey( $user, $key ) {

		self::deleteAll( [ 'parentId' => $user->getId(), 'key' => $key ] );
	}

	public static function deleteByUserIdKey( $id, $key ) {

		self::deleteAll( [ 'parentId' => $id, 'key' => $key ] );
	}
}

?>