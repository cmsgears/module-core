<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use yii\db\ActiveRecord;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\utilities\MessageUtil;

class Category extends ActiveRecord {

	// Instance Methods --------------------------------------------

	// db columns

	public function getId() {

		return $this->category_id;
	}

	public function getParentId() {

		return $this->category_parent;
	}

	public function getParent() {

		return $this->hasOne( Category::className(), [ 'category_id' => 'category_parent' ] );
	}

	public function setParentId( $parentId ) {

		$this->category_parent = $parentId;
	}

	public function getName() {

		return $this->category_name;
	}

	public function setName( $name ) {

		$this->category_name = $name;
	}

	public function getDesc() {

		return $this->category_desc;
	}

	public function setDesc( $desc ) {

		$this->category_desc = $desc;
	}

	public function getType() {

		return $this->category_type;
	}

	public function getTypeStr() {

		return self::$typeMap[ $this->category_type ];
	}

	public function setType( $type ) {

		$this->category_type = $type;
	}

	public function getOptions() {

    	return $this->hasMany( Option::className(), [ 'option_category' => 'category_id' ] );
	}

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'category_name' ], 'required' ],
            [ 'category_name', 'alphanumspace' ],
            [ 'category_name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'category_name', 'validateNameUpdate', 'on' => [ 'update' ] ],
			[ [ 'category_parent', 'category_desc', 'category_type' ], 'safe' ]
        ];
    }

	public function attributeLabels() {

		return [
			'category_name' => 'Name',
			'category_parent' => 'Parent',
			'category_desc' => 'Description',
			'category_type' => 'type'
		];
	}

	// Category

    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeName( $this->category_type, $this->category_name ) ) {

				$this->addError( $attribute, MessageUtil::getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingCategory = self::findByTypeName( $this->category_type, $this->category_name );

			if( isset( $existingCategory ) && $existingCategory->getId() != $this->getId() && 
				strcmp( $existingCategory->getName(), $this->getName() ) == 0 && $existingCategory->getType() == $this->getType() ) {

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

		return self::find()->where( 'category_id=:id', [ ':id' => $id ] )->one();
	}

	public static function findByName( $name ) {

		return self::find()->where( 'category_name=:name', [ ':name' => $name ] )->one();
	}

	public static function findByType( $type ) {

		return self::find()->where( 'category_type=:type', [ ':type' => $type ] )->all();
	}

	public static function findByTypeName( $type, $name ) {

		return self::find()->where( 'category_name=:name', [ ':name' => $name ] )->andWhere( 'category_type=:type', [ ':type' => $type ] )->one();
	}
	
	public static function isExistByTypeName( $type, $name ) {

		$category = self::findByTypeName( $type, $name );
		
		return isset( $category );
	}
}

?>