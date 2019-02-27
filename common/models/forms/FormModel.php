<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\models\forms;

/**
 * Useful for models which need submitted data for special processing.
 *
 * @property array $data
 *
 * @since 1.0.0
 */
abstract class FormModel extends DataModel {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	/**
	 * Stores data submitted for a Form.
	 *
	 * @var array
	 */
	public $data;

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
	 * It process the submitted form data and store the form data using given form name.
	 *
	 * The model attributes will be set by the parent class.
	 *
	 * @param array $data
	 * @param string $formName
	 * @return boolean
	 */
	public function load( $data, $formName = null ) {

		if( isset( $formName ) && isset( $data[ $formName ] ) ) {

			$this->data	= $data[ $formName ];
		}

		return parent::load( $data, $formName );
	}

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Validators ----------------------------

	// FormModel -----------------------------

}
