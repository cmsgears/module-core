<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\components;

/**
 * The mail component used to send mails by Core Module.
 *
 * @since 1.0.0
 */
class Mailer extends \cmsgears\core\common\base\Mailer {

	// Variables ---------------------------------------------------

	// Globals ----------------

	// Account mails
	const MAIL_ACCOUNT_CREATE	= 'account/create';
	const MAIL_ACCOUNT_ACTIVATE	= 'account/activate';

	const MAIL_REG				= 'register/request';
	const MAIL_REG_CONFIRM		= 'register/confirm';

	const MAIL_PASSWORD_RESET	= 'password/reset';
	const MAIL_PASSWORD_CHANGE	= 'password/change';

	// Comment mails
	const MAIL_COMMENT_FEEDBACK		= 'comment/submit/feedback';
	const MAIL_COMMENT_TESTIMONIAL	= 'comment/submit/testimonial';

	const MAIL_COMMENT_SPAM_REQUEST		= 'comment/request/spam';
	const MAIL_COMMENT_DELETE_REQUEST	= 'comment/request/delete';

	// Status mails - Approval Process
	const MAIL_APPROVE     = 'status/approve';
	const MAIL_REJECT      = 'status/reject';
	const MAIL_BLOCK       = 'status/block';
	const MAIL_FROZEN      = 'status/frozen';
	const MAIL_ACTIVATE    = 'status/activate';
	const MAIL_A_REQUEST   = 'status/activation-request';

	// Public -----------------

	public $htmlLayout	= '@cmsgears/module-core/common/mails/layouts/html';
	public $textLayout	= '@cmsgears/module-core/common/mails/layouts/text';
	public $viewPath	= '@cmsgears/module-core/common/mails/views';

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

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
	 * The method sends mail for accounts created by admin and activated by the users either from the admin or website.
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
	 * The method sends mail for accounts confirmed by users from the website.
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
	 * The method sends mail for feedback by users from website.
	 */
	public function sendFeedbackMail( $comment ) {

		$siteName		= $this->coreProperties->getSiteName();
		$fromEmail		= $this->mailProperties->getSenderEmail();
		$fromName		= $this->mailProperties->getSenderName();
		$contactEmail	= $this->mailProperties->getContactEmail();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_COMMENT_FEEDBACK, [ 'coreProperties' => $this->coreProperties, 'comment' => $comment ] )
			->setTo( $contactEmail )
			->setFrom( [ $fromEmail => "$fromName | $siteName" ] )
			->setSubject( "Feedback received | " . $this->coreProperties->getSiteName() )
			//->setTextBody( "heroor" )
			->send();
	}

	/**
	 * The method sends mail for feedback by users from website.
	 */
	public function sendTestimonialMail( $comment ) {

		$siteName		= $this->coreProperties->getSiteName();
		$fromEmail		= $this->mailProperties->getSenderEmail();
		$fromName		= $this->mailProperties->getSenderName();
		$contactEmail	= $this->mailProperties->getContactEmail();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_COMMENT_TESTIMONIAL, [ 'coreProperties' => $this->coreProperties, 'comment' => $comment ] )
			->setTo( $contactEmail )
			->setFrom( [ $fromEmail => "$fromName | $siteName" ] )
			->setSubject( "Testimonial received | " . $this->coreProperties->getSiteName() )
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

	// == Status Mails ========

	public function sendApproveMail( $model, $email ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_APPROVE, [ 'coreProperties' => $this->coreProperties, 'email' => $email, 'model' => $model ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Approved $model->name | " . $this->coreProperties->getSiteName() )
			->send();
	}

	public function sendRejectMail( $model, $email, $message ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_REJECT, [ 'coreProperties' => $this->coreProperties, 'email' => $email, 'model' => $model, 'message' => $message ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Rejected $model->name | " . $this->coreProperties->getSiteName() )
			->send();
	}

	public function sendBlockMail( $model, $email, $message ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_BLOCK, [ 'coreProperties' => $this->coreProperties, 'email' => $email, 'model' => $model, 'message' => $message ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Blocked $model->name | " . $this->coreProperties->getSiteName() )
			->send();
	}

	public function sendFreezeMail( $model, $email, $message ) {

		$fromEmail  = $this->mailProperties->getSenderEmail();
		$fromName   = $this->mailProperties->getSenderName();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_FROZEN, [ 'coreProperties' => $this->coreProperties, 'email' => $email, 'model' => $model, 'message' => $message ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Frozen $model->name | " . $this->coreProperties->getSiteName() )
			->send();
	}

	public function sendActivateMail( $model, $email ) {

		$fromEmail 	= $this->mailProperties->getSenderEmail();
		$fromName 	= $this->mailProperties->getSenderName();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_ACTIVATE, [ 'coreProperties' => $this->coreProperties, 'email' => $email, 'model' => $model ] )
			->setTo( $email )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Activated $model->name | " . $this->coreProperties->getSiteName() )
			->send();
	}

	public function sendActivationRequestMail( $model, $url ) {

		$fromEmail  = $this->mailProperties->getSenderEmail();
		$fromName   = $this->mailProperties->getSenderName();
		$toEmail    = $this->mailProperties->getContactEmail();

		// Send Mail
		$this->getMailer()->compose( self::MAIL_A_REQUEST, [ 'coreProperties' => $this->coreProperties, 'email' => $email, 'model' => $model, 'url' => $url ] )
			->setTo( $toEmail )
			->setFrom( [ $fromEmail => $fromName ] )
			->setSubject( "Activation Request - $model->name | " . $this->coreProperties->getSiteName() )
			->send();
	}

}
