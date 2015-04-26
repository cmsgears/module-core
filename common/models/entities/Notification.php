<?php
namespace cmsgears\core\common\models\entities;

/**
 * Reminder Entity
 *
 * @property integer $id
 * @property integer $notifierId
 * @property integer $userId
 * @property short $type
 * @property string $message
 * @property datetime $createdAt
 * @property boolean $flag
 */
class Notification extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return User
	 */
	public function getNotifier() {

		return $this->hasOne( User::className(), [ 'id' => 'notifierId' ] );
	}

	/**
	 * @return User
	 */
	public function getUser() {

		return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
	}

	/**
	 * @return string representation of flag
	 */
	public function getFlagStr() {

		return $this->reminder_flag ? 'yes' : 'no';
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'notifierId', 'userId', 'message', 'type' ], 'required' ],
			[ [ 'flag' ], 'safe' ],
			[ 'createdAt', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss' ]
        ];
    }

	public function attributeLabels() {

		return [
			'notifierId' => 'Notifier',
			'userId' => 'User',
			'message' => 'Message',
			'type' => 'Type',
			'flag' => 'Read'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CoreTables::TABLE_NOTIFICATION;
	}

	// Notification ----------------------

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}
}

?>