<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelFile Entity
 *
 * @property integer $id
 * @property integer $fileId
 * @property integer $parentId
 * @property string $parentType
 * @property short $order
 * @property short $active
 */
class ModelFile extends CmgModel {

	// Instance Methods --------------------------------------------

	/**
	 * @return CmgFile - associated file
	 */
	public function getFile() {

    	return $this->hasOne( CmgFile::className(), [ 'id' => 'fileId' ] );
	}

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'fileId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'id', 'active' ], 'safe' ],
            [ [ 'fileId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'min' => 1, 'max' => 100 ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'fileId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_FILE ),
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
		];
	}

	// ModelFile -------------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_FILE;
	}

	// ModelFile -------------------------
	
	// Read ------

	public static function findByFileId( $parentId, $parentType, $fileId ) {

		return self::find()->where( 'parentId=:pid AND parentType=:ptype AND fileId=:fid', [ ':pid' => $parentId, ':ptype' => $parentType, ':fid' => $fileId ] )->one(); 
	}

	public static function findByFileTitle( $parentId, $parentType, $fileTitle ) {

		return self::find()->joinWith( 'file' )->where( 'parentId=:pid AND parentType=:ptype AND title=:title', [ ':pid' => $parentId, ':ptype' => $parentType, ':title' => $fileTitle ] )->one(); 
	}

	// Delete ----

	/**
	 * Delete all entries related to a file
	 */
	public static function deleteByFileId( $fileId ) {

		self::deleteAll( 'fileId=:id', [ ':id' => $fileId ] );
	}
}

?>