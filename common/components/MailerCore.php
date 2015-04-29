<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;
use yii\base\Component;

/**
 * The mail component used for sending possible mails by the CMSGears core module. It must be initialised 
 * for app using the name cmgCoreMailer. It's used by various controllers to trigger mails.  
 */
class MailerCore extends Component {

	// Various mail views used by the component
	const MAIL_ACCOUNT_CREATE	= "account-create";	
	const MAIL_ACCOUNT_ACTIVATE	= "account-activate";
	const MAIL_REG				= "register";
	const MAIL_REG_CONFIRM		= "register-confirm";
	const MAIL_PASSWORD_RESET	= "password-reset";

    public $htmlLayout 	= '@cmsgears/module-core/common/mails/layouts/html';
    public $textLayout 	= '@cmsgears/module-core/common/mails/layouts/text';
    public $viewPath 	= '@cmsgears/module-core/common/mails/views';

	private $mailer;

	/**
	 * Initialise the CMG Core Mailer.
	 */
    public function init() {

        parent::init();

        $this->mailer = Yii::$app->getMailer();

        $this->mailer->htmlLayout 	= $this->htmlLayout;
        $this->mailer->textLayout 	= $this->textLayout;
        $this->mailer->viewPath 	= $this->viewPath;
    }
	
	/**
	 * @return core mailer
	 */
	public function getMailer() {

		return $this->mailer;
	}

	/**
	 * The method sends mail for accounts created by site admin.
	 */
	public function sendCreateUserMail( $coreProperties, $mailProperties, $user ) {

		$fromEmail 	= $mailProperties->getSenderEmail();
		$fromName 	= $mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_ACCOUNT_CREATE, [ 'coreProperties' => $coreProperties, 'user' => $user ] )
            ->setTo( $user->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Registration | " . $coreProperties->getSiteName() )
            //->setTextBody( "heroor" )
            ->send();
	}

	/**
	 * The method sends mail for accounts created by admin and activated by the users from website.
	 */
	public function sendActivateUserMail( $coreProperties, $mailProperties, $user ) {

		$fromEmail 	= $mailProperties->getSenderEmail();
		$fromName 	= $mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_ACCOUNT_ACTIVATE, [ 'coreProperties' => $coreProperties, 'user' => $user ] )
            ->setTo( $user->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Registration | " . $coreProperties->getSiteName() )
            //->setTextBody( $contact->contact_message )
            ->send();
	}

	/**
	 * The method sends mail for accounts registered from website.
	 */
	public function sendRegisterMail( $coreProperties, $mailProperties, $user ) {

		$fromEmail 	= $mailProperties->getSenderEmail();
		$fromName 	= $mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_REG, [ 'coreProperties' => $coreProperties, 'user' => $user ] )
            ->setTo( $user->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Registration | " . $coreProperties->getSiteName() )
            //->setTextBody( $contact->contact_message )
            ->send();
	}

	/**
	 * The method sends mail for accounts verified by users from website.
	 */
	public function sendVerifyUserMail( $coreProperties, $mailProperties, $user ) {

		$fromEmail 	= $mailProperties->getSenderEmail();
		$fromName 	= $mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_REG_CONFIRM, [ 'coreProperties' => $coreProperties, 'user' => $user ] )
            ->setTo( $user->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Registration | " . $coreProperties->getSiteName() )
            //->setTextBody( $contact->contact_message )
            ->send();
	}

	/**
	 * The method sends mail for password reset request by users from website.
	 */
	public function sendPasswordResetMail( $coreProperties, $mailProperties, $user ) {

		$fromEmail 	= $mailProperties->getSenderEmail();
		$fromName 	= $mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( self::MAIL_PASSWORD_RESET, [ 'coreProperties' => $coreProperties, 'user' => $user ] )
            ->setTo( $user->email )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Password Reset | " . $coreProperties->getSiteName() )
            //->setTextBody( "heroor" )
            ->send();
	}
}

?>