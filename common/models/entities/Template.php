<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\NameTypeTrait;
use cmsgears\core\common\models\traits\SlugTypeTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Template Entity
 *
 * @property long $id
 * @property long $createdBy
 * @property long $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $description
 * @property boolean $active
 * @property string $renderer
 * @property boolean $fileRender
 * @property string $layout
 * @property boolean $layoutGroup
 * @property string $viewPath
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $content
 * @property string $data
 */
class Template extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $modelType	= CoreGlobal::TYPE_TEMPLATE;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use CreateModifyTrait;
	use DataTrait;
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
			AuthorBehavior::className(),
			'sluggableBehavior' => [
				'class' => SluggableBehavior::className(),
				'attribute' => 'name',
				'slugAttribute' => 'slug',
				'ensureUnique' => true,
				'uniqueValidator' => [ 'targetAttribute' => 'type' ]
			],
			'timestampBehavior' => [
				'class' => TimestampBehavior::className(),
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

		// model rules
		$rules = [
			// Required, Safe
			[ [ 'name', 'type' ], 'required' ],
			[ [ 'id', 'content', 'data' ], 'safe' ],
			// Unique
			[ [ 'name', 'type' ], 'unique', 'targetAttribute' => [ 'name', 'type' ] ],
			// Text Limit
			[ [ 'type', 'renderer' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ [ 'name', 'icon' ], 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ [ 'slug' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ [ 'description', 'layout', 'viewPath' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xxLargeText ],
			// Other
			[ [ 'active', 'fileRender', 'layoutGroup' ], 'boolean' ],
			[ [ 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
			[ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
		];

		// trim if required
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'renderer', 'description', 'layout', 'viewPath' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		return [
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
			'description' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
			'renderer' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_RENDERER ),
			'layout' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LAYOUT ),
			'viewPath' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VIEW_PATH ),
			'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Template ------------------------------

	public function isActive() {

		return $this->active;
	}

    public function getActiveStr() {

        return Yii::$app->formatter->asBoolean( $this->active );
    }

    public function getFileRenderStr() {

        return Yii::$app->formatter->asBoolean( $this->fileRender );
    }

    public function getGroupLayoutStr() {

        return Yii::$app->formatter->asBoolean( $this->layoutGroup );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	/**
	 * @inheritdoc
	 */
	public static function tableName() {

		return CoreTables::TABLE_TEMPLATE;
	}

	// CMG parent classes --------------------

	// Template ------------------------------

	// Read - Query -----------

	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'creator', 'modifier' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	public static function findActiveByType( $type ) {

		return self::queryByType( $type )->andWhere( 'active=:active', [ ':active' => true ] )->all();
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
