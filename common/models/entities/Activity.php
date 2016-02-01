<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\TemplateTrait;
use cmsgears\core\common\models\traits\DataTrait;

/**
 * Activity Entity
 *
 * @property integer $id
 * @property integer $templateId
 * @property string $type
 * @property integer $consumed
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $scheduledAt
 * @property string $data
 */
class Activity extends CmgEntity {

	use TemplateTrait;

	use DataTrait;

	// Instance Methods --------------------------------------------

	/**
	 * @return string representation of flag
	 */
	public function getConsumedStr() {

		return Yii::$app->formatter->asBoolean( $this->consumed ); 
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
            [ [ 'type' ], 'required' ],
			[ [ 'data' ], 'safe' ],
			[ [ 'type' ], 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_MEDIUM ],
			[ 'consumed', 'boolean' ],
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