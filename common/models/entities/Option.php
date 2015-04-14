<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Option Entity
 *
 * @property integer $id
 * @property integer $categoryId
 * @property string $name
 * @property string $value
 */
class Option extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Category - The parent Category.
	 */
	public function getCategory() {

		return $this->hasOne( Category::className(), [ 'id' => 'categoryId' ] );
	}

	/**
	 * @return Category - The parent Category with alias.
	 */
	public function getCategoryWithAlias() {

		return $this->hasOne( Category::className(), [ 'id' => 'categoryId' ] )->from( CoreTables::TABLE_CATEGORY . ' cat' );
	}

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'name', 'value' ], 'required' ],
            [ [ 'id', 'categoryId' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'string', 'min'=>1, 'max'=>100 ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
        ];
    }

	public function attributeLabels() {

		return [
			'categoryId' => 'Category',
			'name' => 'Name',
			'value' => 'Value'
		];
	}

	// Config ----------------------------
	
	/**
	 * Validates to ensure that only one name exist for a Category.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameCategoryId( $this->name, $this->categoryId ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one name exist for a Category.
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

	public static function tableName() {
		
		return CoreTables::TABLE_OPTION;
	}

	// Option -----------------------------

	public static function findWithAlias() {

		return self::find()->from( CoreTables::TABLE_OPTION . ' opt' );
	}

	public static function findById( $id ) {

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByCategory( $category ) {

		return self::find()->where( 'categoryId=:id', [ ':id' => $category->id ] )->one();
	}

	public static function findByCategoryId( $categoryId ) {

		return self::find()->where( 'categoryId=:id', [ ':id' => $categoryId ] )->one();
	}

	public static function findByCategoryName( $categoryName ) {

		return self::find()->joinWith( 'categoryWithAlias' )->where( 'cat.name=:cname', [ ':cname' => $categoryName ] )->all();
	}

	public static function findByName( $name ) {

		return self::find()->where( 'name=:name', [ ':name' => $name ] )->one();
	}

	public static function findByNameCategory( $name, $category ) {

		return self::find()->where( 'name=:name AND categoryId=:id', [ ':name' => $name, ':id' => $category->id ] )->one();
	}

	public static function findByNameCategoryId( $name, $categoryId ) {

		return self::find()->where( 'name=:name AND categoryId=:id', [ ':name' => $name, ':id' => $categoryId ] )->one();
	}

	public static function isExistByNameCategoryId( $name, $categoryId ) {

		$option = self::findByCategoryIdName( $name, $categoryId );

		return isset( $option );
	}

	public static function findByNameCategoryName( $name, $categoryName ) {

		return self::findWithAlias()->joinWith( 'categoryWithAlias' )->where( 'opt.name=:name AND cat.name=:cname' )
							->addParams( [ ':name' => $name, ':cname' => $categoryName ] )
							->one();
	}
}

?>