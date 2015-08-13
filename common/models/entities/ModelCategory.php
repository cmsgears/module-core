<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * ModelCategory Entity
 *
 * @property integer $id
 * @property integer $categoryId
 * @property integer $parentId
 * @property string $parentType
 */
class ModelCategory extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Category - associated category
	 */
	public function getCategory() {

    	return $this->hasOne( Category::className(), [ 'id' => 'categoryId' ] );
	}

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'categoryId', 'parentId', 'parentType' ], 'required' ],
            [ 'id', 'safe' ],
            [ [ 'categoryId', 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
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
			'categoryId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_CATEGORY )
		];
	}

	// ModelCategory ---------------------

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_MODEL_CATEGORY;
	}

	// ModelCategory ---------------------

	// Read ----

	/**
	 * @return ModelCategory - by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

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