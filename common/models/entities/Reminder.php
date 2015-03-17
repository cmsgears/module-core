<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

class Reminder extends ActiveRecord {

	// Instance Methods --------------------------------------------

	public function getUser() {

		return $this->hasOne( User::className(), [ 'id' => 'userId' ] );
	}

	public function getFlagStr() {

		return $this->flag ? 'yes' : 'no';
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'userId', 'message', 'type' ], 'required' ],
			[ [ 'flag' ], 'safe' ]
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

		return CoreTables::TABLE_REMINDER;
	}

	// Reminder

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}
}

?>