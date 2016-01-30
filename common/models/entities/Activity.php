<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\CategoryTrait;

/**
 * Activity Entity
 *
 * @property integer $id
 * @property integer $templateId
 * @property integer $userId
 * @property string $type
 * @property integer $consumed
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $scheduledAt
 * @property string $data
 */
class Activity extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Template
	 */
	public function getTemplate() {

		return $this->hasOne( Template::className(), [ 'id' => 'templateId' ] );
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
	public function getConsumedStr() {

		return Yii::$app->formatter->asBoolean( $this->consumed ); 
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
 				'updatedAtAttribute' => 'modifiedAt',
 				'value' => new Expression('NOW()')
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'userId', 'type' ], 'required' ],
			[ [ 'data' ], 'safe' ],
			[ [ 'type' ], 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_MEDIUM ],
			[ 'consumed', 'boolean' ],
            [ [ 'userId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'templateId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'createdAt', 'modifiedAt', 'scheduledAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'templateId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TEMPLATE ),
			'userId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_USER ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'consumed' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CONSUMED ),
			'data' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_ACTIVITY;
	}

	// Activity --------------------------
}

?>