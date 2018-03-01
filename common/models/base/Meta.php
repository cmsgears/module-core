<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\base;

// Yii Imports
use Yii;

/**
 * Meta represents meta data of a model.
 *
 * A model can have multiple meta mapped to it, but only one meta with the same name of
 * a particular type is allowed for a model. We can have value type hints using $valueType.
 *
 * @property int $id
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $valueType
 * @property string $value
 *
 * @since 1.0.0
 */
abstract class Meta extends ActiveRecord {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	const VALUE_TYPE_TEXT		= 'text';
	const VALUE_TYPE_FLAG		= 'flag';
	const VALUE_TYPE_LIST		= 'list';
	const VALUE_TYPE_MAP		= 'map';
	const VALUE_TYPE_CSV		= 'csv';
	const VALUE_TYPE_OBJECT		= 'json';
	const VALUE_TYPE_HTML		= 'html';
	const VALUE_TYPE_MARKDOWN	= 'markdown';

	// Public -----------------

	/**
	 * Stores the map of types having value type as key and value as text representation of key.
	 *
	 * @var array
	 */
	public static $typeMap	= [
		self::VALUE_TYPE_TEXT => 'Text',
		self::VALUE_TYPE_FLAG => 'Flag',
		self::VALUE_TYPE_LIST => 'List',
		self::VALUE_TYPE_MAP => 'Map',
		self::VALUE_TYPE_CSV => 'Csv',
		self::VALUE_TYPE_OBJECT => 'Object',
		self::VALUE_TYPE_HTML => 'Html',
		self::VALUE_TYPE_MARKDOWN => 'Markdown'
	];

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

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
			[ 'name', 'required' ],
			[ [ 'id', 'value' ], 'safe' ],
			// Unique
			[ [ 'modelId', 'name', 'type' ], 'unique', 'targetAttribute' => [ 'modelId', 'name', 'type' ], 'comboNotUnique' => Yii::$app->coreMessage->getMessage( CoreGlobal::ERROR_EXIST ) ],
			// Text Limit
			[ [ 'type', 'valueType' ], 'string', 'min' => 1, 'max' => Yii::$app->core->mediumText ],
			[ 'name', 'string', 'min' => 1, 'max' => Yii::$app->core->xLargeText ],
			[ 'label', 'string', 'min' => 1, 'max' => Yii::$app->core->xxLargeText ],
		];

		// Trim Text
		if( Yii::$app->core->trimFieldValue ) {

			$trim[] = [ [ 'name', 'type', 'valueType', 'value' ], 'filter', 'filter' => 'trim', 'skipOnArray' => true ];

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
			'label' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_LABEL ),
			'type' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_TYPE ),
			'valueType' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE_TYPE ),
			'value' => Yii::$app->coreMessage->getMessage( CoreGlobal::FIELD_VALUE )
		];
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Meta ----------------------------------

	/**
	 * Generate and return label using meta name.
	 *
	 * It replace underscore(_) by space and update first letter of words to uppercase.
	 *
	 * @return string generated label.
	 */
	public function getLabel() {

		$label		= preg_replace( '/_/', ' ', $this->name );
		$label		= ucwords( $label );

		return $label;
	}

	/**
	 * Check whether value type is text.
	 *
	 * @return boolean
	 */
	public function isText() {

		return $this->valueType == self::VALUE_TYPE_TEXT;
	}

	/**
	 * Check whether value type is boolean i.e. 0 or 1.
	 *
	 * @return boolean
	 */
	public function isFlag() {

		return $this->valueType == self::VALUE_TYPE_FLAG;
	}

	/**
	 * Check whether value type is list i.e. indexed array.
	 *
	 * @return boolean
	 */
	public function isList() {

		return $this->valueType == self::VALUE_TYPE_LIST;
	}

	/**
	 * Check whether value type is map i.e. associative array.
	 *
	 * @return boolean
	 */
	public function isMap() {

		return $this->valueType == self::VALUE_TYPE_MAP;
	}

	/**
	 * Check whether value type is csv i.e. string having comma separated values.
	 *
	 * @return boolean
	 */
	public function isCsv() {

		return $this->valueType == self::VALUE_TYPE_CSV;
	}

	/**
	 * Check whether value type is object i.e. JOSN.
	 *
	 * @return boolean
	 */
	public function isObject() {

		return $this->valueType == self::VALUE_TYPE_OBJECT;
	}

	/**
	 * Check whether value type is HTML.
	 *
	 * @return boolean
	 */
	public function isHtml() {

		return $this->valueType == self::VALUE_TYPE_HTML;
	}

	/**
	 * Check whether value type is Markdown.
	 *
	 * @return boolean
	 */
	public function isMarkdown() {

		return $this->valueType == self::VALUE_TYPE_MARKDOWN;
	}

	/**
	 * Convert and return the value stored in [[value]] using [[valueType]].
	 *
	 * @return mixed Converted value.
	 */
	public function getFieldValue() {

		switch( $this->valueType ) {

			case self::VALUE_TYPE_TEXT: {

				return $this->value;
			}
			case self::VALUE_TYPE_FLAG: {

				return Yii::$app->formatter->asBoolean( $this->value );
			}
			case self::VALUE_TYPE_LIST: {

				return json_decode( $this->value );
			}
			case self::VALUE_TYPE_MAP: {

				return json_decode( $this->value, true );
			}
			case self::VALUE_TYPE_OBJECT: {

				return json_decode( $this->value );
			}
			default: {

				return $this->value;
			}
		}
	}

	/**
	 * Generate and return the map having label, name and value as keys.
	 *
	 * @return array Map of label, name and value.
	 */
	public function getFieldInfo() {

		switch( $this->valueType ) {

			case self::VALUE_TYPE_TEXT: {

				return [ 'label' => $this->getLabel(), 'name' => $this->name, 'value' => $this->value ];
			}
			case self::VALUE_TYPE_FLAG: {

				$value = Yii::$app->formatter->asBoolean( $this->value );

				return [ 'label' => $this->getLabel(), 'name' => $this->name, 'value' => $value ];
			}
			default: {

				return [ 'label' => $this->getLabel(), 'name' => $this->name, 'value' => $this->value ];
			}
		}
	}

	// Static Methods ----------------------------------------------

	// Yii parent classes --------------------

	// yii\db\ActiveRecord ----

	// CMG parent classes --------------------

	// Meta ----------------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
