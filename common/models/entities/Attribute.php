<?php
namespace cmsgears\core\common\models\entities;

// Yii Imports
use \Yii;
use yii\validators\FilterValidator;
use yii\helpers\ArrayHelper;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * Attribute Entity
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $value
 */
abstract class Attribute extends CmgModel {
	
	const VALUE_TYPE_TEXT		= 'text';
	const VALUE_TYPE_FLAG		= 'flag';
	const VALUE_TYPE_CSV		= 'csv';
	const VALUE_TYPE_HTML		= 'html';
	const VALUE_TYPE_JSON		= 'json';
	const VALUE_TYPE_MARKDOWN	= 'markdown';

	// Instance Methods --------------------------------------------

	public function getLabel() {

		$label  = preg_split( "/_/", $this->name );
		$label	= join( " ", $label );
		$label	= ucwords( $label );

		return $label;
	}

	public function getFieldValue() {

		switch( $this->valueType ) {

			case self::VALUE_TYPE_TEXT: {

				return $this->value;
			}
			case self::VALUE_TYPE_FLAG: {

				return Yii::$app->formatter->asBoolean( $this->value );
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
}

?>