<?php
namespace cmsgears\core\common\models\traits\resources;

// Yii Import
use \Yii;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;

trait DataTrait {

	public function generateJsonFromObject( $dataObject ) {

		$data		= json_encode( $dataObject );
		$this->data	= $data;
	}

	public function generateObjectFromJson( $assoc = false ) {

		$obj 	= json_decode( $this->data, $assoc );

		return (object)$obj;
	}

	public function setDataAttribute( $name, $value, $assoc = false ) {

		$object	= $this->generateObjectFromJson( $assoc );

		$object->$name	= $value;

		$this->generateJsonFromObject( $object );
	}

	public function getDataAttribute( $name, $assoc = false ) {

		$object	= $this->generateObjectFromJson( $assoc );

		if( isset( $object->$name ) ) {

			return $object->$name;
		}

		return null;
	}

	public function updateDataAttribute( $name, $value, $assoc = false ) {

		$this->setDataAttribute( $name, $value, $assoc );

		$this->update();
	}

	public function removeDataAttribute( $name, $assoc = false ) {

		$object	= $this->generateObjectFromJson( $assoc );

		unset( $object->$name );

		$this->generateJsonFromObject( $object );
	}
}

?>