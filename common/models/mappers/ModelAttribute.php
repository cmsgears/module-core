<?php
namespace cmsgears\core\common\models\mappers;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

/**
 * ModelAttribute Entity
 *
 * @property long $id
 * @property long $parentId
 * @property string $parentType
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $valueType
 * @property string $value
 */
class ModelAttribute extends \cmsgears\core\common\models\base\Attribute {

    // Variables ---------------------------------------------------

    // Constants/Statics --

    // Public -------------

    // Private/Protected --

    // Traits ------------------------------------------------------

    // Constructor and Initialisation ------------------------------

    // Instance Methods --------------------------------------------

    // yii\base\Component ----------------

    // yii\base\Model --------------------

    /**
     * @inheritdoc
     */
    public function rules() {

        // model rules
        $rules = [
            [ [ 'parentId', 'parentType', 'name' ], 'required' ],
            [ [ 'id', 'value' ], 'safe' ],
            [ [ 'parentType', 'name', 'type', 'valueType' ], 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->mediumText ],
            [ 'label', 'string', 'min' => 1, 'max' => Yii::$app->cmgCore->largeText ],
            [ 'name', 'alphanumu' ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ],
            [ [ 'parentId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
        ];

        // trim if required
        if( Yii::$app->cmgCore->trimFieldValue ) {

            $trim[] = [ [ 'name', 'label', 'value', 'valueType', 'type' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'parentId' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT ),
            'parentType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_PARENT_TYPE ),
            'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'label' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LABEL ),
            'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
            'valueType' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VALUE_TYPE ),
            'value' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VALUE )
        ];
    }

    // ModelAttribute --------------------

    /**
     * Validates to ensure that only one attribute exist with one name.
     */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByTypeName( $this->parentId, $this->parentType, $this->type, $this->name ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

    /**
     * Validates to ensure that only one attribute exist with one name.
     */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            $existingConfig = self::findByTypeName( $this->parentId, $this->parentType, $this->type, $this->name );

            if( isset( $existingConfig ) && $existingConfig->id != $this->id ) {

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

        return CoreTables::TABLE_MODEL_ATTRIBUTE;
    }

    // ModelAttribute --------------------

    // Create -------------

    // Read ---------------

    /**
     * @param integer $parentId
     * @param string $parentType
     * @param string $type
     * @return array - ModelAttribute by type
     */
    public static function findByType( $parentId, $parentType, $type ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND type=:type', [ ':pid' => $parentId, ':ptype' => $parentType, ':type' => $type ] )->all();
    }

    /**
     * @param integer $parentId
     * @param string $parentType
     * @param string $name
     * @return ModelAttribute - by name
     */
    public static function findByName( $parentId, $parentType, $name ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND name=:name', [ ':pid' => $parentId, ':ptype' => $parentType, ':name' => $name ] )->all();
    }

    /**
     * @param integer $parentId
     * @param string $parentType
     * @param string $type
     * @param string $name
     * @return ModelAttribute - by type and name
     */
    public static function findByTypeName( $parentId, $parentType, $type, $name ) {

        return self::find()->where( 'parentId=:pid AND parentType=:ptype AND type=:type AND name=:name',
                [ ':pid' => $parentId, ':ptype' => $parentType, ':type' => $type, ':name' => $name ] )->one();
    }

    /**
     * @param integer $parentId
     * @param string $parentType
     * @param string $type
     * @param string $name
     * @return boolean - Check whether attribute exist by type and name
     */
    public static function isExistByTypeName( $parentId, $parentType, $type, $name ) {

        $config = self::findByTypeName( $parentId, $parentType, $type, $name );

        return isset( $config );
    }

    // Update -------------

    // Delete -------------
}

?>