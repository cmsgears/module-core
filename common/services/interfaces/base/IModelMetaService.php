<?php
namespace cmsgears\core\common\services\interfaces\base;

// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

use cmsgears\core\common\models\base\Meta;

interface IModelMetaService extends IEntityService {

    // Data Provider ------

    // Read ---------------

    // Read - Models ---

    // Read - Lists ----

    // Read - Maps -----

    public function getIdMetaMapByType( $model, $type );

    public function getNameMetaMapByType( $model, $type );

    // Create -------------

    // Update -------------

    public function updateModelMetas( $model, $metas );

    // Delete -------------
}
