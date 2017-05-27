<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The mail component used for sending possible mails by the CMSGears core module. It must be initialised
 * for app using the name coreMailer. It's used by various controllers to trigger mails.
 */
class Mailer extends \cmsgears\core\common\base\Mailer {

	// Variables ---------------------------------------------------

	// Globals ----------------

	const MAIL_ACCOUNT_CREATE				= "account/create";
	const MAIL_ACCOUNT_ACTIVATE				= "account/activate";
	const MAIL_REG							= "register";
	const MAIL_REG_CONFIRM					= "register-confirm";
	const MAIL_PASSWORD_RESET				= "password-reset";
	const MAIL_PASSWORD_CHANGE				= 'password-change';
	const MAIL_COMMENT_SPAM_REQUEST			= 'comment/spam-request';
	const MAIL_COMMENT_DELETE_REQUEST		= 'comment/delete-request';

	// Public -----------------

	public $htmlLayout			= '@cmsgears/module-core/common/mails/layouts/html';
	public $textLayout			= '@cmsgears/module-core/common/mails/layouts/text';
	public $viewPath			= '@cmsgears/module-core/common/mails/views';

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii parent classes --------------------

	// CMG parent classes --------------------

	// Mailer --------------------------------

	/**
	 * The method sends mail for accounts created by site admin.
	 */
	public function sendCreateUserMail( $user ) {

		$siteName	= $this->coreProperties->getSiteName();
		$fromEmail	= $this->mailProperties->getSenderEmail();
		$fromName	= $this->mailProperties->getSenderName();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_ACCOUNT_CREATE, [ 'coreProperties' => $this->coreProperties, 'user' => $user ] )
			->setTo( $user->email )
			->setFrom( [ $fromEmail => "$fromName | $siteName" ] )
			->setSubject( "Registration | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "heroor" )
			->send();
	}

	/**
	 * The method sends mail for accounts created by admin and activated by the users from website.
	 */
	public function sendActivateUserMail( $user ) {

		$siteName	= $this->coreProperties->getSiteName();
		$fromEmail	= $this->mailProperties->getSenderEmail();
		$fromName	= $this->mailProperties->getSenderName();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_ACCOUNT_ACTIVATE, [ 'coreProperties' => $this->coreProperties, 'user' => $user ] )
			->setTo( $user->email )
			->setFrom( [ $fromEmail => "$fromName | $siteName" ] )
			->setSubject( "Registration | " . $this->coreProperties->getSiteName() )
			//->setTextBody( $contact->contact_message )
			->send();
	}

	/**
	 * The method sends mail for accounts registered from website.
	 */
	public function sendRegisterMail( $user ) {

		$siteName	= $this->coreProperties->getSiteName();
		$fromEmail	= $this->mailProperties->getSenderEmail();
		$fromName	= $this->mailProperties->getSenderName();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_REG, [ 'coreProperties' => $this->coreProperties, 'user' => $user ] )
			->setTo( $user->email )
			->setFrom( [ $fromEmail => "$fromName | $siteName" ] )
			->setSubject( "Registration | " . $this->coreProperties->getSiteName() )
			//->setTextBody( $contact->contact_message )
			->send();
	}

	/**
	 * The method sends mail for accounts verified by users from website.
	 */
	public function sendVerifyUserMail( $user ) {

		$siteName	= $this->coreProperties->getSiteName();
		$fromEmail	= $this->mailProperties->getSenderEmail();
		$fromName	= $this->mailProperties->getSenderName();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_REG_CONFIRM, [ 'coreProperties' => $this->coreProperties, 'user' => $user ] )
			->setTo( $user->email )
			->setFrom( [ $fromEmail => "$fromName | $siteName" ] )
			->setSubject( "Registration | " . $this->coreProperties->getSiteName() )
			//->setTextBody( $contact->contact_message )
			->send();
	}

	/**
	 * The method sends mail for password reset request by users from website.
	 */
	public function sendPasswordResetMail( $user ) {

		$siteName	= $this->coreProperties->getSiteName();
		$fromEmail	= $this->mailProperties->getSenderEmail();
		$fromName	= $this->mailProperties->getSenderName();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_PASSWORD_RESET, [ 'coreProperties' => $this->coreProperties, 'user' => $user ] )
			->setTo( $user->email )
			->setFrom( [ $fromEmail => "$fromName | $siteName" ] )
			->setSubject( "Password Reset | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "heroor" )
			->send();
	}

	/**
	 * The method sends mail for password change request by users from website.
	 */
	public function sendPasswordChangeMail( $user ) {

		$siteName	= $this->coreProperties->getSiteName();
		$fromEmail	= $this->mailProperties->getSenderEmail();
		$fromName	= $this->mailProperties->getSenderName();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_PASSWORD_CHANGE, [ 'coreProperties' => $this->coreProperties, 'user' => $user ] )
			->setTo( $user->email )
			->setFrom( [ $fromEmail => "$fromName | $siteName" ] )
			->setSubject( "Password Change | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "heroor" )
			->send();
	}

	/**
	 * The method sends mail for spam comment request by users from website.
	 */
	public function sendCommentSpamRequestMail( $comment, $updatePath ) {

		$siteName		= $this->coreProperties->getSiteName();
		$fromEmail		= $this->mailProperties->getSenderEmail();
		$fromName		= $this->mailProperties->getSenderName();
		$contactEmail	= $this->mailProperties->getContactEmail();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_COMMENT_SPAM_REQUEST, [ 'coreProperties' => $this->coreProperties, 'comment' => $comment, 'updatePath' => $updatePath ] )
			->setTo( $contactEmail )
			->setFrom( [ $fromEmail => "$fromName | $siteName" ] )
			->setSubject( "Spam Request | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "heroor" )
			->send();
	}

	/**
	 * The method sends mail for spam comment request by users from website.
	 */
	public function sendCommentDeleteRequestMail( $comment, $updatePath ) {

		$siteName		= $this->coreProperties->getSiteName();
		$fromEmail		= $this->mailProperties->getSenderEmail();
		$fromName		= $this->mailProperties->getSenderName();
		$contactEmail	= $this->mailProperties->getContactEmail();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_COMMENT_DELETE_REQUEST, [ 'coreProperties' => $this->coreProperties, 'comment' => $comment, 'updatePath' => $updatePath ] )
			->setTo( $contactEmail )
			->setFrom( [ $fromEmail => "$fromName | $siteName" ] )
			->setSubject( "Delete Request | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "heroor" )
			->send();
	}
}
