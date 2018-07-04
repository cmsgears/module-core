<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\utilities;

// Yii Imports
use Yii;

/**
 * The class AjaxUtil format the success and failure message for Ajax requests.
 *
 * @since 1.0.0
 */
class AjaxUtil {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// AjaxUtil ------------------------------

	/**
	 * The method generate success response array having 3 elements as listed below:
	 * 1. result - 1 (It indicates that the request is processed successfully)
	 * 2. message - <message value> (The message to be displayed for success)
	 * 3. data - <response data array> (The response data to be used for success condition)
	 *
	 * @param message
	 * @param data
	 */
	public static function generateSuccess( $message, $data = null ) {

		$response = [];

		$response[ 'result' ]	= 1;
		$response[ 'message' ]	= $message;
		$response[ 'data' ]		= $data;

		Yii::$app->response->format = 'json';

		return $response;
	}

	/**
	 * The method generate failure response array having 3 elements as listed below:
	 * 1. result - 0 (It indicates that the request is failed)
	 * 2. message - <message value> (The message to be displayed for failure)
	 * 3. errors - <errors array> (The errors to be displayed for failure)
	 *
	 * @param message
	 * @param errors
	 * @param data
	 */
	public static function generateFailure( $message, $errors = null, $data = null ) {

		$response = [];

		$response[ 'result' ]	= 0;
		$response[ 'message' ]	= $message;
		$response[ 'errors' ]	= $errors;
		$response[ 'data' ]		= $data;

		Yii::$app->response->format = 'json';

		return $response;
	}

	/**
	 * The method generate an array of error messages from model validated by Yii.
	 * @param model
	 * @return array - error messages
	 */
	public static function generateErrorMessage( $source, $config = [] ) {

		$multiple		= isset( $config[ 'multiple' ] ) ? $config[ 'multiple' ] : false;
		$modelClass		= isset( $config[ 'modelClass' ] ) ? $config[ 'modelClass' ] : null;
		$modelErrors	= [];

		if( $multiple ) {

			foreach( $source as $idx => $model ) {

				$errors			= $model->getErrors();
				$modelClass		= isset( $modelClass ) ? $modelClass : $model->getClassName();
				$modelErrors	= [];

				foreach( $errors as $key => $value ) {

					$modelErrors[ "$modelClass[$idx][$key]" ] = $value[ 0 ];
				}
			}
		}
		else {

			$errors			= $source->getErrors();
			$modelClass		= isset( $modelClass ) ? $modelClass : $source->getClassName();
			$modelErrors	= [];

			foreach( $errors as $key => $value ) {

				$modelErrors[ "{$modelClass}[$key]" ] = $value[ 0 ];
			}
		}

		return $modelErrors;
	}
}
