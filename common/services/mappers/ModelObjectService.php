<?php
namespace cmsgears\core\common\services\mappers;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\CoreTables;
use cmsgears\core\common\models\entities\ObjectData;
use cmsgears\core\common\models\mappers\ModelObject;

use cmsgears\core\common\services\interfaces\entities\IObjectService;
use cmsgears\core\common\services\interfaces\mappers\IModelObjectService;

use cmsgears\core\common\services\traits\MapperTrait;

/**
 * The class ModelObjectService is base class to perform database activities for ModelObject Entity.
 */
class ModelObjectService extends \cmsgears\core\common\services\base\EntityService implements IModelObjectService {

    // Variables ---------------------------------------------------

    // Globals -------------------------------

    // Constants --------------

    // Public -----------------

    public static $modelClass	= '\cmsgears\core\common\models\mappers\ModelObject';

    public static $modelTable	= CoreTables::TABLE_MODEL_OBJECT;

    public static $parentType	= null;

    // Protected --------------

    // Variables -----------------------------

    // Public -----------------

    // Protected --------------

    // Private ----------------

    private $objectService;

    // Traits ------------------------------------------------------

    use MapperTrait;

    // Constructor and Initialisation ------------------------------

    public function __construct( IObjectService $objectService, $config = [] ) {

        $this->objectService	= $objectService;

        parent::__construct( $config );
    }

    // Instance methods --------------------------------------------

    // Yii parent classes --------------------

    // yii\base\Component -----

    // CMG interfaces ------------------------

    // CMG parent classes --------------------

    // ModelObjectService --------------------

    // Data Provider ------

    // Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

    // Read - Others ---

    // Create -------------

    // Update -------------

    // Delete -------------

    // Static Methods ----------------------------------------------

    // CMG parent classes --------------------

    // ModelObjectService --------------------

    // Data Provider ------

    // Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

    // Read - Others ---

    // Create -------------

    // Update -------------

    // Delete -------------
}
