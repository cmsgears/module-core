<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

class Option extends ActiveRecord {

	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {

		return $this->option_id;
	}

	public function getCategoryId() {

		return $this->option_category;
	}

	public function getCategory() {

		return $this->hasOne( Category::className(), [ 'category_id' => 'option_category' ] );
	}

	public function setCategoryId( $categoryId ) {

		$this->option_category = $categoryId;
	}

	public function getKey() {

		return $this->option_key;
	}

	public function setKey( $key ) {

		$this->option_key = $key;
	}

	public function getValue() {

		return $this->option_value;
	}

	public function setValue( $value ) {

		$this->option_value = $value;
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'option_key', 'option_value' ], 'required' ],
            [ 'option_key', 'alphanumhyphenspace' ],
            [ 'option_key', 'validateKeyCreate', 'on' => [ 'create' ] ],
            [ 'option_key', 'validateKeyUpdate', 'on' => [ 'update' ] ],
			[ [ 'option_category' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'option_key' => 'Key',
			'option_value' => 'Value'
		];
	}

	// Config

    public function validateKeyCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByCategoryIdKey( $this->getCategoryId(), $this->getKey() ) ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    public function validateKeyUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingOption = self::findByCategoryIdKey( $this->getCategoryId(), $this->getKey() );

			if( isset( $existingOption ) && $existingOption->getId() != $this->getId() && 
				strcmp( $existingOption->getKey(), $this->getKey() ) == 0 && $existingOption->getCategoryId() == $this->getCategoryId() ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord

	public static function tableName() {
		
		return CoreTables::TABLE_OPTION;
	}

	// Option

	public static function findById( $id ) {

		return self::find()->where( 'option_id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByCategory( $category ) {

		return self::find()->where( [ 'option_category' => $category->getId() ] )->one();
	}

	public static function findByCategoryId( $categoryId ) {

		return self::find()->where( 'option_category=:id', [ ':id' => $categoryId ] )->one();
	}

	public static function findByKey( $key ) {

		return self::find()->where( 'option_key=:key', [ ':key' => $key ] )->one();
	}

	public static function findByCategoryKey( $category, $key ) {

		return self::find()->where( [ 'option_category' => $category->getId() ] )->andWhere( 'option_key=:key', [ ':key' => $key ] )->one();
	}

	public static function findByCategoryIdKey( $categoryId, $key ) {

		return self::find()->where( 'option_category=:id', [ ':id' => $categoryId ] )->andWhere( 'option_key=:key', [ ':key' => $key ] )->one();
	}

	public static function isExistByCategoryIdKey( $categoryId, $key ) {

		$option = self::find()->where( 'option_category=:id', [ ':id' => $categoryId ] )->andWhere( 'option_key=:key', [ ':key' => $key ] )->one();
		
		return isset( $option );
	}

	public static function findByCategoryNameKey( $categoryName, $key ) {

		return self::find()->joinWith( 'category' )->where( 'category_name=:name', [ ':name' => $categoryName ] )->andWhere( 'option_key=:key', [ ':key' => $key ] )->one();
	}
}

?>