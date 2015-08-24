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
	 * @param array $config to generate query
	 * @return ActiveDataProvider
	 */
	public static function getPagination( $config = [] ) {

		return self::getDataProvider( new NewsletterMember(), $config );
	}

	// Create -----------

	/**
	 * @param Newsletter $newsletter
	 * @return Newsletter
	 */
	public static function create( $email, $name ) {

		$member	= NewsletterMember::findByEmail( $email );

		// Create Newsletter Member
		if( !isset( $member ) ) {

			$member	= new NewsletterMember();

			$member->email 	= $email;
			$member->name 	= $name;
			$member->active = true;

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
	public static function update( $email, $name, $active ) {
		
		$member	= NewsletterMember::findByEmail( $email );
		
		if( isset( $member ) ) {

			$member->name	= $name;
			$member->active	= $active;

			$member->update();
		}
		else if( $active ) {
			
			self::create( $email, $name );
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