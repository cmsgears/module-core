<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Category Entity
 *
 * @property integer $id
 * @property integer $parentId
 * @property string $name
 * @property string $description
 * @property integer $type
 */
class Category extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Category - parent category
	 */
	public function getParent() {

		return $this->hasOne( Category::className(), [ 'id' => 'parentId' ] );
	}

	/**
	 * @return array - list of Option having all the options belonging to this category
	 */
	public function getOptions() {

    	return $this->hasMany( Option::className(), [ 'categoryId' => 'id' ] );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description', 'type' ], 'safe' ],
            [ 'name', 'alphanumspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ 'parentId', 'number', 'integerOny' => true, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_SELECT ) ]
        ];
    }

	public function attributeLabels() {

		return [
			'name' => 'Name',
			'parentId' => 'Parent Category',
			'description' => 'Description',
			'type' => 'Type'
		];
	}

	// Category --------------------------
	
	/**
	 * Validates to ensure that name is used only for one category for a particular type
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeName( $this->type, $this->name ) ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that name is used only for one category for a particular type
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingCategory = self::findByTypeName( $this->type, $this->name );

			if( isset( $existingCategory ) && $existingCategory->id != $this->id && 
				strcmp( $existingCategory->name, $this->name ) == 0 && $existingCategory->type == $this->type ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	public static function tableName() {

		return CoreTables::TABLE_CATEGORY;
	}

	// Category --------------------------

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByName( $name ) {

		return self::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}

	public static function findByType( $type ) {

		return self::find()->where( 'type=:type', [ ':type' => $type ] )->all();
	}

	public static function findByTypeName( $type, $name ) {

		return self::find()->where( [ 'name=:name', 'type=:type' ] )
							->addParams( [ ':name' => $name, ':type' => $type ] )
							->one();
	}
	
	public static function isExistByTypeName( $type, $name ) {
		
		$category = self::findByTypeName( $type, $name );

		return isset( $category );
	}
}

?>