<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Site;

use cmsgears\core\common\models\traits\NameTypeTrait;
use cmsgears\core\common\models\traits\SlugTypeTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;

/**
 * Category Entity
 *
 * @property long $id
 * @property long $siteId
 * @property long $parentId
 * @property long $rootId
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $description
 * @property boolean $featured
 * @property short lValue
 * @property short rValue
 * @property string $htmlOptions
 * @property string $content
 * @property string $data
 */
class Category extends \cmsgears\core\common\models\hierarchy\TypedHierarchicalModel {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	public static $multiSite = true;

	// Variables -----------------------------

	// Public -----------------

	public $parentType	= CoreGlobal::TYPE_CATEGORY;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

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
            'sluggableBehavior' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'ensureUnique' => true,
                'uniqueValidator' => [ 'targetAttribute' => 'type' ]
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
            [ [ 'siteId', 'name' ], 'required' ],
            [ [ 'id', 'htmlOptions', 'content', 'data' ], 'safe' ],
            [ [ 'name', 'icon', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
            [ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
            [ 'description', 'string', 'min' => 0, 'max' => Yii::$app->core->xLargeText ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'parentId', 'rootId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ 'featured', 'boolean' ]
        ];

        // trim if required
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
            'featured' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FEATURED ),
            'htmlOptions' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
            'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_DATA )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

    // Category ------------------------------

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

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_CATEGORY;
    }

	// CMG parent classes --------------------

	// Category ------------------------------

	// Read - Query -----------

	public static function queryWithAll( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'site', 'options' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithSite( $config = [] ) {

		$config[ 'relations' ]	= [ 'site' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithOptions( $config = [] ) {

		$config[ 'relations' ]	= [ 'options' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

    public static function findByParentId( $id ) {

        return self::find()->where( 'parentId=:id', [ ':id' => $id ] )->all();
    }

    /**
     * @return Category - by type and featured
     */
    public static function getFeaturedByType( $type ) {

        return self::find()->where( 'type=:type AND featured=1', [ ':type' => $type ] )->orderBy( [ 'name' => SORT_ASC ] )->all();
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>