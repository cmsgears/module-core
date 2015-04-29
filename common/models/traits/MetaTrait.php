<?php
namespace cmsgears\core\common\models\traits;

use cmsgears\core\common\models\entities\CoreTables;
use cmsgears\core\common\models\entities\ModelMeta;

trait MetaTrait {

	public function getMetas() {

		$parentType	= $this->metaType;

    	return $this->hasMany( ModelMeta::className(), [ 'parentId' => 'id' ] )
					->where( "parentType=$parentType" );
	}

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