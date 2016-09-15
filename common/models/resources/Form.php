<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\IVisibility;
use cmsgears\core\common\models\interfaces\IOwner;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\Site;

use cmsgears\core\common\models\traits\CreateModifyTrait;
use cmsgears\core\common\models\traits\NameTypeTrait;
use cmsgears\core\common\models\traits\SlugTypeTrait;
use cmsgears\core\common\models\traits\interfaces\OwnerTrait;
use cmsgears\core\common\models\traits\interfaces\VisibilityTrait;
use cmsgears\core\common\models\traits\resources\MetaTrait;
use cmsgears\core\common\models\traits\resources\DataTrait;
use cmsgears\core\common\models\traits\mappers\TemplateTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Form Entity
 *
 * @property integer $id
 * @property integer $siteId
 * @property integer $templateId
 * @property integer $createdBy
 * @property integer $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
 * @property string $icon
 * @property string $description
 * @property string $successMessage
 * @property boolean $captcha
 * @property boolean $visibility
 * @property boolean $active
 * @property boolean $userMail
 * @property boolean $adminMail
 * @property datetime $createdAt
 * @property datetime $modifiedAt
 * @property string $htmlOptions
 * @property string $content
 * @property string $data
 * @property boolean $uniqueSubmit
 */
class Form extends \cmsgears\core\common\models\base\Resource implements IVisibility {

    // Variables ---------------------------------------------------

    // Globals -------------------------------

    // Constants --------------

    // Public -----------------

    // Protected --------------

    public static $multiSite	= true;

    // Variables -----------------------------

    // Public -----------------

    public $mParentType	= CoreGlobal::TYPE_FORM;

    // Protected --------------

    // Private ----------------

    // Traits ------------------------------------------------------

    use MetaTrait;
    use CreateModifyTrait;
    use DataTrait;
    use NameTypeTrait;
    use OwnerTrait;
    use SlugTypeTrait;
    use TemplateTrait;
    use VisibilityTrait;

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
            'authorBehavior' => [
                'class' => AuthorBehavior::className()
            ],
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'modifiedAt',
                'value' => new Expression('NOW()')
            ],
            'sluggableBehavior' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => true,
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
            [ [ 'name', 'siteId', 'captcha', 'visibility', 'active' ], 'required' ],
            [ [ 'id', 'htmlOptions', 'content', 'data' ], 'safe' ],
            [ [ 'name', 'type' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
            [ 'slug', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
            [ [ 'description', 'successMessage' ], 'string', 'min' => 0, 'max' => Yii::$app->core->xLargeText ],
            [ [ 'name', 'type' ], 'unique', 'targetAttribute' => [ 'name', 'type' ] ],
            [ [ 'visibility' ], 'number', 'integerOnly' => true, 'min' => 0 ],
            [ [ 'captcha', 'active', 'userMail', 'adminMail', 'uniqueSubmit' ], 'boolean' ],
            [ [ 'templateId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'siteId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

        // trim if configured
        if( Yii::$app->core->trimFieldValue ) {

            $trim[] = [ [ 'name', 'description', 'successMessage', 'htmlOptions' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
            'successMessage' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MESSAGE_SUCCESS ),
            'captcha' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CAPTCHA ),
            'visibility' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VISIBILITY ),
            'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
            'userMail' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MAIL_USER ),
            'adminMail' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_MAIL_ADMIN ),
            'htmlOptions' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
            'content' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_CONTENT ),
            'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_META )
        ];
    }

    // yii\db\BaseActiveRecord

    public function beforeSave( $insert ) {

        if( parent::beforeSave( $insert ) ) {

            if( $this->templateId <= 0 ) {

                $this->templateId = null;
            }

            return true;
        }

        return false;
    }

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // Validators ----------------------------

    // Form ----------------------------------

    public function getSite() {

        return $this->hasOne( Site::className(), [ 'id' => 'siteId' ] );
    }

    /**
     * @return array - array of FormField
     */
    public function getFields() {

        return $this->hasMany( FormField::className(), [ 'formId' => 'id' ] );
    }

    /**
     * @return array - map of FormField having field name as key
     */
    public function getFieldsMap() {

        $formFields     = $this->fields;
        $formFieldsMap  = array();

        foreach ( $formFields as $formField ) {

            $formFieldsMap[ $formField->name ] = $formField;
        }

        return $formFieldsMap;
    }

    public function getCaptchaStr() {

        return Yii::$app->formatter->asBoolean( $this->captcha );
    }

    public function getVisibilityStr() {

        return self::$visibilityMap[ $this->visibility ];
    }

    public function getActiveStr() {

        return Yii::$app->formatter->asBoolean( $this->active );
    }

    // Send mail to user if set and email field exist
    public function getUserMailStr() {

        return Yii::$app->formatter->asBoolean( $this->userMail );
    }

    // Send mail to admin if set
    public function getAdminMailStr() {

        return Yii::$app->formatter->asBoolean( $this->adminMail );
    }

    // Static Methods ----------------------------------------------

    // Yii parent classes --------------------

    // yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_FORM;
    }

    // CMG parent classes --------------------

    // Form ----------------------------------

    // Read - Query -----------

    public static function queryWithHasOne( $config = [] ) {

        $relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'site', 'template', 'creator', 'modifier' ];
        $config[ 'relations' ]	= $relations;

        return parent::queryWithAll( $config );
    }

    public static function queryWithFields( $config = [] ) {

        $config[ 'relations' ]	= [ 'fields' ];

        return parent::queryWithAll( $config );
    }

    // Read - Find ------------

    // Create -----------------

    // Update -----------------

    // Delete -----------------
}
