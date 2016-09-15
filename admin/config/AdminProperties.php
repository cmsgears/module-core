<?php
namespace cmsgears\core\admin\config;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\config\CmgProperties;

class AdminProperties extends CmgProperties {

    // Variables ---------------------------------------------------

    // Global -----------------

    const PROP_THEME			= 'theme';

    // Public -----------------

    // Protected --------------

    // Private ----------------

    private static $instance;

    // Constructor and Initialisation ------------------------------

    // Instance methods --------------------------------------------

    // Yii parent classes --------------------

    // CMG parent classes --------------------

    // AdminProperties -----------------------

    // Singleton

    /**
     * Return Singleton instance.
     */
    public static function getInstance() {

        if( !isset( self::$instance ) ) {

            self::$instance	= new AdminProperties();

            self::$instance->init( CoreGlobal::CONFIG_ADMIN );
        }

        return self::$instance;
    }

    // Properties

    public function getTheme() {

        return $this->properties[ self::PROP_THEME ];
    }
}
