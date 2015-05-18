<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\ModelMeta;

/**
 * MetaTrait can be used to add meta feature to relevant models. It allows us to make existing tables extandable without adding additional columns.
 * The model must define the member variable $metaType which is unique for the model.
 */
trait MetaTrait {

	/**
	 * @return array - ModelMeta associated with parent
	 */
	public function getMetas() {

		$parentType	= $this->metaType;

    	return $this->hasMany( ModelMeta::className(), [ 'parentId' => 'id' ] )
					->where( "parentType='$parentType'" );
	}

	/**
	 * @return array - map of meta name and value
	 */
	public function getMetasNameValueMap() {

		$metas 		= $this->metas;
		$metasMap	= array();

		foreach ( $metas as $meta ) {

			$metasMap[ $meta->name ] = $meta->value;
		}

		return $metasMap;
	}	
}

?>