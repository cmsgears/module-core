<?php
namespace cmsgears\core\common\models\entities;

/**
 * Reminder Entity
 *
 * @property int $id
 * @property int $notifierId
 * @property int $userId
 * @property string $type
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

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'notifierId', 'userId', 'message', 'type' ], 'required' ],
			[ [ 'flag' ], 'safe' ],
            [ 'type', 'string', 'min'=>1, 'max'=>100 ],
			[ 'createdAt', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss' ]
        ];
    }

	/**
	 * Model attributes
	 */
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

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_NOTIFICATION;
	}

	// Notification ----------------------
	
	/**
	 * @return Notification - by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}
}

?>