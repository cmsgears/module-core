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
 */
class ModelFile extends CmgEntity {

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
            [ [ 'id', 'order' ], 'safe' ],
            [ [ 'fileId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'min' => 1, 'max' => 100 ]
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
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER )
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
	
	// Read ----

	/**
	 * @return ModelFile - by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	/**
	 * @param int $parentId
	 * @param string $parentType
	 * @return array - ModelFile by parent id and type
	 */
	public static function findByParentIdType( $parentId, $parentType ) {

		return self::find()->where( 'parentId=:id AND parentType=:type', [ ':id' => $parentId, ':type' => $parentType ] )->all();
	}
	
	// Delete ----

	/**
	 * Delete all the entries associated with the parent.
	 */
	public static function deleteByParentIdType( $parentId, $parentType ) {

		self::deleteAll( 'parentId=:id AND parentType=:type', [ ':id' => $parentId, ':type' => $parentType ] );
	}

	/**
	 * Delete all entries related to a file
	 */
	public static function deleteByFileId( $fileId ) {

		self::deleteAll( 'fileId=:id', [ ':id' => $fileId ] );
	}
}

?>