<?php
namespace cmsgears\core\common\models\traits;

trait DataTrait {

	public function generateJsonFromObject( $dataObject ) {

		$data		= json_encode( $dataObject );
		$this->data	= $data;
	}

	public function generateObjectFromJson( $assoc = false ) {

		$obj 	= json_decode( $this->data, $assoc );

		return (object)$obj;
	}

	public function setDataAttribute( $name, $value ) {

		$object	= $this->generateObjectFromJson( $assoc = false );

		$object->$name	= $value;

		$this->generateJsonFromObject( $object );
	}

	public function getDataAttribute( $name ) {

		$object	= $this->generateObjectFromJson( $assoc = false );

		if( isset( $object->$name ) ) {

			return $object->$name;
		}
		
		return null;
	}

	public function updateDataAttribute( $name, $value ) {

		$this->setDataAttribute( $name, $value );

		$this->update();
	}

	public function removeDataAttribute( $name ) {

		$object	= $this->generateObjectFromJson( $assoc = false );

		unset( $object->$name );

		$this->generateJsonFromObject( $object );
	}
}

?>