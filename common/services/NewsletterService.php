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

	// Create -----------

	/**
	 * @param Newsletter $newsletter
	 * @return Newsletter
	 */
	public static function create( $newsletter ) {

		// Set Attributes
		$user					= Yii::$app->user->getIdentity();
		$newsletter->createdBy	= $user->id;

		// Create Newsletter
		$newsletter->save();

		// Return Newsletter
		return $newsletter;
	}

	// Update -----------

	/**
	 * @param Newsletter $newsletter
	 * @return Newsletter
	 */
	public static function update( $newsletter ) {

		// Find existing Newsletter
		$nlToUpdate	= self::findById( $newsletter->id );

		// Copy and set Attributes	
		$user					= Yii::$app->user->getIdentity();
		$nlToUpdate->modifiedBy	= $user->id;

		$nlToUpdate->copyForUpdateFrom( $newsletter, [ 'name', 'description', 'content' ] );
		
		// Update Newsletter
		$nlToUpdate->update();
		
		// Return updated Newsletter
		return $nlToUpdate;
	}

	// Delete -----------
	
	/**
	 * @param Newsletter $newsletter
	 * @return boolean
	 */
	public static function delete( $newsletter ) {

		// Find existing Newsletter
		$nlToDelete	= self::findById( $newsletter->id );

		// Delete Newsletter
		$nlToDelete->delete();

		return true;
	}
}

?>