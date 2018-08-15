<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\resources;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IFeatured;
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\base\INameType;
use cmsgears\core\common\models\interfaces\base\ISlugType;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\hierarchy\NestedSetModel;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\FeaturedTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\NameTypeTrait;
use cmsgears\core\common\models\traits\base\SlugTypeTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * The category model can be used to categorize other models via model category.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $parentId
 * @property integer $rootId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $title
 * @property string $description
 * @property integer $lValue
 * @property integer $rValue
 * @property integer $order
 * @property boolean $pinned
 * @property boolean $featured
 * @property string $htmlOptions
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 *
 * @since 1.0.0
 */
class Category extends NestedSetModel implements IAuthor, IFeatured, IMultiSite, INameType, ISlugType {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_CATEGORY;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use DataTrait;
	use FeaturedTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use SlugTypeTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	/**
	 * @inheritdoc
	 */
	public function behaviors() {

		return [
			AuthorBehavior::class,
			'sluggableBehavior' => [
				'class' => SluggableBehavior::class,
				'attribute' => 'name',
				'slugAttribute' => 'slug', // Unique for Site Id and Type
				'immutable' => true,
				'ensureUnique' => true,
				'uniqueValidator' => [ 'targetAttribute' => [ 'siteId', 'type', 'slug' ] ]
			],
			'timestampBehavior' => [
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'createdAt',
				'updatedAtAttribute' => 'modifiedAt',
				'value' => new Expression('NOW()')
			]
		];
	}

	// yii\base\Model ---------

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'siteId', 'name' ], 'required' ],
			[ [ 'id', 'htmlOptions', 'content', 'data' ], 'safe' ],
			// Unique
			// Notes: disabled it in order to allow sub categories having same name as parent, but with different slug. It can be enable based on project needs by extending the model and service.
			//[ [ 'name', 'type' ], 'unique', 'targetAttribute' => [ 'name', 'type' ] ],
			[ 'slug', 'unique', 'targetAttribute' => [ 'siteId', 'slug', 'type' ] ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'icon', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 0, 'max' => Yii::$app->core->xLargeText ],
			[ 'slug', 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			[ 'title', 'string', 'min' => 0, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 0, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'pinned', 'featured' ], 'boolean' ],
			[ [ 'parentId', 'rootId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'siteId', 'createdBy', 'modifiedBy', 'lValue', 'rValue' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ 'parentId', 'validateParentChain' ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

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
			'siteId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SITE ),
			'parentId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'pinned' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PINNED ),
			'featured' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
			'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
			'htmlOptions' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Category ------------------------------

	/**
	 * Return the immediate parent of the category.
	 *
	 * @return Category
	 */
	public function getParent() {

		$parentTable = CoreTables::getTableName( CoreTables::TABLE_CATEGORY );

		return $this->hasOne( Category::class, [ 'id' => 'parentId' ] )->from( "$parentTable as parent" );
	}

	/**
	 * Return the root parent of the category.
	 *
	 * @return Category
	 */
	public function getRoot() {

		$parentTable = CoreTables::getTableName( CoreTables::TABLE_CATEGORY );

		return $this->hasOne( Category::class, [ 'id' => 'rootId' ] )->from( "$parentTable as root" );
	}

	/**
	 * Returns list of immediate child categories.
	 *
	 * @return Category[]
	 */
	public function getChildren() {

		return $this->hasMany( Category::class, [ 'parentId' => 'id' ] );
	}

	/**
	 * Returns list of options mapped to this category.
	 *
	 * @return Option[]
	 */
	public function getOptions() {

		return $this->hasMany( Option::class, [ 'categoryId' => 'id' ] );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_CATEGORY );
	}

	// CMG parent classes --------------------

	// Category ------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'site', 'parent', 'root' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the category with parent and root.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with options.
	 */
	public static function queryWithHierarchy( $config = [] ) {

		$config[ 'relations' ]	= [ 'parent', 'root' ];

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the category with options.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with options.
	 */
	public static function queryWithOptions( $config = [] ) {

		$config[ 'relations' ]	= [ 'options' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Find and return the categories having given parent id.
	 *
	 * @param string $parentId
	 * @param array $config
	 * @return Category[]
	 */
	public static function findByParentId( $parentId, $config = [] ) {

		$limit 		= $config['limit'] ?? null;
		return self::find()->where( 'parentId=:id', [ ':id' => $parentId ] )->limit($limit)->all();
	}

	/**
	 * Find and return the featured categories for given type.
	 *
	 * @param string $type
	 * @param array $config
	 * @return Category
	 */
	public static function findFeaturedByType( $type, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;
		$order		= isset( $config[ 'order' ] ) ? $config[ 'order' ] : [ 'name' => SORT_ASC  ];
		$limit 		= $config['limit'] ?? null;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'type=:type AND siteId=:siteId AND featured=1', [ ':type' => $type, ':siteId' => $siteId ] )->orderBy( $order )->limit($limit)->all();
		}
		else {

			return static::find()->where( 'type=:type AND featured=1', [ ':type' => $type ] )->orderBy( $order )->limit($limit)->all();
		}
	}

	/**
	 * Find and return the featured categories for given type.
	 *
	 * @param string $type
	 * @param array $config
	 * @return Category
	 */
	public static function findL0ByType( $type, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;
		$order		= isset( $config[ 'order' ] ) ? $config[ 'order' ] : [ 'name' => SORT_ASC  ];
		$limit 		= $config['limit'] ?? null;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'type=:type AND siteId=:siteId AND parentId IS null', [ ':type' => $type, ':siteId' => $siteId ] )->orderBy( $order )->limit($limit)->all();
		}
		else {

			return static::find()->where( 'type=:type AND parentId IS null', [ ':type' => $type ] )->orderBy( $order )->limit($limit)->all();
		}
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
