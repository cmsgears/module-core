<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Newsletter;

class NewsletterService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findById( $id ) {

		return Newsletter::findById( $id );
	}

	public static function getMailingList() {

		$userTable		= CoreTables::TABLE_USER;
		$members 		= User::findByQuery( "select email, firstName, lastName from $userTable" )->where( [ 'newsletter' => 1 ] )->all();
		$membersList	= [];

		foreach ( $members as $member ) {

			$membersList[ $member->email ]	= trim( $member->firstName . " " . $member->lastName );
		}

		return $membersList;
	}
}

?>