<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\CmgFile;

/**
 * ModelFile Entity
 *
 * @property long $id
 * @property long $fileId
 * @property long $parentId
 * @property string $parentType
 * @property short $order
 * @property boolean $active
 */
class ModelFile extends \cmsgears\core\common\models\base\CmgModel {

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
            [ [ 'fileId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ 'parentType', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'active' ], 'boolean' ],
            [ [ 'fileId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'fileId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_FILE ),
            'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
            'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
        ];
    }

    // ModelFile -------------------------

    /**
     * @return CmgFile - associated file
     */
    public function getFile() {

        return $this->hasOne( CmgFile::className(), [ 'id' => 'fileId' ] );
    }

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_MODEL_FILE;
    }

    // ModelFile -------------------------

    // Create -------------

    // Read ---------------

    public static function findByFileId( $parentId, $parentType, $fileId ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND fileId=:fid', [ ':pid' => $parentId, ':ptype' => $parentType, ':fid' => $fileId ] )->one();
    }

    public static function findByFileTitle( $parentId, $parentType, $fileTitle ) {

        return self::find()->joinWith( 'file' )->where( 'parentId=:pid AND parentType=:ptype AND title=:title', [ ':pid' => $parentId, ':ptype' => $parentType, ':title' => $fileTitle ] )->one();
    }

    public static function findByFileTitleLike( $parentId, $parentType, $likeTitle ) {

        return self::find()->joinWith( 'file' )->where( 'parentId=:pid AND parentType=:ptype AND title LIKE :title', [ ':pid' => $parentId, ':ptype' => $parentType, ':title' => $likeTitle ] )->all();
    }

    // Update -------------

    // Delete -------------

    /**
     * Delete all entries related to a file
     */
    public static function deleteByFileId( $fileId ) {

        self::deleteAll( 'fileId=:id', [ ':id' => $fileId ] );
    }
}

?>