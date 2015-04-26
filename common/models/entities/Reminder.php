<?php
namespace cmsgears\core\common\models\entities;

/**
 * Reminder Entity
 *
 * @property integer $id
 * @property integer $userId
 * @property short $type
 * @property string $message
 * @property datetime $createdAt
 * @property datetime $time
 * @property boolean $flag
 */
class Reminder extends CmgEntity {

	// Instance Methods --------------------------------------------

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

		return $this->flag ? 'yes' : 'no';
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'userId', 'message', 'type', 'time' ], 'required' ],
			[ [ 'id', 'flag' ], 'safe' ],
			[ 'createdAt', 'date', 'format' => 'yyyy-MM-dd HH:mm:ss' ]
        ];
    }

	public function attributeLabels() {

		return [
			'userId' => 'User',
			'message' => 'Message',
			'type' => 'Type',
			'flag' => 'Read',
			'time' => 'Time'
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CoreTables::TABLE_REMINDER;
	}

	// Reminder --------------------------

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}
}

?>