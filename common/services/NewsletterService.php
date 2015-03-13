<?php
namespace cmsgears\modules\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\modules\core\common\models\entities\Newsletter;

class NewsletterService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	public static function findById( $id ) {

		return Newsletter::findOne( $id );
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