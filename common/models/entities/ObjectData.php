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

use cmsgears\cms\admin\models\forms\WidgetForm;

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
 * @property string $data
 */
class ObjectData extends CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    use TemplateTrait;
    use CreateModifyTrait;
    use DataTrait;

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    public function getSite() {

        return $this->hasOne( Site::className(), [ 'id' => 'siteId' ] );
    }

    /**
     * @return string representation of flag
     */
    public function getActiveStr() {

        return Yii::$app->formatter->asBoolean( $this->active );
    }

    public function getWidgetForm() {

        $form = new WidgetForm();
    }

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
            [ [ 'siteId', 'name' ], 'required' ],
            [ [ 'id', 'templateId', 'slug', 'icon', 'description', 'htmlOptions', 'data' ], 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ [ 'active' ], 'boolean' ],
            [ [ 'name', 'icon', 'type' ], 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_MEDIUM ],
            [ 'slug', 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_LARGE ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'templateId' ], 'number', 'integerOnly' => true, 'min' => 0, 'tooSmall' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_SELECT ) ],
            [ [ 'siteId', 'createdBy', 'modifiedBy' ], 'number', 'integerOnly' => true, 'min' => 1 ],
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
            'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'icon' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ICON ),
            'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
            'description' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DESCRIPTION ),
            'active' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
            'htmlOptions' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
            'data' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_DATA )
        ];
    }

    // ObjectData ------------------------

    /**
     * Validates whether a objext existing with the same name and type for same site.
     */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameType( $this->name, $this->type ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    /**
     * Validates whether a objext existing with the same name and type for same site.
     */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            $existingObj = self::findByNameType( $this->name, $this->type );

            if( isset( $existingObj ) && $this->id != $existingObj->id ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
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
     * @return ObjectData - by slug and type
     */
    public static function findBySlugType( $slug, $type ) {

        return self::find()->where( 'slug=:slug AND type=:type', [ ':slug' => $slug, ':type' => $type ] )->one();
    }

    /**
     * @return ObjectData - by name and type for current site
     */
    public static function findByNameType( $name, $type ) {

        $siteId = Yii::$app->cmgCore->siteId;

        return self::find()->where( 'name=:name AND type=:type AND siteId=:siteId', [ ':name' => $name, ':type' => $type, ':siteId' => $siteId ] )->one();
    }

    /**
     * @return boolean - check whether a object exist by the provided name and type for current site
     */
    public static function isExistByNameType( $name, $type ) {

        $obj = self::findByNameType( $name, $type );

        return isset( $obj );
    }

    // Update -------------

    // Delete -------------
}

?>