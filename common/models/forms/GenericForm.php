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
 * Process dynamic forms.
 */
class GenericForm extends Model {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	public $active;

	/**
	 * Check for AJAX processing.
	 *
	 * @var boolean
	 */
	public $ajax = false;

	/**
	 * Form fields.
	 *
	 * @var array
	 */
	public $fields;

	/**
	 * Model attributes.
	 *
	 * @var array
	 */
	public $attribs;

	/**
	 * Captcha challenge.
	 *
	 * @var string
	 */
	public $captcha;

	/**
	 * Captcha URL to handle regular forms.
	 *
	 * @var string
	 */
	public $captchaAction;

	/**
	 * Captcha URL to handle AJAX requests.
	 *
	 * @var type
	 */
	public $acaptchaAction;

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function __construct( $config = [] ) {

		$this->fields	= $config[ 'fields' ];
		$this->attribs	= [];
		$fields			= $this->fields;

		unset( $config[ 'fields' ] );

		foreach( $fields as $key => $field ) {

			if( isset( $field->value ) ) {

				$this->__set( $key, $field->value );
			}
			else {

				$this->__set( $key, null );
			}

			$this->attribs[] = $key;
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

	/**
	 * @inheritdoc
	 */
	public function rules() {

		// Prepare validators list
		$validators	= [];
		$fields		= $this->fields;

		foreach( $fields as $key => $field ) {

			if( isset( $field->validators ) ) {

				$fieldValidators = preg_split( "/,/", $field->validators );

				foreach( $fieldValidators as $validator ) {

					if( !isset( $validators[ $validator ] ) ) {

						$validators[ $validator ] = [];
					}

					if( strlen( $validator ) > 0 ) {

						$validators[ $validator ][]	= $field->name;
					}
				}
			}
		}

		$rules = [
			[ $this->attribs, 'safe' ]
		];

		// Handle AJAX captcha validation
		if( $this->ajax ) {

			if( empty( $this->acaptchaAction ) ) {

				$this->acaptchaAction = '/forms/form/acaptcha';
			}

			$rules[] = [ 'captcha', 'captcha', 'captchaAction' => $this->acaptchaAction, 'on' => 'captcha' ];
		}
		// Handle regular captcha validation
		else {

			if( empty( $this->captchaAction ) ) {

				$this->captchaAction = '/forms/form/captcha';
			}

			$rules[] = [ 'captcha', 'captcha', 'captchaAction' => $this->captchaAction, 'on' => 'captcha' ];
		}

		// Generate rules for form fields
		foreach ( $validators as $key => $value ) {

			if( count( $value ) > 0 ) {

				$rules[] = [ $value, $key ];
			}
		}

		return $rules;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {

		$fields	= $this->fields;
		$labels	= [];

		foreach( $fields as $key => $field ) {

			if( isset( $field->label ) ) {

				$labels[ $key ] = $field->label;
			}
			else {

				$labels[ $key ] = $key;
			}
		}

		return $labels;
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// GenericForm ---------------------------

	/**
	 * Collect and return the list of class members and their values using reflection.
	 *
	 * @return array List of class members and their value.
	 */
	public function getFormAttributes( $classPath = null ) {

		if( !isset( $classPath ) ) {

			$classPath	= 'cmsgears\core\common\models\forms\GenericForm';
		}

		$refclass	= new \ReflectionClass( $classPath );
		$attribArr	= array();

		foreach ( $refclass->getProperties() as $property ) {

			$name = $property->name;

			if ( $property->class == $refclass->name ) {

				$attribArr[ $name ] = $this->$name;
			}
		}

		return $attribArr;
	}
}
