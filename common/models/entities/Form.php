<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\traits\TemplateTrait;
use cmsgears\core\common\models\traits\AttributeTrait;
use cmsgears\core\common\models\traits\DataTrait;

use cmsgears\core\common\behaviors\AuthorBehavior;

/**
 * Form Entity
 *
 * @property long $id
 * @property long $siteId
 * @property long $templateId
 * @property long $createdBy
 * @property long $modifiedBy
 * @property string $name
 * @property string $slug
 * @property string $type
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
 * @property string $data
 */
class Form extends NamedCmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    const VISIBILITY_PUBLIC     =  0;
    const VISIBILITY_PRIVATE    = 10;

    public static $visibilityMap = [
        self::VISIBILITY_PUBLIC => 'Public',
        self::VISIBILITY_PRIVATE => 'Private'
    ];

    // Public -------------

    public $parentType  = CoreGlobal::TYPE_FORM;

    // Private/Protected --

    // Traits ------------------------------------------------------

    use TemplateTrait;
    use DataTrait;
    use AttributeTrait;

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

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

    public function isPublic() {

        return $this->visibility == self::VISIBILITY_PUBLIC;
    }

    public function isPrivate() {

        return $this->visibility == self::VISIBILITY_PRIVATE;
    }

    // yii\base\Component ----------------

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
            [ [ 'name', 'siteId', 'captcha', 'visibility', 'active' ], 'required' ],
            [ [ 'id', 'description', 'successMessage', 'htmlOptions', 'data' ], 'safe' ],
            [ [ 'name', 'type' ], 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_MEDIUM ],
            [ 'slug', 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_LARGE ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'active', 'userMail', 'adminMail' ], 'boolean' ],
            [ [ 'templateId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'siteId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
            [ [ 'createdAt', 'modifiedAt' ], 'date', 'format' => Yii::$app->formatter->datetimeFormat ]
        ];

        // trim if configured
        if( Yii::$app->cmgCore->trimFieldValue ) {

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
            'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'slug' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
            'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
            'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
            'successMessage' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MESSAGE_SUCCESS ),
            'captcha' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_CAPTCHA ),
            'visibility' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VISIBILITY ),
            'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
            'userMail' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MAIL_USER ),
            'adminMail' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_MAIL_ADMIN ),
            'htmlOptions' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
            'data' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_META )
        ];
    }

    // yii\base\Model --------------------

    // Form --------------------------

    // Static Methods ----------------------------------------------

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_FORM;
    }

    // yii\db\ActiveRecord ---------------

    // Form --------------------------

    // Create -------------

    // Read ---------------

    /**
     * @return Form - by slug.
     */
    public static function findBySlug( $slug ) {

        return self::find()->where( 'slug=:slug', [ ':slug' => $slug ] )->one();
    }

    /**
     * @param string $name
     * @return Block - by name for current site
     */
    public static function findByName( $name ) {

        $siteId = Yii::$app->cmgCore->siteId;

        return static::find()->where( 'name=:name AND siteId=:siteId' )
                            ->addParams( [ ':name' => $name, ':siteId' => $siteId ] )
                            ->one();
    }

    // Update -------------

    // Delete -------------
}

?>