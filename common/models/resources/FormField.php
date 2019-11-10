<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\resources;

// Yii Imports
use Yii;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\interfaces\resources\IData;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\base\Resource;

use cmsgears\core\common\models\traits\resources\DataTrait;

/**
 * Field represents the form field to be submitted with the form.
 *
 * @property integer $id
 * @property integer $formId
 * @property integer $categoryId
 * @property string $name
 * @property string $label
 * @property short $type
 * @property string $icon
 * @property boolean $compress
 * @property boolean $meta
 * @property boolean $active
 * @property string $validators
 * @property integer $order
 * @property string $htmlOptions
 * @property string $content
 * @property string $data
 *
 * @since 1.0.0
 */
class FormField extends Resource implements IData {

	// TODO: further analysis is required to remove the alphanumu validator for name field to support html forms.

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const TYPE_TEXT				=	0;
	const TYPE_HIDDEN			=	5;
	const TYPE_PASSWORD			=  10;
	const TYPE_TEXTAREA			=  20;
	const TYPE_CHECKBOX			=  30;
	const TYPE_TOGGLE			=  40;
	const TYPE_CHECKBOX_GROUP	=  50;
	const TYPE_RADIO			=  60;
	const TYPE_RADIO_GROUP		=  70;
	const TYPE_SELECT			=  80;
	const TYPE_RATING			=  90;
	const TYPE_ICON				= 100;
	const TYPE_DATE				= 110;

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

	public $value;

	// Protected --------------

	protected $modelType = CoreGlobal::TYPE_FORM_FIELD;

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

