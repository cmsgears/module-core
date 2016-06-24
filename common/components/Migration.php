<?php
namespace cmsgears\core\common\components;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

/**
 * The migration component for CMSGears based sites.
 */
class Migration extends \yii\base\Component {

    public $fk				= true;

    public $tableOptions	= null;

	public $siteName		= 'CMSGears';
	public $siteTitle		= 'CMSGears Demo';

	public $primaryDomain	= 'cmsgears.org';

	public $defaultSite		= 'http://www.cmsgears.org';
	public $defaultAdmin	= 'http://www.cmsgears.org/admin/';

	public $uploadsDir		= null;
	public $uploadsUrl		= 'http://localhost/test/uploads/';

	public $siteMaster		= 'demomaster';

    /**
     * Initialise the Component.
     */
    public function init() {

        parent::init();
    }

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

    public function getSiteMaster() {

        return $this->siteMaster;
    }
}

?>