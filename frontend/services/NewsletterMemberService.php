<?php
namespace cmsgears\core\frontend\services;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\models\entities\NewsletterMember;

class NewsletterMemberService extends \cmsgears\core\common\services\NewsletterMemberService {

	// Static Methods ----------------------------------------------

	// Create -----------

	/**
	 * @param Newsletter $newsletter
	 * @return Newsletter
	 */
	public static function signUp( $newsletter ) {

		$member	= NewsletterMember::findByEmail( $newsletter->email );

		// Create Newsletter Member
		if( !isset( $member ) ) {

			$member	= new NewsletterMember();

			$member->email 	= $newsletter->email;
			$member->name 	= $newsletter->name;
			$member->active = true;
			$member->global	= $newsletter->global;

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
}

?>