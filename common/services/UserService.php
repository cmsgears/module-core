<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\User;

class UserService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findAll() {

		return User::find()->all();
	}

	public static function findById( $id ) {

		return User::findById( $id );
	}

	public static function findByAccessToken( $token ) {

		return User::findByAccessToken( $token );
	}

	public static function findByEmail( $email ) {

		return User::findByEmail( $email );
	}

	public static function isExistByEmail( $email ) {

		$user = User::findByEmail( $email );

		return isset( $user );
	}

	public static function isExistByUsername( $username ) {

		$user = User::findByUsername( $username );

		return isset( $user );
	}
}

?>