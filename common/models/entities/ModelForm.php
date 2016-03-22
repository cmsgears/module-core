<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelForm Entity
 *
 * @property long $id
 * @property long $formId
 * @property long $parentId
 * @property string $parentType
 * @property short $order
 * @property boolean $active
 */
class ModelForm extends CmgModel {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    public function getTag() {

        return $this->hasOne( Form::className(), [ 'id' => 'formId' ] );
    }

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    /**
     * @inheritdoc
     */
    public function rules() {

        return [
            [ [ 'formId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'formId' ], 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'min' => 1, 'max' => 100 ],
            [ 'order', 'number', 'integerOnly', 'min' => 0 ],
            [ [ 'active' ], 'boolean' ]
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
            'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
            'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
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

    // Create -------------

    // Read ---------------

    public static function findByFormId( $parentId, $parentType, $formId ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND formId=:fid', [ ':pid' => $parentId, ':ptype' => $parentType, ':fid' => $formId ] )->one();
    }

    // Update -------------

    // Delete -------------

    /**
     * Delete all entries related to a form
     */
    public static function deleteByFormId( $formId ) {

        self::deleteAll( 'formId=:fid', [ ':fid' => $formId ] );
    }
}

?>