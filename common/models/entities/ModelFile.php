<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

/**
 * ModelFile Entity
 *
 * @property integer $parentId
 * @property integer $parentType
 * @property integer $fileId   
 */
class ModelFile extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'parentId', 'parentType', 'fileId' ], 'required' ],
            [ [ 'parentId', 'parentType', 'fileId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];
    }

	public function attributeLabels() {

		return [
			'parentId' => 'Parent',
			'parentType' => 'Type',
			'fileId' => 'File'
		];
	}

	// Category --------------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CoreTables::TABLE_MODEL_FILE;
	}

	// Category --------------------------

	public static function findByParentIdType( $id, $type ) {

		return self::find()->where( 'parentId=:id AND parentType=:type', [ ':id' => $id, ':type' => $type ] )->all();
	}

	public static function deleteByParentIdType( $id, $type ) {

		self::deleteAll( 'parentId=:id AND parentType=:type', [ ':id' => $id, ':type' => $type ] );
	}

	public static function deleteByFileId( $fileId ) {

		self::deleteAll( 'fileId=:id', [ ':id' => $fileId ] );
	}
}

?>