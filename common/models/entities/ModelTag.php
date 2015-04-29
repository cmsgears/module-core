<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

/**
 * ModelTag Entity
 *
 * @property integer $parentId
 * @property integer $parentType
 * @property string $name
 * @property string $description
 */
class ModelTag extends CmgEntity {

	// Instance Methods --------------------------------------------

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'parentId', 'parentType', 'name' ], 'required' ],
			[ [ 'description' ], 'safe' ],
            [ [ 'parentId', 'parentType' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'string', 'min'=>1, 'max'=>100 ]
        ];
    }

	public function attributeLabels() {

		return [
			'categoryId' => 'Category',
			'parentId' => 'Parent',
			'parentType' => 'Type'
		];
	}

	// Category --------------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CoreTables::TABLE_MODEL_TAG;
	}

	// Category --------------------------

	public static function findByParentIdType( $id, $type ) {

		return self::find()->where( 'parentId=:id AND parentType=:type', [ ':id' => $id, ':type' => $type ] )->all();
	}

	public static function deleteByParentIdType( $id, $type ) {

		self::deleteAll( 'parentId=:id AND parentType=:type', [ ':id' => $id, ':type' => $type ] );
	}
}

?>