<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelComment Entity
 *
 * @property integer $id
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property integer $replyParentId
 * @property integer $parentId
 * @property string $parentType
 * @property string $ip
 * @property string $name
 * @property string $email
 * @property string $message
 * @property datetime $createdAt
 * @property datetime $modifiedat
 */
class ModelComment extends CmgModel {

	// Instance Methods --------------------------------------------

	public function getReplyParent() {

		return $this->hasOne( ModelComment::className(), [ 'id' => 'replyParentId' ] );
	}
	
	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'parentId', 'parentType', 'message' ], 'required' ],
            [ 'id', 'ip', 'name', 'email', 'safe' ],
            [ [ 'parentId', 'replyParentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'min' => 1, 'max' => 100 ],
			[ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'replyParentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'email' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_EMAIL ),
			'ip' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_IP ),
			'message' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MESSAGE )
		];
	}

	// ModelTag --------------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_COMMENT;
	}

	// ModelComment ----------------------

	// Read ------
}

?>