<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

class UserMeta extends ActiveRecord {

	// Instance methods --------------------------------------------------

	// db columns
	
	public function getId() {

		return $this->user_meta_id;
	}

	public function getUserId() {

		return $this->user_meta_parent;
	}

	public function setUserId( $id ) {

		$this->user_meta_parent = $id;
	}
	
	public function getKey() {

		return $this->user_meta_key;
	}

	public function setKey( $key ) {

		$this->user_meta_key = $key;
	}

	public function getValue() {

		return $this->user_meta_value;
	}

	public function setValue( $value ) {

		$this->user_meta_value = $value;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'user_meta_parent', 'user_meta_key' ], 'required' ],
            [ 'user_meta_key', 'alphanumhyphenspace' ],
			[ [ 'user_meta_value' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'user_meta_key' => 'Key',
			'user_meta_value' => 'Value'
		];
	}

	// Static methods --------------------------------------------------

	public static function tableName() {

		return CoreTables::TABLE_USER_META;
	}

	// UserMeta ----------

	// Find

	public static function findAllByUser( $user ) {

		return self::find()->where( [ 'user_meta_parent' => $user->getId() ] )->all();
	}

	public static function findAllByUserId( $userId ) {

		return self::find()->where( 'user_meta_parent=:id', [ ':id' => $userId ] )->all();
	}

	public static function findByUserMetaKey( $user, $key ) {

		return self::find()->where( [ 'user_meta_parent' => $user->getId() ] )
							->andWhere( 'user_meta_key=:key', [ ':key' => $key ] )->one();
	}

	public static function findByUserIdMetaKey( $userId, $key ) {

		return self::find()->where( [ 'user_meta_parent=:id', [ ':id' => $userId ] ] )
							->andWhere( 'user_meta_key=:key', [ ':key' => $key ] )->one();
	}

	// Delete

	public static function deleteByUser( $user ) {

		self::deleteAll()->where( [ 'user_meta_parent' => $user->getId() ] );
	}

	public static function deleteByUserId( $id ) {

		self::deleteAll( [ 'user_meta_parent=:id', [ ':id' => $userId ] ] );
	}

	public static function deleteByUserMetaKey( $user, $key ) {

		self::deleteAll( [ 'user_meta_parent' => $user->getId(), 'user_meta_key' => $key ] );
	}

	public static function deleteByUserIdMetaKey( $id, $key ) {

		self::deleteAll( [ 'user_meta_parent' => $id, 'user_meta_key' => $key ] );
	}
}

?>