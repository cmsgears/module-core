<?php
namespace cmsgears\modules\core\common\utilities;

// Yii Imports
use \Yii;

/**
 * The class AjaxUtil format the success and failure message for Ajax requests.
 */
class AjaxUtil {

	// Static Methods ----------------------------------------------

	public static function generateSuccess( $message, $data = null ) {

		$response				= array();

		$response["result"]		= 1;
		$response["message"]	= $message;
		$response["data"]		= $data;

		Yii::$app->response->format = 'json';

		echo json_encode( $response );

		die();
	}

	public static function generateFailure( $message, $errors = null ) {

		$response				= array();

		$response["result"]		= 0;
		$response["message"]	= $message;
		$response["errors"]		= $errors;

		echo json_encode( $response );

		die();
	}
	
	public static function generateErrorMessage( $model ) {

		$errors 		= $model->getErrors();
		$modelErrors	= array();

		foreach ( $errors as $key => $value ) {

			$modelErrors[ $key ] = $value[ 0 ];
		}

		return $modelErrors;
	}
}

?>