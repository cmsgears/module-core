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

use cmsgears\core\common\models\interfaces\IOwner;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\VisualTrait;
use cmsgears\core\common\models\traits\AttributeTrait;
use cmsgears\core\common\models\traits\FileTrait;
use cmsgears\core\common\models\traits\TemplateTrait;
use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\DataTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * ObjectData Entity
 *
 * @property long $id
 * @property long $siteId
 * @property long $templateId
 * @property long $avatarId
 * @property long $bannerId
 * @property long $createdBy
 * @property long $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $icon
 * @property string $type
 * @property string $description
 * @property boolean $active
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $htmlOptions
 * @property string $content
 * @property string $data
 */
class ObjectData extends \cmsgears\core\common\models\base\TypedCmgEntity implements IOwner {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    protected static $siteSpecific	= true;

    // Public -------------

    public $parentType  = CoreGlobal::TYPE_OBJECT;

    // Private/Protected --

	protected $checkOwnership	= false;

    // Traits ------------------------------------------------------

    use VisualTrait;
    use AttributeTrait;
    use FileTrait;
    use TemplateTrait;
    use CreateModifyTrait;
    use DataTrait;

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

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
                'ensureUnique' => true
            ],
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'modifiedAt',
                'value' => new Expression('NOW()')
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
            [ [ 'siteId', 'name', 'type' ], 'required' ],
            [ [ 'id', 'htmlOptions', 'content', 'data' ], 'safe' ],
            [ [ 'name', 'icon', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->largeText ],
            [ [ 'description' ], 'string', 'min' => 0, 'max' => Yii::$app->cmgCore->extraLargeText ],
			[ 'name', 'alphanumpun' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
			[ [ 'active' ], 'boolean' ],
            [ [ 'templateId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'siteId', 'avatarId', 'bannerId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
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
            'templateId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TEMPLATE ),
            'avatarId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_AVATAR ),
            'bannerId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_BANNER ),
            'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'icon' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ICON ),
            'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
            'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
            'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
            'htmlOptions' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
            'content' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
            'data' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DATA )
        ];
    }

	// yii\db\BaseActiveRecord -----------

	public function beforeSave( $insert ) {

	    if( parent::beforeSave( $insert ) ) {

			if( $this->templateId <= 0 ) {

				$this->templateId = null;
			}

	        return true;
	    }

		return false;
	}

	// IOwner ----------------------------

	public function isOwner( $user = null ) {

		if( $this->checkOwnership ) {

			if( !isset( $user ) ) {

				$user	= Yii::$app->user->getIdentity();
			}

			if( isset( $user ) ) {

				return $this->createdBy == $user->id;
			}
		}

		return false;
	}

    // ObjectData ------------------------

    public function getSite() {

        return $this->hasOne( Site::className(), [ 'id' => 'siteId' ] );
    }

    /**
     * @return string representation of flag
     */
    public function getActiveStr() {

        return Yii::$app->formatter->asBoolean( $this->active );
    }

    // Static Methods ----------------------------------------------

    // yii\db\ActiveRecord ---------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_OBJECT_DATA;
    }

    // ObjectData ------------------------

    // Create -------------

    // Read ---------------

    /**
     * @return ObjectData - by slug
     */
    public static function findBySlug( $slug ) {

        return self::find()->where( 'slug=:slug', [ ':slug' => $slug ] )->one();
    }

    // Update -------------

    // Delete -------------
}

?>