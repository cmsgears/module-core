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
 * @property int $id
 * @property int $userId
 * @property int $typeId
 * @property string $message
 * @property datetime $createdAt
 * @property datetime $modifiedAt 
 * @property datetime $time
 * @property boolean $flag
 */
class Reminder extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return User
	 */
	public function getUser() {

		return $this->hasOne( User::className(), [ 'id' => 'userId' ] )->from( CoreTables::TABLE_USER . ' user' );
	}

	/**
	 * @return Option
	 */
	public function getType() {

		return $this->hasOne( Option::className(), [ 'id' => 'typeId' ] )->from( CoreTables::TABLE_OPTION . ' type' );
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
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'userId', 'typeId', 'message', 'time' ], 'required' ],
			[ [ 'id', 'flag' ], 'safe' ],
            [ [ 'userId', 'typeId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'userId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'typeId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'message' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
			'time' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TIME ),
			'flag' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MARK )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_REMINDER;
	}

	// Reminder --------------------------

	/**
	 * @return Reminder - by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}
}

?>