<?php
namespace cmsgears\core\common\models\entities;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\MessageUtil;

class Category extends CmgEntity {

	// Instance Methods --------------------------------------------

	public function getParent() {

		return $this->hasOne( Category::className(), [ 'id' => 'parentId' ] );
	}

	public function getOptions() {

    	return $this->hasMany( Option::className(), [ 'categoryId' => 'id' ] );
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ 'name', 'alphanumspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ 'parentId', 'number', 'integerOny' => true, MessageUtil::getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'description', 'type' ], 'safe' ]
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

	// Category

    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeName( $this->type, $this->name ) ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

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

	public static function tableName() {

		return CoreTables::TABLE_CATEGORY;
	}

	// Category

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

		return self::find()->where( 'name=:name', [ ':name' => $name ] )->andWhere( 'type=:type', [ ':type' => $type ] )->one();
	}
	
	public static function isExistByTypeName( $type, $name ) {
		
		$category = self::findByTypeName( $type, $name );

		return isset( $category );
	}
}

?>