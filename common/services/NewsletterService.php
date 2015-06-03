<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\Newsletter;

/**
 * The class NewsletterService is base class to perform database activities for Newsletter Entity.
 */
class NewsletterService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return Newsletter
	 */
	public static function findById( $id ) {

		return Newsletter::findById( $id );
	}

	/**
	 * @return array - associative array having email as key and name as value
	 */
	public static function getMailingList( $conditions = [] ) {

		$conditions['newsletter'] 	= 1;
		$userTable					= CoreTables::TABLE_USER;
		$members 					= User::findByQuery( "select email, firstName, lastName from $userTable" )->where( $conditions )->all();
		$membersList				= [];

		foreach ( $members as $member ) {

			$membersList[ $member->email ]	= $member->getName();
		}

		return $membersList;
	}
}

?>