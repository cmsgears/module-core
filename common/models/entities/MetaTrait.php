<?php
namespace cmsgears\core\common\models\entities;

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