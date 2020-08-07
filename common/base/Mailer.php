<?php
/**
 * This file is part of CMSGears Framework. Please view License file distributed
 * with the source code for license details.
 *
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 */

namespace cmsgears\core\common\base;

// Yii Imports
use Yii;

// Mail
use Swift_SmtpTransport;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;
use cmsgears\core\common\config\MailProperties;

class Mailer extends \yii\base\Component {

	// Variables ---------------------------------------------------

	// Globals -------------------------------

	// Constants --------------

	// Public -----------------

	// Protected --------------

	// Variables -----------------------------

	// Public -----------------

	// Protected --------------

	// Private ----------------

	private $mailer;

	private $coreProperties;
	private $mailProperties;

	// Traits ------------------------------------------------------

	// Constructor and Initialisation ------------------------------

	public function init() {

		parent::init();

		$this->mailer = Yii::$app->getMailer();

		$this->mailer->htmlLayout	= $this->htmlLayout;
		$this->mailer->textLayout	= $this->textLayout;
		$this->mailer->viewPath		= $this->viewPath;
	}

	public function initSmtp() {

		$transport		= new Swift_SmtpTransport();
		$mailProperties	= $this->mailProperties;

		$transport->setHost( $mailProperties->getSmtpHost() );
		$transport->setPort( $mailProperties->getSmtpPort() );
		$transport->setUsername( $mailProperties->getSmtpUsername() );
		$transport->setPassword( $mailProperties->getSmtpPassword() );
		$transport->setEncryption( $mailProperties->getSmtpEncryption() );

		$this->mailer->transport = $transport;
	}

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Mailer --------------------------------

	public function getMailer() {

		return $this->mailer;
	}

	public function getCoreProperties() {

		if( !isset( $this->coreProperties ) ) {

			$this->coreProperties = CoreProperties::getInstance();
		}

		return $this->coreProperties;
	}

	public function getMailProperties() {

		if( !isset( $this->mailProperties ) ) {

			$this->mailProperties = MailProperties::getInstance();
		}

		if( $this->mailProperties->isSmtp() ) {

			$this->initSmtp();
		}

		return $this->mailProperties;
	}

}
