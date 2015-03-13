<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

class Notification extends ActiveRecord {

	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {

		return $this->notification_id;
	}

	public function getNotifierId() {

		return $this->notification_from;
	}

	public function getNotifier() {

		return $this->hasOne( User::className(), [ 'user_id' => 'notification_from' ] );
	}

	public function setNotifierId( $userId ) {

		$this->notification_from = $userId;
	}

	public function getUserId() {

		return $this->notification_user;
	}

	public function getUser() {

		return $this->hasOne( User::className(), [ 'user_id' => 'notification_user' ] );
	}

	public function setUserId( $userId ) {

		$this->notification_user = $userId;
	}

	public function getMessage() {

		return $this->notification_message;
	}

	public function setMessage( $message ) {

		$this->notification_message = $message;
	}

	public function getTypeId() {

		return $this->notification_type;
	}

	public function getType() {

		return $this->hasOne( Option::className(), [ 'option_id' => 'notification_type' ] );
	}

	public function setTypeId( $type ) {

		$this->notification_type = $type;
	}

	public function getTime() {

		return $this->notification_time;
	}

	public function setTime( $time ) {

		$this->notification_time = $time;
	}

	public function getFlag() {

		return $this->notification_flag;
	}

	public function getFlagStr() {

		return $this->notification_flag ? 'yes' : 'no';
	}

	public function setFlag( $flag ) {

		$this->notification_flag = $flag;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'notification_user', 'notification_message', 'notification_type' ], 'required' ],
			[ [ 'notification_from', 'notification_flag' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'notification_user' => 'User',
			'notification_message' => 'Message',
			'notification_type' => 'Type',
			'notification_flag' => 'Read'
		];
	}

	// Static Methods ----------------------------------------------

	public static function tableName() {

		return CoreTables::TABLE_NOTIFICATION;
	}

	// Notification

	public static function findById( $id ) {

		return self::find()->where( 'notification_id=:id', [ ':id' => $id ] )->one();
	}
}

?>