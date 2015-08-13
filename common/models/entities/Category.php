<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Category Entity
 *
 * @property integer $id
 * @property integer $parentId
 * @property string $name
 * @property string $description
 * @property string $slug
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

	// yii\base\Component ----------------
	
    /**
     * @inheritdoc
     */
    public function behaviors() {

        return [

            'sluggableBehavior' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'ensureUnique' => true
            ]
        ];
    }

	// yii\base\Model --------------------

    /**
     * @inheritdoc
     */
	public function rules() {

        return [
            [ [ 'name' ], 'required' ],
            [ [ 'id', 'description' ], 'safe' ],
            [ 'name', 'alphanumspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ 'parentId', 'number', 'integerOnly' => true, 'min' => 1, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'type', 'icon' ], 'string', 'min'=>1, 'max'=>100 ],
        ];
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'slug' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ICON )
		];
	}

	// Category --------------------------
	
	/**
	 * Validates to ensure that name is used only for one category for a particular type
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeName( $this->type, $this->name ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
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

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_CATEGORY;
	}

	// Category --------------------------

	// Read ----

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