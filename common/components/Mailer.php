<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;

/**
 * The mail component used for sending possible mails by the CMSGears core module. It must be initialised 
 * for app using the name cmgCoreMailer. It's used by various controllers to trigger mails.  
 */
class Mailer extends \cmsgears\core\common\base\Mailer {

	const MAIL_ACCOUNT_CREATE				= "account-create";	
	const MAIL_ACCOUNT_ACTIVATE				= "account-activate";
	const MAIL_REG							= "register";
	const MAIL_REG_CONFIRM					= "register-confirm";
	const MAIL_PASSWORD_RESET				= "password-reset";

    public $htmlLayout 			= '@cmsgears/module-core/common/mails/layouts/html';
    public $textLayout 			= '@cmsgears/module-core/common/mails/layouts/text';
    public $viewPath 			= '@cmsgears/module-core/common/mails/views';

	/**
	 * The method sends mail for accounts created by site admin.
	 */
	public function sendCreateUserMail( $user ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_ACCOUNT_CREATE, [ 'coreProperties' => $this->coreProperties, 'user' => $user ] )
            ->setTo( $user->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Registration | " . $this->coreProperties->getSiteName() )
            //->setTextBody( "heroor" )
            ->send();
	}

	/**
	 * The method sends mail for accounts created by admin and activated by the users from website.
	 */
	public function sendActivateUserMail( $user ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_ACCOUNT_ACTIVATE, [ 'coreProperties' => $this->coreProperties, 'user' => $user ] )
            ->setTo( $user->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Registration | " . $this->coreProperties->getSiteName() )
            //->setTextBody( $contact->contact_message )
            ->send();
	}

	/**
	 * The method sends mail for accounts registered from website.
	 */
	public function sendRegisterMail( $user ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_REG, [ 'coreProperties' => $this->coreProperties, 'user' => $user ] )
            ->setTo( $user->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Registration | " . $this->coreProperties->getSiteName() )
            //->setTextBody( $contact->contact_message )
            ->send();
	}

	/**
	 * The method sends mail for accounts verified by users from website.
	 */
	public function sendVerifyUserMail( $user ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_REG_CONFIRM, [ 'coreProperties' => $this->coreProperties, 'user' => $user ] )
            ->setTo( $user->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Registration | " . $this->coreProperties->getSiteName() )
            //->setTextBody( $contact->contact_message )
            ->send();
	}

	/**
	 * The method sends mail for password reset request by users from website.
	 */
	public function sendPasswordResetMail( $user ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_PASSWORD_RESET, [ 'coreProperties' => $this->coreProperties, 'user' => $user ] )
            ->setTo( $user->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Password Reset | " . $this->coreProperties->getSiteName() )
            //->setTextBody( "heroor" )
            ->send();
	}
}

?>