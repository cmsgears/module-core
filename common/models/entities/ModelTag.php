<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

/**
 * ModelTag Entity
 *
 * @property int $parentId
 * @property string $parentType
 * @property string $name
 * @property string $description
 */
class ModelTag extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'parentId', 'parentType', 'name' ], 'required' ],
			[ [ 'description' ], 'safe' ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'max' => 100 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'string', 'min'=>1, 'max'=>100 ]
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'categoryId' => 'Category',
			'parentId' => 'Parent',
			'parentType' => 'Parent Type',
			'name' => 'Name',
			'description' => 'Description'
		];
	}

	// Category --------------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_TAG;
	}

	// Category --------------------------
	
	/**
	 * @return array - categories by given parent id and type.
	 */
	public static function findByParentIdType( $id, $type ) {

		return self::find()->where( 'parentId=:id AND parentType=:type', [ ':id' => $id, ':type' => $type ] )->all();
	}

	/**
	 * Delete categories by given parent id and type.
	 */
	public static function deleteByParentIdType( $id, $type ) {

		self::deleteAll( 'parentId=:id AND parentType=:type', [ ':id' => $id, ':type' => $type ] );
	}
}

?>