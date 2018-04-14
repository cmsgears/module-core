<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\traits\base;

// Yii Imports
use Yii;

/**
 * MetaTrait provide methods for model meta and meta tables.
 *
 * @property string $valueType
 * @property string $value
 *
 * @since 1.0.0
 */
trait MetaTrait {

	// Variables ---------------------------------------------------

	// Globals ----------------

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

	// Private ----------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii classes ---------------------------

	// CMG interfaces ------------------------

	// CMG classes ---------------------------

	// Validators ----------------------------

	// MetaTrait -----------------------------

	/**
	 * @inheritdoc
	 */
	public function generateLabel() {

		$label = preg_replace( '/_/', ' ', $this->name );
		$label = ucwords( $label );

		return $label;
	}

	/**
	 * @inheritdoc
	 */
	public function getLabel() {

		if( empty( $this->label ) ) {

			$this->label = preg_replace( '/_/', ' ', $this->name );
			$this->label = ucwords( $this->label );
		}

		return $this->label;
	}

	/**
	 * @inheritdoc
	 */
	public function isText() {

		return $this->valueType == self::VALUE_TYPE_TEXT;
	}

	/**
	 * @inheritdoc
	 */
	public function isFlag() {

		return $this->valueType == self::VALUE_TYPE_FLAG;
	}

	/**
	 * @inheritdoc
	 */
	public function isList() {

		return $this->valueType == self::VALUE_TYPE_LIST;
	}

	/**
	 * @inheritdoc
	 */
	public function isMap() {

		return $this->valueType == self::VALUE_TYPE_MAP;
	}

	/**
	 * @inheritdoc
	 */
	public function isCsv() {

		return $this->valueType == self::VALUE_TYPE_CSV;
	}

	/**
	 * @inheritdoc
	 */
	public function isObject() {

		return $this->valueType == self::VALUE_TYPE_OBJECT;
	}

	/**
	 * @inheritdoc
	 */
	public function isHtml() {

		return $this->valueType == self::VALUE_TYPE_HTML;
	}

	/**
	 * @inheritdoc
	 */
	public function isMarkdown() {

		return $this->valueType == self::VALUE_TYPE_MARKDOWN;
	}

	/**
	 * @inheritdoc
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
	 * @inheritdoc
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

	/**
	 * @inheritdoc
	 */
	public function getValueTypeStr() {

		return static::$typeMap[ $this->valueType ];
	}

	// Static Methods ----------------------------------------------

	// Yii classes ---------------------------

	// CMG classes ---------------------------

	// MetaTrait -----------------------------

	// Read - Query -----------

	// Read - Find ------------

	// Create -----------------

	// Update -----------------

	// Delete -----------------

}
