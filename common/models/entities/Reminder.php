<?php
namespace cmsgears\modules\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

class Reminder extends ActiveRecord {

	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {

		return $this->reminder_id;
	}

	public function getUserId() {

		return $this->reminder_user;
	}

	public function getUser() {

		return $this->hasOne( User::className(), [ 'user_id' => 'reminder_user' ] );
	}

	public function setUserId( $userId ) {

		$this->reminder_user = $userId;
	}

	public function getMessage() {

		return $this->reminder_message;
	}

	public function setMessage( $message ) {

		$this->reminder_message = $message;
	}

	public function getTypeId() {

		return $this->reminder_type;
	}

	public function getType() {

		return $this->hasOne( Option::className(), [ 'option_id' => 'reminder_type' ] );
	}

	public function setTypeId( $type ) {

		$this->reminder_type = $type;
	}

	public function getTime() {

		return $this->reminder_time;
	}

	public function setTime( $time ) {

		$this->reminder_time = $time;
	}

	public function getFlag() {

		return $this->reminder_flag;
	}

	public function getFlagStr() {

		return $this->reminder_flag ? 'yes' : 'no';
	}

	public function setFlag( $flag ) {

		$this->reminder_flag = $flag;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'reminder_user', 'reminder_message', 'reminder_type' ], 'required' ],
			[ [ 'reminder_flag' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'reminder_user' => 'User',
			'reminder_message' => 'Message',
			'reminder_type' => 'Type',
			'reminder_flag' => 'Read'
		];
	}

	// Static Methods ----------------------------------------------

	public static function tableName() {

		return CoreTables::TABLE_REMINDER;
	}

	// Reminder

	public static function findById( $id ) {

		return self::find()->where( 'reminder_id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByUserId( $userId ) {

		return self::find()->where( 'reminder_user=:id', [ ':id' => $userId ] )->one();
	}
}

?>