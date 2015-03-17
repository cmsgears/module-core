<?php
namespace cmsgears\core\common\models\entities;

class Notification extends CmgEntity {

	// Instance Methods --------------------------------------------

	public function getNotifier() {

		return $this->hasOne( User::className(), [ 'id' => 'notifierId' ] );
	}

	public function getUser() {

		return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
	}

	public function getFlagStr() {

		return $this->reminder_flag ? 'yes' : 'no';
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'userId', 'message', 'type' ], 'required' ],
			[ [ 'notifierId', 'flag' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'userId' => 'User',
			'message' => 'Message',
			'type' => 'Type',
			'flag' => 'Read'
		];
	}

	// Static Methods ----------------------------------------------

	public static function tableName() {

		return CoreTables::TABLE_NOTIFICATION;
	}

	// Notification

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}
}

?>