<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\SiteAttribute;

use cmsgears\core\common\models\traits\resources\AttributeTrait;
use cmsgears\core\common\models\traits\resources\VisualTrait;

/**
 * Site Entity
 *
 * @property long $id
 * @property long $avatarId
 * @property long $bannerId
 * @property long $themeId
 * @property string $name
 * @property string $slug
 * @property short $order
 * @property boolean $active
 */
class Site extends \cmsgears\core\common\models\base\NamedEntity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $parentType  = CoreGlobal::TYPE_SITE;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

    use AttributeTrait;
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
            [ [ 'name' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'name' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->largeText ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
            [ 'active', 'boolean' ],
            [ [ 'avatarId', 'bannerId', 'themeId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
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
            'avatarId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
            'bannerId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_BANNER ),
            'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'slug' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
            'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
            'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Site ----------------------------------

    public function getTheme() {

        return $this->hasOne( Theme::className(), [ 'id' => 'themeId' ] );
    }

    /**
     * @return array - SiteAttribute
     */
    public function getAttributes() {

        return $this->hasMany( SiteAttribute::className(), [ 'siteId' => 'id' ] );
    }

    /**
     * @return array - list of site Users
     */
    public function getUsers() {

        return $this->hasMany( User::className(), [ 'id' => 'memberId' ] )
                    ->viaTable( CoreTables::TABLE_SITE_MEMBER, [ 'siteId' => 'id' ] );
    }

    /**
     * @return string representation of flag
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

        return CoreTables::TABLE_SITE;
    }

	// CMG parent classes --------------------

	// Site ----------------------------------

	// Read - Query -----------

	// Read - Find ------------

    /**
     * @return Site - by slug
     */
    public static function findBySlug( $slug ) {

        return self::find()->where( 'slug=:slug', [ ':slug' => $slug ] )->one();
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>