<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\User;

/**
 * ModelActivity Entity
 *
 * @property long $id
 * @property long $userId
 * @property long $parentId
 * @property string $parentType
 * @property short $type
 * @property string $ip
 * @property string $agent
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 */
class ModelActivity extends \cmsgears\core\common\models\base\CmgModel {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    /**
     * @inheritdoc
     */
    public function rules() {

        return [
            [ [ 'userId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id', 'content' ], 'safe' ],
            [ [ 'parentType', 'type', 'ip' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ [ 'agent' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->extraLargeText ],
            [ [ 'activityId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'userId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_USER ),
            'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'ip' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_IP ),
            'agent' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_AGENT_BROWSER ),
            'content' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CONTENT )
        ];
    }

    // ModelActivity ---------------------

    /**
     * @return User - associated user
     */
    public function getUser() {

        return $this->hasOne( Activity::className(), [ 'id' => 'userId' ] );
    }

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_MODEL_ACTIVITY;
    }

    // ModelActivity ---------------------

    // Create -------------

    // Read ---------------

    // Update -------------

    // Delete -------------
}

?>