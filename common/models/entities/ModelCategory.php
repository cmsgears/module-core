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
 * @property short $order
 */
class ModelCategory extends CmgModel {

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
            [ [ 'id' ], 'safe' ],
            [ [ 'categoryId' ], 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'parentType' ], 'string', 'min' => 1, 'max' => 100 ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ]
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
			'categoryId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CATEGORY ),
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER )
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

	// Read ------
	public static function findByParentType( $parentType ) {
		
		return self::find()->where( 'parentType=:id', [ ':id' => $parentType ] )->all();
	}	
	
	public static function findByParentId( $parentId ) {
		
		return self::find()->where( 'parentId=:id', [ ':id' => $parentId ] )->all();
	}
		
	// Delete ----

	/**
	 * Delete all entries related to a category
	 */
	public static function deleteByCategoryId( $categoryId ) {

		self::deleteAll( 'categoryId=:id', [ ':id' => $categoryId ] );
	}
	
	public static function deleteByParentId( $parentId ) {

		self::deleteAll( 'parentId=:id', [ ':id' => $parentId ] );
	}
}

?>