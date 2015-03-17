<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\Newsletter;

class NewsletterService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findById( $id ) {

		return Newsletter::findById( $id );
	}

	public static function getMembersEmailList() {

		$members 		= User::findNewsletterMembers();
		$membersList	= [];

		foreach ( $members as $member ) {

			$membersList[ $member->getEmail() ]	= $member->getName();
		}

		return $membersList; 
	}
}

?>