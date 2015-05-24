<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

/**
 * ModelTag Entity
 *
 * @property int $tagId
 * @property int $parentId
 * @property string $parentType
 */
class ModelTag extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'tagId', 'parentId', 'parentType' ], 'required' ],
            [ [ 'tagId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'max' => 100 ]
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'tagId' => 'Tag',
			'parentId' => 'Parent',
			'parentType' => 'Parent Type'
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