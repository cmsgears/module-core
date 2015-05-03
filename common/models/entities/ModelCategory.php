<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

/**
 * ModelCategory Entity
 *
 * @property integer $parentId
 * @property string $parentType
 * @property integer $categoryId   
 */
class ModelCategory extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'parentId', 'parentType', 'categoryId' ], 'required' ],
            [ [ 'parentId', 'categoryId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'max' => 100 ]
        ];
    }

	public function attributeLabels() {

		return [
			'parentId' => 'Parent',
			'parentType' => 'Type',
			'categoryId' => 'Category'
		];
	}

	// Category --------------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CoreTables::TABLE_MODEL_CATEGORY;
	}

	// Category --------------------------

	public static function findByParentIdType( $id, $type ) {

		return self::find()->where( 'parentId=:id AND parentType=:type', [ ':id' => $id, ':type' => $type ] )->all();
	}

	public static function deleteByParentIdType( $id, $type ) {

		self::deleteAll( 'parentId=:id AND parentType=:type', [ ':id' => $id, ':type' => $type ] );
	}

	public static function deleteByCategoryId( $categoryId ) {

		self::deleteAll( 'categoryId=:id', [ ':id' => $categoryId ] );
	}
}

?>