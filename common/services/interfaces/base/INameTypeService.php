<?php
namespace cmsgears\core\common\services\interfaces\base;

interface INameTypeService extends IEntityService {

    // Data Provider ------

    public function getPageByType( $type, $config = [] );

    // Read ---------------

    // Read - Models ---

    public function getByName( $name, $first = false );

    public function getByType( $type, $first = false );

    public function getByNameType( $name, $type );

    public function searchByName( $name, $config = [] );

    public function searchByNameType( $name, $type, $config = [] );

    // Read - Lists ----

    public function getIdListByType( $type, $config = [] );

    public function getIdNameListByType( $type, $options = [] );

    // Read - Maps -----

    public function getIdNameMapByType( $type, $options = [] );

    // Create -------------

    // Update -------------

    // Delete -------------

}
