<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Category Entity
 *
 * @property int $id
 * @property int $parentId
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $icon
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
	 * @return array - list of immediate child categories
	 */
	public function getCategories() {

    	return $this->hasMany( Category::className(), [ 'parentId' => 'id' ] );
	}

	/**
	 * @return array - list of Option having all the options belonging to this category
	 */
	public function getOptions() {

    	return $this->hasMany( Option::className(), [ 'categoryId' => 'id' ] );
	}

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description' ], 'safe' ],
            [ 'name', 'alphanumspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ 'parentId', 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'type', 'icon' ], 'string', 'min'=>1, 'max'=>100 ],
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'name' => 'Name',
			'parentId' => 'Parent Category',
			'description' => 'Description',
			'type' => 'Type',
			'icon' => 'Icon'
		];
	}

	// Category --------------------------
	
	/**
	 * Validates to ensure that name is used only for one category for a particular type
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeName( $this->type, $this->name ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
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

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {

		return CoreTables::TABLE_CATEGORY;
	}

	// Category --------------------------

	/**
	 * @return Category - by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	/**
	 * @return Category - by name
	 */
	public static function findByName( $name ) {

		return self::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}

	/**
	 * @return Category - by type
	 */
	public static function findByType( $type ) {

		return self::find()->where( 'type=:type', [ ':type' => $type ] )->all();
	}

	/**
	 * @return Category - by type and name
	 */
	public static function findByTypeName( $type, $name ) {

		return self::find()->where( 'type=:type AND name=:name', [ ':type' => $type, ':name' => $name ] )->one();
	}

	/**
	 * @return Category - checks whether category exist by type and name
	 */
	public static function isExistByTypeName( $type, $name ) {

		$category = self::findByTypeName( $type, $name );

		return isset( $category );
	}
}

?>