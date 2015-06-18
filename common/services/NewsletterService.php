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

	// Data Provider ----

	/**
	 * @param array - yii conditions for where query
	 * @param array - custom query instead of model
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $conditions = [], $query = null ) {

		return self::getDataProvider( new Newsletter(), [ 'conditions' => $conditions, 'query' => $query ] );
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