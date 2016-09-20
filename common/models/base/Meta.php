<?php
namespace cmsgears\core\common\models\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Meta Entity
 *
 * @property long $id
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $valueType
 * @property string $value
 */
abstract class Meta extends Entity {

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

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// Meta ----------------------------------

	public function getLabel() {

		$label		= preg_replace( '/_/', ' ', $this->name );
		$label		= ucwords( $label );

		return $label;
	}

	public function isText() {

		return $this->valueType == self::VALUE_TYPE_TEXT;
	}

	public function isFlag() {

		return $this->valueType == self::VALUE_TYPE_FLAG;
	}

	public function isList() {

		return $this->valueType == self::VALUE_TYPE_LIST;
	}

	public function isMap() {

		return $this->valueType == self::VALUE_TYPE_MAP;
	}

	public function isCsv() {

		return $this->valueType == self::VALUE_TYPE_CSV;
	}

	public function isObject() {

		return $this->valueType == self::VALUE_TYPE_OBJECT;
	}

	public function isHtml() {

		return $this->valueType == self::VALUE_TYPE_HTML;
	}

	public function isMarkdown() {

		return $this->valueType == self::VALUE_TYPE_MARKDOWN;
	}

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
