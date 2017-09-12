<?php
namespace cmsgears\core\common\utilities;

// Yii Imports
use \Yii;

/**
 * The class AjaxUtil format the success and failure message for Ajax requests.
 */
class AjaxUtil {

	// Static Methods ----------------------------------------------

	/**
	 * The method generate success response array having 3 elements as listed below:
	 * 1. result - 1 (It indicates that the request is processed successfully)
	 * 2. message - <message value> (The message to be displayed for success)
	 * 3. data - <response data array> (The response data to be displayed for success)
	 * @param message
	 * @param data
	 */
	public static function generateSuccess( $message, $data = null ) {

		$response				= array();

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
	 * @param message
	 * @param errors
	 */
	public static function generateFailure( $message, $errors = null, $data = null ) {

		$response				= array();

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
	public static function generateErrorMessage( $model ) {

		$errors			= $model->getErrors();
		$modelErrors	= array();

		foreach ( $errors as $key => $value ) {

			$modelErrors[ $key ] = $value[ 0 ];
		}

		return $modelErrors;
	}
}

?>