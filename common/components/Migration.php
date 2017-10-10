<?php
/**
 * @link https://www.cmsgears.org/
 * @copyright Copyright (c) 2015 VulpineCode Technologies Pvt. Ltd.
 * @license https://www.cmsgears.org/license/
 * @package module
 * @subpackage core
 */
namespace cmsgears\core\common\components;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The migration component for CMSGears based sites.
 *
 * It defines the default prefix for CMSGears and Application specific database tables.
 *
 * @author Bhagwat Singh Chouhan <bhagwat.chouhan@gmail.com>
 * @since 1.0.0
 */
class Migration extends \yii\base\Component {

	// Variables ---------------------------------------------------

	// Global -----------------

	// Public -----------------

	// Table prefix
	public $cmgPrefix		= 'cmg_'; // Used for CMSGears tables
	public $appPrefix		= 'app_'; // Used for app tables

	public $fk				= true;

	public $tableOptions	= null;

	public $siteName		= 'CMSGears';
	public $siteTitle		= 'CMSGears Demo';

	public $primaryDomain	= 'cmsgears.org';

	public $defaultSite		= 'http://www.cmsgears.org';
	public $defaultAdmin	= 'http://www.cmsgears.org/admin/';

	public $uploadsDir		= null;
	public $uploadsUrl		= 'http://www.cmsgears.org/uploads/';

	public $testAccounts	= true;

	public $siteSlug		= CoreGlobal::SITE_MAIN;
	public $siteMaster		= 'demomaster';
	public $siteContact		= 'democontact';
	public $siteInfo		= 'demoinfo';

	// Timezone examples: Asia/Kolkata, Asia/Vladivostok, Asia/Bangkok, America/Toronto, America/Chicago, America/Los_Angeles, Europe/London, Australia/Sydney
	public $timezone		= 'UTC';

	// Protected --------------

	// Private ----------------

	// Constructor and Initialisation ------------------------------

	// Instance methods --------------------------------------------

	// Yii interfaces ------------------------

	// Yii parent classes --------------------

	// CMG interfaces ------------------------

	// CMG parent classes --------------------

	// Migration -----------------------------

	public function isFk() {

		return $this->fk;
	}

	public function getTableOptions() {

		return $this->tableOptions;
	}

	public function getSiteName() {

		return $this->siteName;
	}

	public function getSiteTitle() {

		return $this->siteTitle;
	}

	public function getPrimaryDomain() {

		return $this->primaryDomain;
	}

	public function getDefaultSite() {

		return $this->defaultSite;
	}

	public function getDefaultAdmin() {

		return $this->defaultAdmin;
	}

	public function getUploadsDir() {

		return $this->uploadsDir;
	}

	public function getUploadsUrl() {

		return $this->uploadsUrl;
	}

	public function isTestAccounts() {

		return $this->testAccounts;
	}

	public function getSiteSlug() {

		return $this->siteSlug;
	}

	public function getSiteMaster() {

		return $this->siteMaster;
	}

	public function getSiteContact() {

		return $this->siteContact;
	}

	public function getSiteInfo() {

		return $this->siteInfo;
	}

	public function getTimezone() {

		return $this->timezone;
	}
}
