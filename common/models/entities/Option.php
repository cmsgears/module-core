<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Option Entity
 *
 * @property int $id
 * @property int $categoryId
 * @property string $name
 * @property string $value
 * @property string $icon
 */
class Option extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Category - The parent category.
	 */
	public function getCategory() {

		return $this->hasOne( Category::className(), [ 'id' => 'categoryId' ] );
	}

	/**
	 * @return Category - The parent category with alias set to 'cat'.
	 */
	public function getCategoryWithAlias() {

		return $this->hasOne( Category::className(), [ 'id' => 'categoryId' ] )->from( CoreTables::TABLE_CATEGORY . ' cat' );
	}

	// yii\base\Model --------------------

	/**
	 * Validation rules
	 */
	public function rules() {

        return [
            [ [ 'name', 'value' ], 'required' ],
            [ [ 'id', 'categoryId' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ [ 'name', 'icon' ], 'string', 'min'=>1, 'max'=>100 ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
        ];
    }

	/**
	 * Model attributes
	 */
	public function attributeLabels() {

		return [
			'categoryId' => 'Category',
			'name' => 'Name',
			'value' => 'Value',
			'icon' => 'Icon'
		];
	}

	// Config ----------------------------

	/**
	 * Validates to ensure that only one option exist for a category with the same name.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameCategoryId( $this->name, $this->categoryId ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one option exist for a category with the same name.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingOption = self::findByNameCategoryId( $this->name, $this->categoryId );

			if( isset( $existingOption ) && $existingOption->id != $this->id && 
				strcmp( $existingOption->name, $this->name ) == 0 && $existingOption->categoryId == $this->categoryId ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static methods --------------------------------------------------

	// yii\db\ActiveRecord ----------------

	/**
	 * @return string - db table name
	 */
	public static function tableName() {
		
		return CoreTables::TABLE_OPTION;
	}

	// Option -----------------------------

	/**
	 * @return ActiveRecord - with alias name set to 'opt'  
	 */
	public static function findWithAlias() {

		return self::find()->from( CoreTables::TABLE_OPTION . ' opt' );
	}
	
	/**
	 * @return Option - by id
	 */
	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	/**
	 * @return Option - by category
	 */
	public static function findByCategory( $category ) {

		return self::find()->where( 'categoryId=:id', [ ':id' => $category->id ] )->one();
	}

	/**
	 * @return Option - by category id
	 */
	public static function findByCategoryId( $categoryId ) {

		return self::find()->where( 'categoryId=:id', [ ':id' => $categoryId ] )->one();
	}

	/**
	 * @return Option - by category name
	 */
	public static function findByCategoryName( $categoryName ) {

		return self::find()->joinWith( 'categoryWithAlias' )->where( 'cat.name=:cname', [ ':cname' => $categoryName ] )->all();
	}

	/**
	 * @return Option - by name
	 */
	public static function findByName( $name ) {

		return self::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}

	/**
	 * @return Option - by name and category
	 */
	public static function findByNameCategory( $name, $category ) {

		return self::find()->where( 'name=:name AND categoryId=:id', [ ':name' => $name, ':id' => $category->id ] )->one();
	}

	/**
	 * @return Option - by name and category id
	 */
	public static function findByNameCategoryId( $name, $categoryId ) {

		return self::find()->where( 'name=:name AND categoryId=:id', [ ':name' => $name, ':id' => $categoryId ] )->one();
	}

	/**
	 * @return Option - check whether option exist by name and category id
	 */
	public static function isExistByNameCategoryId( $name, $categoryId ) {

		$option = self::findByCategoryIdName( $name, $categoryId );

		return isset( $option );
	}

	/**
	 * @return Option - by name and category name
	 */
	public static function findByNameCategoryName( $name, $categoryName ) {

		return self::findWithAlias()->joinWith( 'categoryWithAlias' )->where( 'opt.name=:name AND cat.name=:cname' )
							->addParams( [ ':name' => $name, ':cname' => $categoryName ] )
							->one();
	}

	/**
	 * @return Option - by value and category id
	 */
	public static function findByValueCategoryName( $value, $categoryName ) {

		return self::findWithAlias()->joinWith( 'categoryWithAlias' )->where( 'opt.name=:name AND cat.name=:cname' )
							->addParams( [ ':value' => $value, ':cname' => $categoryName ] )
							->one();
	}
}

?>