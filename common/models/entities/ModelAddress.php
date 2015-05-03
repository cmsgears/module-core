<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

/**
 * ModelFile Entity
 *
 * @property integer $parentId
 * @property string $parentType
 * @property integer $addressId
 * @property integer $type   
 */
class ModelAddress extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'parentId', 'parentType', 'addressId' ], 'required' ],
            [ [ 'parentId', 'addressId', 'type' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'max' => 100 ]
        ];
    }

	public function attributeLabels() {

		return [
			'parentId' => 'Parent',
			'parentType' => 'Parent Type',
			'addressId' => 'Address',
			'type' => 'Address Type'
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