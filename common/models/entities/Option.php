<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Option Entity
 *
 * @property integer $id
 * @property integer $categoryId
 * @property string $name
 * @property string $value
 * @property string $icon
 * @property string $options
 * @property string $data
 */
class Option extends CmgEntity {

	// Instance Methods --------------------------------------------

	/**
	 * @return Category - The parent category.
	 */
	public function getCategory() {

		return $this->hasOne( Category::className(), [ 'id' => 'categoryId' ] );
	}

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

		// model rules
        $rules = [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'categoryId', 'value', 'options', 'data' ], 'safe' ],
            [ 'categoryId', 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'name', 'icon' ], 'string', 'min' => 1, 'max' => 100 ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
        ];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'value', 'icon' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'categoryId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CATEGORY ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'value' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VALUE ),
			'icon' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'options' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
			'data' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// Config ----------------------------

	/**
	 * Validates to ensure that only one option exist for a category with the same name.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameCategoryId( $this->name, $this->categoryId ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
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

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static methods --------------------------------------------------

	// yii\db\ActiveRecord ----------------

    /**
     * @inheritdoc
     */
	public static function tableName() {
		
		return CoreTables::TABLE_OPTION;
	}

	// Option -----------------------------
	
	// Read ----

	/**
	 * @return Option - by category
	 */
	public static function findByCategory( $category ) {

		return self::find()->where( 'categoryId=:id', [ ':id' => $category->id ] )->all();
	}

	/**
	 * @return Option - by category id
	 */
	public static function findByCategoryId( $categoryId ) {

		return self::find()->where( 'categoryId=:id', [ ':id' => $categoryId ] )->all();
	}

	/**
	 * @return Option - by category name
	 */
	public static function findByCategoryName( $categoryName ) {
		
		$categoryTable = CoreTables::TABLE_CATEGORY;

		return self::find()->joinWith( 'category' )->where( "$categoryTable.name=:cname", [ ':cname' => $categoryName ] )->all();
	}

	/**
	 * @return Option - by name and category
	 */
	public static function findByNameCategory( $name, $category ) {

		$optionTable = CoreTables::TABLE_OPTION;

		return self::find()->where( "$optionTable.name=:name AND categoryId=:id", [ ':name' => $name, ':id' => $category->id ] )->one();
	}

	/**
	 * @return Option - by name and category id
	 */
	public static function findByNameCategoryId( $name, $categoryId ) {

		$optionTable = CoreTables::TABLE_OPTION;

		return self::find()->where( "$optionTable.name=:name AND categoryId=:id", [ ':name' => $name, ':id' => $categoryId ] )->one();
	}

	/**
	 * @return boolean - check whether option exist by name and category id
	 */
	public static function isExistByNameCategoryId( $name, $categoryId ) {

		$option = self::findByNameCategoryId( $name, $categoryId );

		return isset( $option );
	}

	/**
	 * @return Option - by name and category name
	 */
	public static function findByNameCategoryName( $name, $categoryName ) {
		
		$categoryTable 	= CoreTables::TABLE_CATEGORY;
		$optionTable 	= CoreTables::TABLE_OPTION;

		return self::findWithAlias()->joinWith( 'category' )->where( "$optionTable.name=:name AND $categoryTable.name=:cname" )
							->addParams( [ ':name' => $name, ':cname' => $categoryName ] )
							->one();
	}

	/**
	 * @return Option - by value and category id
	 */
	public static function findByValueCategoryName( $value, $categoryName ) {

		$categoryTable 	= CoreTables::TABLE_CATEGORY;
		$optionTable 	= CoreTables::TABLE_OPTION;

		return self::findWithAlias()->joinWith( 'category' )->where( "$optionTable.value=:value AND $categoryTable.name=:cname" )
							->addParams( [ ':value' => $value, ':cname' => $categoryName ] )
							->one();
	}
	
	// Delete ----

	/**
	 * Delete Option - by category id
	 */
	public static function deleteByCategoryId( $categoryId ) {

		return self::deleteAll( 'categoryId=:id', [ ':id' => $categoryId ] );
	}
}

?>