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
use cmsgears\core\common\models\interfaces\base\ISlugType;
use cmsgears\core\common\models\interfaces\resources\IContent;
use cmsgears\core\common\models\interfaces\resources\IData;
use cmsgears\core\common\models\interfaces\resources\IGridCache;
use cmsgears\core\common\models\interfaces\resources\IVisual;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\Entity;
use cmsgears\core\common\models\resources\File;

use cmsgears\core\common\models\traits\base\AuthorTrait;
use cmsgears\core\common\models\traits\base\MultiSiteTrait;
use cmsgears\core\common\models\traits\base\NameTypeTrait;
use cmsgears\core\common\models\traits\base\SlugTypeTrait;
use cmsgears\core\common\models\traits\resources\ContentTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\resources\GridCacheTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Template model can be used to render the models having template support.
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $themeId
 * @property integer $bannerId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $title
 * @property string $description
 * @property boolean $active
 * @property boolean $frontend
 * @property string $classPath
 * @property string $dataPath
 * @property string $dataForm
 * @property string $attributesPath
 * @property string $attributesForm
 * @property string $configPath
 * @property string $configForm
 * @property string $settingsPath
 * @property string $settingsForm
 * @property string $renderer
 * @property boolean $fileRender
 * @property string $layout
 * @property boolean $layoutGroup
 * @property string $viewPath
 * @property string $view
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $htmlOptions
 * @property string $help
 * @property string $message
 * @property string $content
 * @property string $data
 * @property string $gridCache
 * @property boolean $gridCacheValid
 * @property datetime $gridCachedAt
 *
 * @since 1.0.0
 */
