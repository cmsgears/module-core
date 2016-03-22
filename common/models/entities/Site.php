<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
use yii\validators\FilterValidator;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\VisualTrait;
use cmsgears\core\common\models\traits\AttributeTrait;
use cmsgears\core\common\models\traits\CommentTrait;

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
class Site extends NamedCmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    public $parentType  = CoreGlobal::TYPE_SITE;

    // Private/Protected --

    // Traits ------------------------------------------------------

    use VisualTrait;
    use AttributeTrait;
    use CommentTrait;

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    public function getTheme() {

        return $this->hasOne( Theme::className(), [ 'id' => 'themeId' ] );
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
            [ [ 'name' ], 'required' ],
            [ [ 'id' ], 'safe' ],
            [ [ 'name' ], 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_MEDIUM ],
            [ 'name', 'alphanumhyphenspace' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ 'slug', 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_LARGE ],
            [ 'order', 'number', 'integerOnly' => true ],
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

    // Site ------------------------------

    // Static Methods ----------------------------------------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_SITE;
    }

    // yii\db\ActiveRecord ---------------

    // Site ------------------------------

    // Create -------------

    // Read ---------------

    /**
     * @return Site - by slug
     */
    public static function findBySlug( $slug ) {

        return self::find()->where( 'slug=:slug', [ ':slug' => $slug ] )->one();
    }

    // Update -------------

    // Delete -------------
}

?>