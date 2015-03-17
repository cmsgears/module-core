<?php
namespace cmsgears\core\common\models\entities;

class Option extends CmgEntity {

	// Instance Methods --------------------------------------------

	public function getCategory() {

		return $this->hasOne( Category::className(), [ 'id' => 'categoryId' ] );
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'key', 'value' ], 'required' ],
            [ 'key', 'alphanumhyphenspace' ],
            [ 'key', 'validateKeyCreate', 'on' => [ 'create' ] ],
            [ 'key', 'validateKeyUpdate', 'on' => [ 'update' ] ],
			[ [ 'categoryId' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'key' => 'Key',
			'value' => 'Value'
		];
	}

	// Config

    public function validateKeyCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByCategoryIdKey( $this->categoryId, $this->key ) ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    public function validateKeyUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingOption = self::findByCategoryIdKey( $this->categoryId, $this->key );

			if( isset( $existingOption ) && $existingOption->id != $this->id && 
				strcmp( $existingOption->key, $this->key ) == 0 && $existingOption->categoryId == $this->categoryId ) {

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

		return self::find()->where( 'id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByCategory( $category ) {

		return self::find()->where( [ 'categoryId' => $category->id ] )->one();
	}

	public static function findByCategoryId( $categoryId ) {

		return self::find()->where( 'categoryId=:id', [ ':id' => $categoryId ] )->one();
	}

	public static function findByKey( $key ) {

		return self::find()->where( 'key=:key', [ ':key' => $key ] )->one();
	}

	public static function findByCategoryKey( $category, $key ) {

		return self::find()->where( [ 'categoryId' => $category->id ] )->andWhere( 'key=:key', [ ':key' => $key ] )->one();
	}

	public static function findByCategoryIdKey( $categoryId, $key ) {

		return self::find()->where( 'categoryId=:id', [ ':id' => $categoryId ] )->andWhere( 'key=:key', [ ':key' => $key ] )->one();
	}

	public static function isExistByCategoryIdKey( $categoryId, $key ) {

		$option = self::find()->where( 'categoryId=:id', [ ':id' => $categoryId ] )->andWhere( 'key=:key', [ ':key' => $key ] )->one();
		
		return isset( $option );
	}

	public static function findByCategoryNameKey( $categoryName, $key ) {

		return self::find()->joinWith( 'category' )->where( '`cmg_category`.`name`=:name', [ ':name' => $categoryName ] )->andWhere( '`key`=:key', [ ':key' => $key ] )->one();
	}
}

?>