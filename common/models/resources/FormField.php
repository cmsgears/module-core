<?php
namespace cmsgears\core\common\models\resources;

// Yii Imports
use \Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;

use cmsgears\core\common\models\traits\resources\DataTrait;

/**
 * FormField Entity
 *
 * @property long $id
 * @property long $formId
 * @property string $name
 * @property string $label
 * @property short $type
 * @property boolean $compress
 * @property string $validators
 * @property short $order
 * @property string $icon
 * @property string $htmlOptions
 * @property string $content
 * @property string $data
 */
class FormField extends \cmsgears\core\common\models\base\Resource {

	// TODO: further analysis is required to remove the alphanumu validator for name field to support html forms.

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

    const TYPE_TEXT             =   0;
	const TYPE_HIDDEN           =   5;
    const TYPE_PASSWORD         =  10;
    const TYPE_TEXTAREA         =  20;
    const TYPE_CHECKBOX         =  30;
    const TYPE_TOGGLE           =  40;
    const TYPE_CHECKBOX_GROUP   =  50;
    const TYPE_RADIO            =  60;
    const TYPE_RADIO_GROUP      =  70;
    const TYPE_SELECT           =  80;
    const TYPE_RATING           =  90;
    const TYPE_ICON             = 100;
    const TYPE_DATE             = 110;

	// Public -----------------

    public static $typeMap = [
        self::TYPE_TEXT => 'Text',
        self::TYPE_HIDDEN => 'Hidden',
        self::TYPE_PASSWORD => 'Password',
        self::TYPE_TEXTAREA => 'Textarea',
        self::TYPE_CHECKBOX => 'Checkbox',
        self::TYPE_TOGGLE => 'Toggle Button',
        self::TYPE_CHECKBOX_GROUP => 'Checkbox Group',
        self::TYPE_RADIO => 'Radio',
        self::TYPE_RADIO_GROUP => 'Radio Group',
        self::TYPE_SELECT => 'Select',
        self::TYPE_RATING => 'Rating',
        self::TYPE_ICON => 'Icon',
        self::TYPE_DATE => 'Date'
    ];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $mParentType	= CoreGlobal::TYPE_FORM_FIELD;

	public $value;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	use DataTrait;

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Component -----

	// yii\base\Model ---------

    /**
     * @inheritdoc
     */
    public function rules() {

        // model rules
        $rules = [
            [ [ 'formId', 'name' ], 'required' ],
            [ [ 'id', 'htmlOptions', 'content', 'data' ], 'safe' ],
            [ [ 'name', 'icon' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
            [ [ 'label', 'validators' ], 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
            [ 'name', 'alphanumu' ],
            [ [ 'formId', 'name' ], 'unique', 'targetAttribute' => [ 'formId', 'name' ] ],
            [ [ 'type', 'order' ], 'number', 'integerOnly' => true ],
            [ 'compress', 'boolean' ]
        ];

        // trim if configured
        if( Yii::$app->cmgCore->trimFieldValue ) {

            $trim[] = [ [ 'name', 'label', 'validators', 'htmlOptions' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

            return ArrayHelper::merge( $trim, $rules );
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {

        return [
            'formId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_FORM ),
            'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
            'label' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LABEL ),
            'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
            'compress' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_COMPRESS ),
            'validators' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALIDATORS ),
            'order' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ORDER ),
            'icon' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ICON ),
            'htmlOptions' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_HTML_OPTIONS ),
            'data' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_META )
        ];
    }

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// FormField -----------------------------

    /**
     * @return Form
     */
    public function getForm() {

        return $this->hasOne( Form::className(), [ 'id' => 'formId' ] );
    }

    public function getTypeStr() {

        return self::$typeMap[ $this->type ];
    }

    public function getCompressStr() {

        return Yii::$app->formatter->asBoolean( $this->compress );
    }

    public function isText() {

        return $this->type == self::TYPE_TEXT;
    }

    public function isTextArea() {

        return $this->type == self::TYPE_TEXTAREA;
    }

    public function isRadio() {

        return $this->type == self::TYPE_RADIO;
    }

    public function isRadioGroup() {

        return $this->type == self::TYPE_RADIO_GROUP;
    }

    public function isSelect() {

        return $this->type == self::TYPE_SELECT;
    }

    public function isRating() {

        return $this->type == self::TYPE_RATING;
    }

    public function isIcon() {

        return $this->type == self::TYPE_ICON;
    }

    public function isCheckboxGroup() {

        return $this->type == self::TYPE_CHECKBOX_GROUP;
    }

    public function isPassword() {

        return $this->type == self::TYPE_PASSWORD;
    }

    public function isCheckbox() {

        return $this->type == self::TYPE_CHECKBOX;
    }

    public function isToggle() {

        return $this->type == self::TYPE_TOGGLE;
    }

    public function getFieldValue() {

        switch( $this->type ) {

            case self::TYPE_TEXT:
            case self::TYPE_TEXTAREA:
            case self::TYPE_RADIO:
            case self::TYPE_RADIO_GROUP:
            case self::TYPE_SELECT:
            case self::TYPE_RATING:
            case self::TYPE_ICON:
            case self::TYPE_CHECKBOX_GROUP: {

                return $this->value;
            }
            case self::TYPE_PASSWORD: {

                return null;
            }
            case self::TYPE_CHECKBOX:
            case self::TYPE_TOGGLE: {

                return Yii::$app->formatter->asBoolean( $this->value );
            }
        }
    }

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

    /**
     * @inheritdoc
     */
    public static function tableName() {

        return CoreTables::TABLE_FORM_FIELD;
    }

	// CMG parent classes --------------------

	// FormField -----------------------------

	// Read - Query -----------

	public static function queryWithAll( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'form' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	public static function queryWithForm( $config = [] ) {

		$config[ 'relations' ]	= [ 'form' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

    public static function findByFormId( $formId ) {

        return self::find()->where( "formId=:id", [ ':id' => $formId ] )->all();
    }

    public static function findByNameFormId( $name, $formId ) {

        return self::find()->where( "formId=:id and name=:name", [ ':id' => $formId, ':name' => $name ] )->one();
    }

    public static function isExistByNameFormId( $name, $formId ) {

        $field = self::findByNameFormId( $name, $formId );

        return isset( $field );
    }

	// Create -----------------

	// Update -----------------

	// Delete -----------------
}

?>