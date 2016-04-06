<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\Tag;

/**
 * ModelTag Entity
 *
 * @property long $id
 * @property long $tagId
 * @property long $parentId
 * @property string $parentType
 * @property short $order
 * @property boolean $active
 */
class ModelTag extends \cmsgears\core\common\models\base\CmgModel {

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
            [ [ 'tagId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ 'parentType', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'active' ], 'boolean' ],
            [ [ 'tagId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'tagId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TAG ),
            'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
            'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
        ];
    }

    // ModelTag --------------------------

    public function getTag() {

        return $this->hasOne( Tag::className(), [ 'id' => 'tagId' ] );
    }

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_MODEL_TAG;
    }

    // ModelTag --------------------------

    // Create -------------

    // Read ---------------

    public static function findByTagId( $parentId, $parentType, $tagId ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND tagId=:tid', [ ':pid' => $parentId, ':ptype' => $parentType, ':tid' => $tagId ] )->one();
    }

    public static function findActiveByParentId( $parentId ) {

        return self::find()->where( 'parentId=:pid AND active=1', [ ':pid' => $parentId ] )->all();
    }

    public static function findActiveByParent( $parentId, $parentType ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND active=1', [ ':pid' => $parentId, ':ptype' => $parentType ] )->all();
    }

    public static function findActiveByTagIdParentType( $tagId, $parentType ) {

        return self::find()->where( 'tagId=:tid AND parentType=:ptype AND active=1',  [ ':tid' => $tagId, ':ptype' => $parentType] )->all();
    }

    // Update -------------

    // Delete -------------

    /**
     * Delete all entries related to a tag. It's required while deleting a tag.
     */
    public static function deleteByTagId( $tagId ) {

        self::deleteAll( 'tagId=:tid', [ ':tid' => $tagId ] );
    }
}

?>