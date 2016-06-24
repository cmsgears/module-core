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

/**
 * Tag Entity
 *
 * @property long $id
 * @property long $siteId
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $description
 */
class Tag extends \cmsgears\core\common\models\base\TypedResource {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	protected static $multisite	= true;

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

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
                'ensureUnique' => true
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
            [ 'id', 'safe' ],
            [ [ 'name', 'icon', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->largeText ],
            [ 'description', 'string', 'min' => 0, 'max' => Yii::$app->cmgCore->xLargeText ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];

        // trim if required
        if( Yii::$app->cmgCore->trimFieldValue ) {

            $trim[] = [ [ 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
            'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'icon' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ICON ),
            'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
            'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

    // Tag -----------------------------------

    public function getSite() {

        return $this->hasOne( Site::className(), [ 'id' => 'siteId' ] );
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_TAG;
    }

	// CMG parent classes --------------------

	// Tag -----------------------------------

	// Read - Query -----------

	// Read - Find ------------

    /**
     * @return Tag - by type
     */
    public static function findBySlug( $slug ) {

        return self::find()->where( 'slug=:slug', [ ':slug' => $slug ] )->one();
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>