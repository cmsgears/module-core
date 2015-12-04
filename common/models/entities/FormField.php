<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * FormField Entity
 *
 * @property integer $id
 * @property integer $formId
 * @property string $name
 * @property string $label
 * @property short $type
 * @property boolean $compress
 * @property short $validators
 * @property short $options
 * @property string $data
 * @property short $order
 */
class FormField extends \cmsgears\core\common\models\entities\CmgEntity {

	const TYPE_TEXT				=  0;
	const TYPE_PASSWORD			=  5;
	const TYPE_TEXTAREA			= 10;
	const TYPE_CHECKBOX			= 20;
	const TYPE_CHECKBOX_GROUP	= 25;
	const TYPE_RADIO			= 30;
	const TYPE_RADIO_GROUP		= 35;
	const TYPE_SELECT			= 40;
	const TYPE_RATING			= 50;

	public static $typeMap = [
		self::TYPE_TEXT => 'Text',
		self::TYPE_PASSWORD => 'Password',
		self::TYPE_TEXTAREA => 'Textarea',
		self::TYPE_CHECKBOX => 'Checkbox',
		self::TYPE_CHECKBOX_GROUP => 'Checkbox Group',
		self::TYPE_RADIO => 'Radio',
		self::TYPE_RADIO_GROUP => 'Radio',
		self::TYPE_SELECT => 'Select',
		self::TYPE_RATING => 'Rating'
	];

	public $value;

	// Instance Methods --------------------------------------------

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
	
	public function isPasswordField() {
		
		return $this->type == self::TYPE_PASSWORD;
	}
	
	public function getFieldValue() {
		
		switch( $this->type ) {
			
			case self::TYPE_TEXT: {
				
				return $this->value;
			}
			case self::TYPE_PASSWORD: {
				
				return null;
			}
			case self::TYPE_CHECKBOX: {

				return Yii::$app->formatter->asBoolean( $this->value );
			}
		}
	}

	// yii\db\ActiveRecord ----------------

    /**
     * @inheritdoc
     */
	public function rules() {

		// model rules
        $rules = [
            [ [ 'formId', 'name' ], 'required' ],
			[ [ 'id', 'label', 'type', 'compress', 'validators', 'options', 'data', 'order' ], 'safe' ],
			[ [ 'type', 'compress' ], 'number', 'integerOnly' => true ],
			[ 'name', 'string', 'min' => 1, 'max' => 100 ],
            [ 'name', 'validateNameCreate', 'on' => [ 'create' ] ],
            [ 'name', 'validateNameUpdate', 'on' => [ 'update' ] ]
        ];

		// trim if configured
		if( Yii::$app->cmgCore->trimFieldValue ) {

			$trim[] = [ [ 'name', 'label', 'validators', 'options' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

			return ArrayHelper::merge( $trim, $rules );
		}

		return $rules;
    }

    /**
     * @inheritdoc
     */
	public function attributeLabels() {

		return [
			'formId' => Yii::$app->cmgFormsMessage->getMessage( CoreGlobal::FIELD_FORM ),
			'name' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'label' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_LABEL ),
			'type' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'compress' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_COMPRESS ),
			'validators' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_VALIDATORS ),
			'options' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_OPTIONS ),
			'data' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_META ),
			'order' => Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::FIELD_ORDER )
		];
	}

	// FormField -------------------------

	/**
	 * Validates whether a filed exist with the same name for same form.
	 */
    public function validateNameCreate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

            if( self::isExistByNameFormId( $this->name, $this->formId ) ) {

                $this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
            }
        }
    }

	/**
	 * Validates whether a filed exist with the same name for same form.
	 */
    public function validateNameUpdate( $attribute, $params ) {

        if( !$this->hasErrors() ) {

			$existingField = self::findByNameFormId( $this->name, $this->formId );

			if( isset( $existingField ) && $this->formId == $existingField->formId && 
				$this->id != $existingField->id && strcmp( $existingField->name, $this->name ) == 0 ) {

				$this->addError( $attribute, Yii::$app->cmgCoreMessage->getMessage( CoreGlobal::ERROR_EXIST ) );
			}
        }
    }

	// Static Methods ----------------------------------------------

	// UserMeta ---------------------------

    /**
     * @inheritdoc
     */
	public static function tableName() {

		return CoreTables::TABLE_FORM_FIELD;
	}

	// FormField --------------------------

	public static function findByFormId( $formId ) {

		$frmTable = FormTables::TABLE_FORM;

		return FormField::find()->joinWith( 'form' )->where( "$frmTable.id=:id", [ ':id' => $formId ] )->all();
	}

	public static function findByNameFormId( $name, $formId ) {

		return self::find()->where( "formId=:id and name=:name", [ ':id' => $formId, ':name' => $name ] )->one();
	}

	public static function isExistByNameFormId( $name, $formId ) {

		$field = self::findByNameFormId( $name, $formId );

		return isset( $field );
	}
}

?>