<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Reminder Entity
 *
 * @property integer $id
 * @property integer $notifierId
 * @property integer $userId
 * @property integer $typeId
 * @property string $message
 * @property datetime $createdAt
 * @property datetime $modifiedAt 
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
	 * @return Option
	 */
	public function getType() {

		return $this->hasOne( Option::className(), [ 'id' => 'typeId' ] );
	}

	/**
	 * @return string representation of flag
	 */
	public function getFlagStr() {

		return Yii::$app->formatter->asBoolean( $this->flag ); 
	}

	/**
	 * @return boolean - whether given user is owner
	 */
	public function checkOwner( $user ) {

		return $this->userId	= $user->id;		
	}

	// yii\base\Component ----------------

    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'createdAt',
 				'updatedAtAttribute' => 'modifiedAt'
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'notifierId', 'userId', 'typeId', 'message' ], 'required' ],
			[ [ 'flag' ], 'safe' ],
            [ [ 'notifierId', 'userId', 'typeId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'notifierId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NOTIFIER ),
			'userId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'typeId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'message' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
			'flag' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MARK )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
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