class Template extends Entity implements IAuthor, IContent, IData, IGridCache,
	IMultiSite, INameType, ISlugType, IVisual {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_TEMPLATE;

	// Private ----------------

	// Traits ------------------------------------------------------

	use AuthorTrait;
	use ContentTrait;
	use DataTrait;
	use GridCacheTrait;
	use MultiSiteTrait;
	use NameTypeTrait;
	use SlugTypeTrait;
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
			'timestampBehavior' => [
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'createdAt',
				'updatedAtAttribute' => 'modifiedAt',
				'value' => new Expression('NOW()')
			],
			'sluggableBehavior' => [
				'class' => SluggableBehavior::class,
				'attribute' => 'name',
				'slugAttribute' => 'slug', // Unique for combination of Site Id and Theme Id
				'immutable' => true,
				'ensureUnique' => true,
				'uniqueValidator' => [ 'targetAttribute' => [ 'siteId', 'themeId', 'type', 'slug' ] ]
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
			[ [ 'id', 'htmlOptions', 'help', 'message', 'content' ], 'safe' ],
			// Unique
			// Unique name and slug
			[ 'name', 'unique', 'targetAttribute' => [ 'siteId', 'themeId', 'type', 'name' ], 'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_NAME ) ],
			[ 'slug', 'unique', 'targetAttribute' => [ 'siteId', 'themeId', 'type', 'slug' ], 'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SLUG ) ],
			// Text Limit
			[ [ 'type', 'renderer', 'layout' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'icon', 'view' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ [ 'name', 'viewPath' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ [ 'classPath', 'dataPath', 'dataForm', 'attributesPath', 'attributesForm', 'configPath' , 'configForm', 'settingsPath', 'settingsForm' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'title', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			[ 'description', 'string', 'min' => 0, 'max' => Yii::$app->core->xtraLargeText ],
			// Other
			[ [ 'active', 'frontend', 'fileRender', 'layoutGroup', 'gridCacheValid' ], 'boolean' ],
			[ 'themeId', 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
			[ [ 'siteId', 'bannerId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt', 'gridCachedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'title', 'description', 'renderer', 'layout', 'viewPath', 'view' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'bannerId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_PREVIEW ),
			'themeId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_THEME ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'title' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TITLE ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
			'frontend' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FRONTEND ),
			'classPath' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CLASSPATH ),
			'renderer' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_RENDERER ),
			'fileRender' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FILE_RENDER ),
			'layout' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LAYOUT ),
			'layoutGroup' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LAYOUT_GROUP ),
			'viewPath' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VIEW_PATH ),
			'view' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VIEW ),
			'htmlOptions' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
			'help' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_HELP ),
			'message' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MESSAGE ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
			'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA ),
			'gridCache' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_GRID_CACHE )
		];
	}

	// yii\db\BaseActiveRecord

	public function beforeSave( $insert ) {

		if( parent::beforeSave( $insert ) ) {

			if( $this->siteId <= 0 ) {

				$this->siteId = null;
			}

			if( $this->themeId <= 0 ) {

				$this->themeId = null;
			}

			// Default Type - Default
			$this->type = $this->type ?? CoreGlobal::TYPE_DEFAULT;

			return true;
		}

		return false;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Template ------------------------------

	/**
	 * Returns theme to which this template belongs. A template can also exist without assigning theme.
	 *
	 * @return Theme|null
	 */
	public function getTheme() {

		return $this->hasOne( Theme::class, [ 'id' => 'themeId' ] );
	}

	/**
	 * Check whether template is active.
	 *
	 * @return boolean
	 */
	public function isActive() {

		return $this->active;
	}

	/**
	 * Returns string representation of active flag.
	 *
	 * @return string
	 */
    public function getActiveStr() {

        return Yii::$app->formatter->asBoolean( $this->active );
    }

	/**
	 * Returns string representation of file render flag.
	 *
	 * @return string
	 */
    public function getFileRenderStr() {

        return Yii::$app->formatter->asBoolean( $this->fileRender );
    }

	/**
	 * Returns string representation of group layout flag.
	 *
	 * @return string
	 */
    public function getGroupLayoutStr() {

        return Yii::$app->formatter->asBoolean( $this->layoutGroup );
    }

	/**
	 * Returns string representation of frontend flag.
	 *
	 * @return string
	 */
    public function getFrontendStr() {

        return Yii::$app->formatter->asBoolean( $this->frontend );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::getTableName( CoreTables::TABLE_TEMPLATE );
	}

	// CMG parent classes --------------------

	// Template ------------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations = isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'site', 'theme', 'banner', 'creator', 'modifier' ];

		$config[ 'relations' ] = $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Find and return the active templates for the given type.
	 *
	 * @param string $type
	 * @param array $config
	 * @return \cmsgears\core\common\models\entities\Template[]
	 */
	public static function findActiveByType( $type, $config = [] ) {

		return self::queryByType( $type, $config )->andWhere( 'active=:active', [ ':active' => true ] )->all();
	}

	/**
	 * Find and return the global model using given slug and type.
	 *
	 * @param string $slug
	 * @param string $type
	 * @return \cmsgears\core\common\models\entities\Template
	 */
	public static function findGlobalBySlugType( $slug, $type, $config = [] ) {

		return static::find()->where( 'slug=:slug AND type=:type AND siteId IS NULL AND themeId IS NULL', [ ':slug' => $slug, ':type' => $type ] )->one();
	}

	/**
	 * Find and return the model using given slug, type, and active theme's id. It assumes that site id is NULL.
	 *
	 * @param string $slug
	 * @param string $type
	 * @return \cmsgears\core\common\models\entities\Template
	 */
	public static function findByThemeSlugType( $slug, $type, $config = [] ) {

		$theme = Yii::$app->core->site->theme;

		return self::findByThemeId( $slug, $type, $theme->id );
	}

	/**
	 * Find and return the model using given slug, type, and theme id. It assumes that site id is NULL.
	 *
	 * @param string $slug
	 * @param string $type
	 * @param integer $themeId
	 * @return \cmsgears\core\common\models\entities\Template
	 */
	public static function findByThemeId( $slug, $type, $themeId, $config = [] ) {

		$config[ 'ignoreSite' ] = isset( $config[ 'ignoreSite' ] ) ? $config[ 'ignoreSite' ] : true;

		return self::queryBySlugType( $slug, $type, $config )->andWhere( 'themeId=:themeId', [ ':themeId' => $themeId ] )->one();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