		// Model Rules
		$rules = [
			// Required, Safe
			[ [ 'formId', 'name' ], 'required' ],
			[ [ 'id', 'htmlOptions', 'content', 'data' ], 'safe' ],
			// Unique
			[ 'name', 'unique', 'targetAttribute' => [ 'formId', 'name' ] ],
			// Text Limit
			[ 'icon', 'string', 'min' => 1, 'max' => Yii::$app->core->largeText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'label', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
			[ 'validators', 'string', 'min' => 1, 'max' => Yii::$app->core->xxxLargeText ],
			// Other
			[ 'name', 'alphanumu' ],
			[ [ 'type', 'order' ], 'number', 'integerOnly' => true ],
			[ [ 'compress', 'meta', 'active' ], 'boolean' ],
			[ [ 'formId', 'categoryId' ], 'number', 'integerOnly' => true, 'min' => 1 ]
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

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
			'categoryId' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_OPTION ),
			'name' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_NAME ),
			'label' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LABEL ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'compress' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_COMPRESS ),
			'meta' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_META ),
			'active' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_ACTIVE ),
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
	 * Returns the form associated with the field.
	 *
	 * @return Form
	 */
	public function getForm() {

		return $this->hasOne( Form::class, [ 'id' => 'formId' ] );
	}

	/**
	 * Returns the option group associated with the field.
	 *
	 * @return Form
	 */
	public function getOptionGroup() {

		return $this->hasOne( Category::class, [ 'id' => 'categoryId' ] );
	}

	/**
	 * Returns string representation of [[$type]].
	 *
	 * @return string
	 */
	public function getTypeStr() {

		return self::$typeMap[ $this->type ];
	}

	/**
	 * Returns string representation of compress flag.
	 *
	 * @return string
	 */
	public function getCompressStr() {

		return Yii::$app->formatter->asBoolean( $this->compress );
	}

	/**
	 * Returns string representation of meta flag.
	 *
	 * @return string
	 */
	public function getMetaStr() {

		return Yii::$app->formatter->asBoolean( $this->meta );
	}

	/**
	 * Returns string representation of active flag.
	 *
	 * @return string
	 */
	public function getActiveStr() {

		return Yii::$app->formatter->asBoolean( $this->active );
	}

	/**
	 * Check whether field is text.
	 *
	 * @return boolean
	 */
	public function isText() {

		return $this->type == self::TYPE_TEXT;
	}

	/**
	 * Check whether field is text area.
	 *
	 * @return boolean
	 */
	public function isTextArea() {

		return $this->type == self::TYPE_TEXTAREA;
	}

	/**
	 * Check whether field is radio.
	 *
	 * @return boolean
	 */
	public function isRadio() {

		return $this->type == self::TYPE_RADIO;
	}

	/**
	 * Check whether field is radio group.
	 *
	 * @return boolean
	 */
	public function isRadioGroup() {

		return $this->type == self::TYPE_RADIO_GROUP;
	}

	/**
	 * Check whether field is select.
	 *
	 * @return boolean
	 */
	public function isSelect() {

		return $this->type == self::TYPE_SELECT;
	}

	/**
	 * Check whether field is rating.
	 *
	 * @return boolean
	 */
	public function isRating() {

		return $this->type == self::TYPE_RATING;
	}

	/**
	 * Check whether field is icon.
	 *
	 * @return boolean
	 */
	public function isIcon() {

		return $this->type == self::TYPE_ICON;
	}

	/**
	 * Check whether field is password.
	 *
	 * @return boolean
	 */
	public function isPassword() {

		return $this->type == self::TYPE_PASSWORD;
	}

	/**
	 * Check whether field is checkbox.
	 *
	 * @return boolean
	 */
	public function isCheckbox() {

		return $this->type == self::TYPE_CHECKBOX;
	}

	/**
	 * Check whether field is toggle switch.
	 *
	 * @return boolean
	 */
	public function isToggle() {

		return $this->type == self::TYPE_TOGGLE;
	}


	/**
	 * Check whether field is checkbox group.
	 *
	 * @return boolean
	 */
	public function isCheckboxGroup() {

		return $this->type == self::TYPE_CHECKBOX_GROUP;
	}

	/**
	 * Identify the field value type and return the value according to type.
	 *
	 * @return mixed
	 */
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

		return CoreTables::getTableName( CoreTables::TABLE_FORM_FIELD );
	}

	// CMG parent classes --------------------

	// FormField -----------------------------

	// Read - Query -----------

	/**
	 * @inheritdoc
	 */
	public static function queryWithHasOne( $config = [] ) {

		$relations				= isset( $config[ 'relations' ] ) ? $config[ 'relations' ] : [ 'form' ];
		$config[ 'relations' ]	= $relations;

		return parent::queryWithAll( $config );
	}

	/**
	 * Return query to find the field with form.
	 *
	 * @param array $config
	 * @return \yii\db\ActiveQuery to query with form.
	 */
	public static function queryWithForm( $config = [] ) {

		$config[ 'relations' ]	= [ 'form' ];

		return parent::queryWithAll( $config );
	}

	// Read - Find ------------

	/**
	 * Find and return all the fields mapped to given form id.
	 *
	 * @param integer $formId
	 * @return FormField[]
	 */
	public static function findByFormId( $formId ) {

		return self::find()->where( "formId=:id", [ ':id' => $formId ] )->all();
	}

	/**
	 * Find and return the field mapped to given name and form id.
	 *
	 * @param string $name
	 * @param integer $formId
	 * @return FormField
	 */
	public static function findByNameFormId( $name, $formId ) {

		return self::find()->where( "formId=:id and name=:name", [ ':id' => $formId, ':name' => $name ] )->one();
	}

	/**
	 * Check whether field exist for given name and form id.
	 *
	 * @param string $name
	 * @param integer $formId
	 * @return boolean
	 */
	public static function isExistByNameFormId( $name, $formId ) {

		$field = self::findByNameFormId( $name, $formId );

		return isset( $field );
	}

	// Create -----------------

	// Update -----------------

	// Delete -----------------

	/**
	 * Deletes all the fields mapped to given form id.
	 *
	 * @param integer $formId
	 * @return integer Number of rows deleted
	 */
	public static function deleteByFormId( $formId ) {

		return self::deleteAll( 'formId=:fid', [ ':fid' => $formId ] );
	}

}
