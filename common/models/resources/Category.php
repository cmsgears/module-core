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
 * @property string $icon
 * @property string $type
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

	protected static $multiSite = true;

	// Variables -----------------------------

	// Public -----------------

	public $parentType	= CoreGlobal::TYPE_CATEGORY;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

    use DataTrait;

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
                'attribute' => 'name'
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
            [ [ 'name', 'icon', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->largeText ],
            [ 'description', 'string', 'min' => 0, 'max' => Yii::$app->cmgCore->xLargeText ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'parentId', 'rootId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
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

	public static function queryWithSite() {

		return self::find()->joinWith( 'site' );
	}

	// Read - Find ------------

    public static function findByParentId( $id ) {

        return self::find()->where( 'parentId=:id', [ ':id' => $id ] )->all();
    }

    /**
     * @return Category - by name
     */
    public static function findByName( $name ) {

        return self::find()->where( 'name=:name', [ ':name' => $name ] )->all();
    }

    /**
     * @return Category - by type
     */
    public static function findByType( $type ) {

        return self::find()->where( 'type=:type', [ ':type' => $type ] )->all();
    }

    public static function findBySlugType( $slug, $type ) {

        return self::find()->where( 'slug=:slug AND type=:type', [ ':slug' => $slug, ':type' => $type ] )->one();
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