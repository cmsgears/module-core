<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\DataTrait;

/**
 * Category Entity
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $parentId
 * @property integer $rootId
 * @property string $name
 * @property string $slug
 * @property string $icon
 * @property string $type 
 * @property string $description
 * @property string $featured
 * @property integer lValue
 * @property integer rValue
 * @property string $htmlOptions
 * @property string $data
 */
class Category extends HierarchicalModel {

	use DataTrait;
	
	// Instance Methods --------------------------------------------

	public function getSite() {

		return $this->hasOne( Site::className(), [ 'id' => 'siteId' ] );
	}

	/**
	 * @return Category - parent category
	 */
	public function getParent() {

		return $this->hasOne( Category::className(), [ 'id' => 'parentId' ] );
	}

	/**
	 * @return array - list of immediate child categories
	 */
	public function getChildren() {

    	return $this->hasMany( Category::className(), [ 'parentId' => 'id' ] );
	}

	/**
	 * @return array - list of Option having all the options belonging to this category
	 */
	public function getOptions() {

    	return $this->hasMany( Option::className(), [ 'categoryId' => 'id' ] );
	}

	/**
	 * @return string representation of flag
	 */
	public function getFeaturedStr() {

		return Yii::$app->formatter->asBoolean( $this->featured ); 
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
		
		// model rules
        $rules = [
            [ [ 'siteId', 'name' ], 'required' ],
            [ [ 'id', 'featured', 'description', 'htmlOptions', 'data' ], 'safe' ],
            [ [ 'name', 'type', 'icon' ], 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_MEDIUM ],
            [ 'slug', 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_LARGE ],
            [ 'name', 'alphanumspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ 'featured', 'boolean' ]
        ];

		// trim if required
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'description', 'icon' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'siteId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'slug' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'featured' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
			'htmlOptions' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
			'data' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// Category --------------------------
	
	/**
	 * Validates to ensure that name is used only for one category for a particular type
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameType( $this->name, $this->type ) ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates to ensure that name is used only for one category for a particular type
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingCategory = self::findByNameType( $this->name, $this->type );

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
	 * @return Category - by type
	 */
	public static function findByType( $type ) {

		return self::find()->where( 'type=:type', [ ':type' => $type ] )->all();
	}

	/**
	 * @return Category - by name
	 */
	public static function findByName( $name ) {

		return self::find()->where( 'name=:name', [ ':name' => $name ] )->all();
	}

	public static function findBySlug( $slug ) {

		return self::find()->where( 'slug=:slug', [ ':slug' => $slug ] )->one();
	}

	public static function findByParentId( $id ) {

		return self::find()->where( 'parentId=:id', [ ':id' => $id ] )->all();
	}

	/**
	 * @return Category - by type and name
	 */
	public static function findByNameType( $name, $type ) {

		$siteId	= Yii::$app->cmgCore->siteId;

		return self::find()->where( 'name=:name AND type=:type AND siteId=:siteId', [ ':name' => $name, ':type' => $type, ':siteId' => $siteId ] )->one();
	}
	
	/**
	 * @return Category - by type and featured
	 */
	public static function getFeaturedByType( $type ) {
		
		return self::find()->where( 'type=:type AND featured=1', [ ':type' => $type ] )->all();
	}

	/**
	 * @return Category - checks whether category exist by type and name
	 */
	public static function isExistByNameType( $name, $type ) {

		$category = self::findByNameType( $name, $type );

		return isset( $category );
	}
}

?>