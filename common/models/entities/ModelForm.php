<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelForm Entity
 *
 * @property integer $id
 * @property integer $formId
 * @property integer $parentId
 * @property string $parentType
 * @property short $order
 */
class ModelForm extends CmgModel {

	// Instance Methods --------------------------------------------

	public function getTag() {

		return $this->hasOne( Form::className(), [ 'id' => 'formId' ] );
	}
	
	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'formId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id', 'order' ], 'safe' ],
            [ [ 'formId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'min' => 1, 'max' => 100 ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'formId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_FORM ),
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER )
		];
	}

	// ModelForm -------------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_FORM;
	}

	// ModelForm -------------------------

	// Read ----

}

?>