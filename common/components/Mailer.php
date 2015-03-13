<?php
namespace cmsgears\modules\core\common\components;

// Yii Imports
use \Yii;
use yii\base\Component;

// CMG Imports
use cmsgears\modules\core\common\config\CoreGlobal;

/**
 * The mail component for CMSGears core module. It must be initialised for app using the name cmgCoreMailer. 
 */
class Mailer extends Component {

    public $htmlLayout 	= '@cmsgears/modules/core/common/mails/layouts/html';
    public $textLayout 	= '@cmsgears/modules/core/common/mails/layouts/text';
    public $viewPath 	= '@cmsgears/modules/core/common/mails/views';

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
	
	public function sendRegisterMail( $coreProperties, $mailProperties, $user ) {

		$fromEmail 	= $mailProperties->getSenderEmail();
		$fromName 	= $mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( CoreGlobal::MAIL_REG, [ 'coreProperties' => $coreProperties, 'user' => $user ] )
            ->setTo( $user->getEmail() )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Registration | " . $coreProperties->getSiteName() )
            //->setTextBody( $contact->contact_message )
            ->send();
	}
	
	public function sendCreateUserMail( $coreProperties, $mailProperties, $user ) {

		$fromEmail 	= $mailProperties->getSenderEmail();
		$fromName 	= $mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( CoreGlobal::MAIL_REG_ADMIN, [ 'coreProperties' => $coreProperties, 'user' => $user ] )
            ->setTo( $user->getEmail() )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Registration | " . $coreProperties->getSiteName() )
            //->setTextBody( "heroor" )
            ->send();
	}
	
	public function sendActivateUserMail( $coreProperties, $mailProperties, $user ) {

		$fromEmail 	= $mailProperties->getSenderEmail();
		$fromName 	= $mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( CoreGlobal::MAIL_REG_CONFIRM, [ 'coreProperties' => $coreProperties, 'user' => $user ] )
            ->setTo( $user->getEmail() )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Registration | " . $coreProperties->getSiteName() )
            //->setTextBody( $contact->contact_message )
            ->send();
	}
	
	public function sendVerifyUserMail( $coreProperties, $mailProperties, $user ) {

		$fromEmail 	= $mailProperties->getSenderEmail();
		$fromName 	= $mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( CoreGlobal::MAIL_REG_CONFIRM, [ 'coreProperties' => $coreProperties, 'user' => $user ] )
            ->setTo( $user->getEmail() )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Registration | " . $coreProperties->getSiteName() )
            //->setTextBody( $contact->contact_message )
            ->send();
	}

	public function sendForgotPasswordMail( $coreProperties, $mailProperties, $user ) {

		$fromEmail 	= $mailProperties->getSenderEmail();
		$fromName 	= $mailProperties->getSenderName();

		// Send Mail
        $this->getMailer()->compose( CoreGlobal::MAIL_FORGOT_PASSWORD, [ 'coreProperties' => $coreProperties, 'user' => $user ] )
            ->setTo( $user->getEmail() )
            ->setFrom( [ $fromEmail => $fromName ] )
            ->setSubject( "Password Reset | " . $coreProperties->getSiteName() )
            //->setTextBody( "heroor" )
            ->send();
	}
}

?>