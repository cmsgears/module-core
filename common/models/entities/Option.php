<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Option Entity
 *
 * @property integer $id
 * @property integer $categoryId
 * @property string $key
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

	// yii\base\Model --------------------

	public function rules() {

        return [
            [ [ 'key', 'value' ], 'required' ],
            [ [ 'id', 'categoryId' ], 'safe' ],
            [ 'key', 'alphanumhyphenspace' ],
            [ 'key', 'length', 'min'=>1, 'max'=>100 ],
            [ 'key', 'validateKeyCreate', 'on' => [ 'create' ] ],
            [ 'key', 'validateKeyUpdate', 'on' => [ 'update' ] ],
        ];
    }

	public function attributeLabels() {

		return [
			'categoryId' => 'Category',
			'key' => 'Key',
			'value' => 'Value'
		];
	}

	// Config ----------------------------
	
	/**
	 * Validates to ensure that only one key exist for a Category.
	 */
    public function validateKeyCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByCategoryIdKey( $this->categoryId, $this->key ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessageSource->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that only one key exist for a Category.
	 */
    public function validateKeyUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingOption = self::findByCategoryIdKey( $this->categoryId, $this->key );

			if( isset( $existingOption ) && $existingOption->id != $this->id && 
				strcmp( $existingOption->key, $this->key ) == 0 && $existingOption->categoryId == $this->categoryId ) {

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

		return self::find()->joinWith( 'category' )->where( '`cmg_category`.`name`=:name', [ ':name' => $categoryName ] )->all();
	}

	public static function findByKey( $key ) {

		return self::find()->where( 'key=:key', [ ':key' => $key ] )->one();
	}

	public static function findByCategoryKey( $category, $key ) {

		return self::find()->where( [ 'categoryId=:id', 'key=:key' ] )
							->addParams( [ ':id' => $category->id, ':key' => $key ] )
							->one();
	}

	public static function findByCategoryIdKey( $categoryId, $key ) {

		return self::find()->where( [ 'categoryId=:id', 'key=:key' ] )
							->addParams( [ ':id' => $categoryId, ':key' => $key ] )
							->one();
	}

	public static function isExistByCategoryIdKey( $categoryId, $key ) {

		$option = self::findByCategoryIdKey( $category, $key );
		
		return isset( $option );
	}

	public static function findByCategoryNameKey( $categoryName, $key ) {

		return self::find()->joinWith( 'category' )->where( [ '`cmg_category`.`name`=:name', 'key=:key' ] )
							->addParams( [ ':name' => $categoryName, ':key' => $key ] )
							->one();
	}
}

?>