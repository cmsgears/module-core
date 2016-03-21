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
 * @property long $id
 * @property long $templateId
 * @property string $type
 * @property string $ip
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property datetime $scheduledAt
 * @property string $data
 */
class Activity extends CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------
    use TemplateTrait;
    use DataTrait;

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

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
            [ [ 'type', 'ip' ], 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_MEDIUM ],
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
            'ip' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_IP ),
            'data' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DATA )
        ];
    }

    // Activity --------------------------

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_ACTIVITY;
    }

    // Activity --------------------------

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------
}

?>