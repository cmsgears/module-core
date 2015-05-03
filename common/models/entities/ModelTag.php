<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

/**
 * ModelTag Entity
 *
 * @property integer $parentId
 * @property string $parentType
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
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ 'parentType', 'string', 'max' => 100 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'string', 'min'=>1, 'max'=>100 ]
        ];
    }

	public function attributeLabels() {

		return [
			'categoryId' => 'Category',
			'parentId' => 'Parent',
			'parentType' => 'Parent Type'
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