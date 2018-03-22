<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\entities;

// Yii Imports
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\base\IAuthor;
use cmsgears\core\common\models\interfaces\base\IMultiSite;
use cmsgears\core\common\models\interfaces\base\INameType;
use cmsgears\core\common\models\interfaces\base\IOwner;
use cmsgears\core\common\models\interfaces\base\ISlugType;
use cmsgears\core\common\models\interfaces\resources\IComment;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\IHierarchy;
use cmsgears\core\common\models\interfaces\resources\ITemplate;
use cmsgears\core\common\models\interfaces\resources\IVisual;
use cmsgears\core\common\models\interfaces\mappers\ICategory;
use cmsgears\core\common\models\interfaces\mappers\IFile;
use cmsgears\core\common\models\interfaces\mappers\IGallery;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\Entity;
use cmsgears\core\common\models\resources\ObjectMeta;
use cmsgears\core\common\models\mappers\ModelObject;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\NameTypeTrait;
use cmsgears\core\common\models\traits\base\SlugTypeTrait;
use cmsgears\core\common\models\traits\resources\CommentTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\HierarchyTrait;
use cmsgears\core\common\models\traits\resources\SocialLinkTrait;
use cmsgears\core\common\models\traits\resources\TemplateTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;
use cmsgears\core\common\models\traits\mappers\CategoryTrait;
use cmsgears\core\common\models\traits\mappers\FileTrait;
use cmsgears\core\common\models\traits\mappers\GalleryTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * ObjectData Entity
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $themeId
 * @property integer $templateId
 * @property integer $avatarId
 * @property integer $bannerId
 * @property integer $videoId
 * @property integer $galleryId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $texture
 * @property string $title
 * @property string $description
 * @property string $classPath
 * @property string $url
 * @property boolean $active
 * @property integer $order
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $htmlOptions
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class ObjectData extends Entity implements IAuthor, ICategory, IComment, IContent, IData, IFile,
	IGallery, IGridCache, IHierarchy, IMultiSite, INameType, IOwner, ISlugType, ITemplate, IVisual {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType	= CoreGlobal::TYPE_OBJECT;

	protected $testOwner	= false;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use CategoryTrait;
	use CommentTrait;
	use ContentTrait;
	use DataTrait;
	use FileTrait;
	use GalleryTrait;
	use GridCacheTrait;
	use HierarchyTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use SlugTypeTrait;
	use SocialLinkTrait;
	use TemplateTrait;
	use VisualTrait;

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
				'slugAttribute' => 'slug', // Unique for combination of Site Id and Theme Id
				'ensureUnique' => true,
				'uniqueValidator' => [ 'targetAttribute' => [ 'siteId', 'themeId' ] ]
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
			[ [ 'name', 'type' ], 'required' ],
			[ [ 'id', 'htmlOptions', 'content', 'data', 'gridCache' ], 'safe' ],
			// Unique - Allowed multiple names for same type. Slug will differentiate the models.
			//[ [ 'siteId', 'themeId', 'type', 'name' ], 'unique', 'targetAttribute' => [ 'siteId', 'themeId', 'type', 'name' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Text Limit
			[ 'type', 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'icon', 'texture' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 0, 'max' => Yii::$app->core->xLargeText ],
			[ 'slug', 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'title', 'classPath' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 0, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'active', 'gridCacheValid' ], 'boolean' ],
			[ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
			[ [ 'themeId', 'templateId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'siteId', 'avatarId', 'bannerId', 'videoId', 'galleryId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'title', 'description' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'themeId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_THEME ),
			'templateId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TEMPLATE ),
			'avatarId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
			'bannerId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_BANNER ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'classPath' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CLASSPATH ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
			'htmlOptions' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// yii\db\BaseActiveRecord

	/**
	 * @inheritdoc
	 */
	public function beforeSave( $insert ) {

		if( parent::beforeSave( $insert ) ) {

			if( $this->themeId <= 0 ) {

				$this->themeId = null;
			}

			if( $this->templateId <= 0 ) {

				$this->templateId = null;
			}

			if( !isset( $this->order ) || strlen( $this->order ) <= 0 ) {

				$this->order = 0;
			}

			return true;
		}

		return false;
	}

	// CMG interfaces ------------------------

	// IOwner -----------------

	/**
	 * Check whether given user is owner of this object.
	 *
	 * @param User $user
	 * @param boolean $strict
	 * @return boolean
	 */
	public function isOwner( $user = null, $strict = false ) {

		if( $this->testOwner ) {

			if( !isset( $user ) && !$strict ) {

				$user = Yii::$app->user->getIdentity();
			}

			if( isset( $user ) ) {

				return $this->createdBy == $user->id;
			}
		}

		return false;
	}

	// CMG parent classes --------------------

	// Validators ----------------------------

	// ObjectData ----------------------------

	/**
	 * Returns theme to which this object belongs. A object can also exist without assigning theme.
	 *
	 * @return Theme|null
	 */
	public function getTheme() {

		return $this->hasOne( Theme::class, [ 'id' => 'themeId' ] );
	}

	/**
	 * Return meta data of the object.
	 *
	 * @return \cmsgears\core\common\models\resources\ObjectMeta[]
	 */
	public function getMetas() {

		return $this->hasMany( ObjectMeta::class, [ 'modelId' => 'id' ] );
	}

	/**
	 * Returns the objects mapped to it.
	 *
	 * @return ObjectData[]
	 */
    public function getObjects() {

        return $this->hasMany( ModelObject::class, [ 'parentId' => 'id' ] );
    }

	/**
	 * Returns string representation of active flag.
	 *
	 * @return string
	 */
	public function getActiveStr() {

		return Yii::$app->formatter->asBoolean( $this->active );
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_OBJECT_DATA );
	}

	// CMG parent classes --------------------

	// ObjectData ----------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'avatar', 'banner', 'video', 'site', 'theme', 'template', 'creator', 'modifier' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the object with objects assigned to it.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with assigned objects.
	 */
    public static function queryWithModelObjects( $config = [] ) {

        $config[ 'relations' ] = [ 'objects' ];

        return parent::queryWithAll( $config );
    }

	// Read - Find ------------

	/**
	 * Find and returns the objects with given type.
	 *
	 * @param string $type
	 * @param array $config
	 * @return ObjectData[]
	 */
	public static function findByType( $type, $config = [] ) {

		$ignoreSite	= isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : false;

		if( static::isMultiSite() && !$ignoreSite ) {

			$siteId	= isset( $config[ 'siteId' ] ) ? $config[ 'siteId' ] : Yii::$app->core->siteId;

			return static::find()->where( 'type=:type AND siteId=:siteId', [ ':type' => $type, ':siteId' => $siteId ] )->orderBy( [ 'order' => SORT_ASC ] )->all();
		}
		else {

			return static::find()->where( 'type=:type', [ ':type' => $type ] )->orderBy( [ 'order' => SORT_ASC ] )->all();
		}
	}

	/**
	 * Find and return model using given slug and type.
	 *
	 * @param string $slug
	 * @param string $type
	 * @param integer $themeId
	 * @return \cmsgears\core\common\models\base\ActiveRecord
	 */
	public static function findByThemeId( $slug, $type, $themeId ) {

		return self::queryBySlugType( $slug, $type, [ 'ignoreSite' => true ] )->andWhere( 'themeId=:themeId', [ ':themeId' => $themeId ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
