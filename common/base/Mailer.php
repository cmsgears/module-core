<?php
namespace cmsgears\core\common\base;

// Yii Imports
use \Yii;
use yii\base\Component;

// Mail
use \Swift_SmtpTransport;

// CMG Imports
use cmsgears\core\common\config\CoreProperties;
use cmsgears\core\common\config\MailProperties;

class Mailer extends Component {

	private $_mailer;

	private $_coreProperties;
	private $_mailProperties;

    public function init() {

        parent::init();

        $this->_mailer = Yii::$app->getMailer();

        $this->_mailer->htmlLayout 	= $this->htmlLayout;
        $this->_mailer->textLayout 	= $this->textLayout;
        $this->_mailer->viewPath 	= $this->viewPath;
    }

	public function initSmtp() {

		$transport		= new Swift_SmtpTransport();
		$mailProperties	= $this->_mailProperties;

		$transport->setHost( $mailProperties->getSmtpHost() );
		$transport->setPort( $mailProperties->getSmtpPort() );
		$transport->setUsername( $mailProperties->getSmtpUsername() );
		$transport->setPassword( $mailProperties->getSmtpPassword() );
		$transport->setEncryption( $mailProperties->getSmtpEncryption() );

		$this->_mailer->transport	= $transport;
	}

	public function getMailer() {

		return $this->_mailer;
	}

	public function getCoreProperties() {

		if( !isset( $this->_coreProperties ) ) {

			$this->_coreProperties	= CoreProperties::getInstance();
		}

		return $this->_coreProperties;
	}

	public function getMailProperties() {

		if( !isset( $this->_mailProperties ) ) {

			$this->_mailProperties	= MailProperties::getInstance();
		}

		if( $this->_mailProperties->isSmtp() ) {

			$this->initSmtp();
		}

		return $this->_mailProperties;
	}
}

?>