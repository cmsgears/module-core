<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\resources\SiteMeta;

use cmsgears\core\common\models\traits\NameTrait;
use cmsgears\core\common\models\traits\SlugTrait;
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
class Site extends \cmsgears\core\common\models\base\Entity {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $mParentType	= CoreGlobal::TYPE_SITE;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use NameTrait;
	use SlugTrait;
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
            [ [ 'name' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
            [ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
            [ 'name', 'unique' ],
            [ 'order', 'number', 'integerOnly' => true, 'min' => 0 ],
            [ 'active', 'boolean' ],
            [ [ 'avatarId', 'bannerId', 'themeId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];

        // trim if required
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'name' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**attributes
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'avatarId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
            'bannerId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_BANNER ),
            'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'slug' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
            'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
            'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE )
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
     * @return array - SiteMeta
     */
    public function getMetas() {

        return $this->hasMany( SiteMeta::className(), [ 'modelId' => 'id' ] );
    }

    /**
     * @return array - list of site Users
     */
    public function getMembers() {

        return $this->hasMany( User::className(), [ 'id' => 'userId' ] )
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

	public static function queryWithHasOne( $config = [] ) {

		$modelTable				= CoreTables::TABLE_SITE;
		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'avatar', 'banner', 'theme' ];
		$config[ 'relations' ]	= $relations;
		$config[ 'groups' ]		= isset( $config[ 'groups' ] ) ? $config[ 'groups' ] : [ "$modelTable.id" ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithTheme( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'banner', 'theme' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithMetas( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'banner', 'metas' ];

		return parent::queryWithAll( $config );
	}

	public static function queryWithMembers( $config = [] ) {

		$config[ 'relations' ]	= [ 'avatar', 'banner', 'members' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}
