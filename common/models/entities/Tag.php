<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\SluggableBehavior;
use yii\validators\FilterValidator;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Tag Entity
 *
 * @property long $id
 * @property long $siteId
 * @property string $name
 * @property string $slug
 * @property string $icon
 * @property string $type
 */
class Tag extends CmgEntity {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    public function getSite() {

        return $this->hasOne( Site::className(), [ 'id' => 'siteId' ] );
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
            [ [ 'siteId', 'name' ], 'required' ],
            [ 'id', 'safe' ],
            [ 'name', 'alphanumhyphenspace' ],
            [ [ 'name', 'type', 'icon' ], 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_MEDIUM ],
            [ 'slug', 'string', 'min' => 1, 'max' => CoreGlobal::TEXT_LARGE ],
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
            'slug' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_SLUG ),
            'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE )
        ];
    }

    // Tag -------------------------------

    /**
     * Validates to ensure that name is used only for one tag for a particular type
     */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameType( $this->name, $this->type ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    /**
     * Validates to ensure that name is used only for one tag for a particular type
     */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            $existingTag = self::findByNameType( $this->name, $this->type );

            if( isset( $existingTag ) && $existingTag->id != $this->id ) {

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

        return CoreTables::TABLE_TAG;
    }

    // Tag -------------------------------

    // Create -------------

    // Read ---------------

    /**
     * @return Tag - by type
     */
    public static function findBySlug( $slug ) {

        return self::find()->where( 'slug=:slug', [ ':slug' => $slug ] )->one();
    }

    /**
     * @return Tag - by type
     */
    public static function findByType( $type ) {

        return self::find()->where( 'type=:type', [ ':type' => $type ] )->all();
    }

    /**
     * @return Tag - by type and name
     */
    public static function findByNameType( $name, $type ) {

        $siteId = Yii::$app->cmgCore->siteId;

        return self::find()->where( 'name=:name AND type=:type AND siteId=:siteId', [ ':name' => $name, ':type' => $type, ':siteId' => $siteId ] )->one();
    }

    /**
     * @return Tag - checks whether tag exist by type and name
     */
    public static function isExistByNameType( $name, $type ) {

        $tag = self::findByNameType( $name, $type );

        return isset( $tag );
    }

    // Update -------------

    // Delete -------------
}

?>