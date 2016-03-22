<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelOption Entity
 *
 * @property long $id
 * @property long $optionId
 * @property long $parentId
 * @property string $parentType
 * @property short $order
 * @property boolean $active
 */
class ModelOption extends CmgModel {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    /**
     * @return Option - associated option
     */
    public function getCategory() {

        return $this->hasOne( Category::className(), [ 'id' => 'optionId' ] );
    }

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    /**
     * @inheritdoc
     */
    public function rules() {

        return [
            [ [ 'optionId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'optionId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'parentType' ], 'string', 'min' => 1, 'max' => 100 ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'active' ], 'boolean' ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'optionId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_OPTION ),
            'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
            'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
        ];
    }

    // ModelOption -----------------------

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_MODEL_OPTION;
    }

    // ModelOption -----------------------

    // Create -------------

    // Read ---------------

    public static function findByOptionId( $parentId, $parentType, $optionId ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND optionId=:cid', [ ':pid' => $parentId, ':ptype' => $parentType, ':cid' => $optionId ] )->one();
    }

    public static function findActiveByParentId( $parentId ) {

        return self::find()->where( 'parentId=:pid AND active=1', [ ':pid' => $parentId ] )->all();
    }

    public static function findActiveByOptionIdParentType( $optionId, $parentType ) {

        return self::find()->where( 'optionId=:cid AND parentType=:ptype AND active=1', [ ':cid' => $optionId, ':ptype' => $parentType ] )->all();
    }

    public static function findActiveByParentIdParentType( $parentId, $parentType ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND active=1', [ ':pid' => $parentId, ':ptype' => $parentType ] )->all();
    }

    // Update -------------

    // Delete -------------

    /**
     * Delete all entries related to a category
     */
    public static function deleteByOptionId( $optionId ) {

        self::deleteAll( 'optionId=:id', [ ':id' => $optionId ] );
    }
}

?>