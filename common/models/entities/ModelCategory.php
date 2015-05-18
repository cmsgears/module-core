<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

/**
 * ModelCategory Entity
 *
 * @property int $parentId
 * @property string $parentType
 * @property int $categoryId   
 */
class ModelCategory extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'parentId', 'parentType', 'categoryId' ], 'required' ],
            [ [ 'parentId', 'categoryId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'max' => 100 ]
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'parentId' => 'Parent',
			'parentType' => 'Parent Type',
			'categoryId' => 'Category'
		];
	}

	// ModelCategory ---------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_CATEGORY;
	}

	// ModelCategory ---------------------

	// Read ----

	/**
	 * @param int $parentId
	 * @param string $parentType
	 * @return array - ModelCategory by parent id and type
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
	 * Delete all entries related to a category
	 */
	public static function deleteByCategoryId( $categoryId ) {

		self::deleteAll( 'categoryId=:id', [ ':id' => $categoryId ] );
	}
}

?>