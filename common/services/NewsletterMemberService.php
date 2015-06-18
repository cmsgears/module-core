<?php
namespace cmsgears\core\common\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\User;
use cmsgears\core\common\models\entities\NewsletterMember;

/**
 * The class NewsletterMemberService is base class to perform database activities for NewsletterMember Entity.
 */
class NewsletterMemberService extends Service {

	// Static Methods ----------------------------------------------

	// Read ----------------

	/**
	 * @param integer $id
	 * @return NewsletterMember
	 */
	public static function findById( $id ) {

		return NewsletterMember::findById( $id );
	}

	/**
	 * @param integer $email
	 * @return NewsletterMember
	 */
	public static function findByEmail( $email ) {

		return NewsletterMember::findByEmail( $email );
	}

	// Data Provider ----

	/**
	 * @param array - yii conditions for where query
	 * @param array - custom query instead of model
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $conditions = [], $query = null ) {

		return self::getDataProvider( new NewsletterMember(), [ 'conditions' => $conditions, 'query' => $query ] );
	}

	// Create -----------

	/**
	 * @param Newsletter $newsletter
	 * @return Newsletter
	 */
	public static function create( $email ) {

		$member	= NewsletterMember::findByEmail( $email );

		// Create Newsletter Member
		if( !isset( $member ) ) {

			$member	= new NewsletterMember();

			$member->email = $email;

			$member->save();
		}

		// Return NewsletterMember
		return $member;
	}
	
	// update -----------

	/**
	 * @param string $email
	 * @param boolean $newsletter
	 */
	public static function update( $email, $newsletter ) {

		if( $newsletter ) {

			self::create( $email );
		}
		else {
			
			self::delete( $email );
		}
	}

	// Delete -----------

	/**
	 * @param string $email
	 * @return boolean
	 */
	public static function delete( $email ) {

		// Find existing NewsletterMember
		$member	= NewsletterMember::findByEmail( $email );

		// Delete Newsletter Member
		if( isset( $member ) ) {

			$member->delete();
		}

		return true;
	}
}

?>