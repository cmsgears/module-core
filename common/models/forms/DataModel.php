<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\forms;

// Yii Imports
use yii\base\Model;

/**
 * The base class for form models which need to process and store the submitted form
 * data in JSON format.
 *
 * It can be used to submit arbitrary forms and store JSON data in data column of the
 * respective table using [[\cmsgears\core\common\models\traits\resources\DataTrait]].
 *
 * @property array $submittedData
 *
 * @since 1.0.0
 */
class DataModel extends Model {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( $data = false, $config = [] ) {

		if( $data ) {

			// Generate form attributes from object
			if( is_object( $data ) ) {

				$this->copyFromObject( $data );
			}
			// Generate form attributes from array
			else if( is_array( $data ) ) {

				$this->setData( $data );
			}
			// Generate form attributes from json
			else {

				$dataMap = json_decode( $data, true );

				$this->setData( $dataMap );
			}
		}

		parent::__construct( $config );
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// yii\base\Object --------

	/**
	 * Override [[\yii\base\BaseObject::__set()]] to set field if it does not exist.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set( $name, $value ) {

		$setter = 'set' . $name;

		if( method_exists( $this, $setter ) ) {

			$this->$setter( $value );
		}
		else {

			$this->$name = $value;
		}
	}

	// yii\base\Component -----

	// yii\base\Model ---------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// DataModel -----------------------------

	/**
	 * Accepts object and generate the model attributes and values using object attributes.
	 *
	 * @param object $object
	 * @return void
	 */
	private function copyFromObject( $object ) {

		$attributes	= get_object_vars( $object );

		foreach( $attributes as $key => $value ) {

			$this->__set( $key, $value );
		}
	}

	/**
	 * Accepts associative array and generate the model attributes using key and
	 * set attribute value using array value.
	 *
	 * @param array $data
	 * @return void
	 */
	private function setData( $data ) {

		foreach( $data as $key => $value ) {

			$this->__set( $key, $value );
		}
	}

	/**
	 * Generate and return JSON data using model attributes.
	 *
	 * @return string
	 */
	public function getData() {

		$json = json_encode( $this );

		return $json;
	}
}
