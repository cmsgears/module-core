<?php
namespace cmsgears\core\common\models\traits\resources;

trait DataTrait {

	public function generateJsonFromObject( $dataObject ) {

		$data		= json_encode( $dataObject );
		$this->data	= $data;
	}

	public function generateObjectFromJson( $assoc = false ) {

		$obj	= json_decode( $this->data, $assoc );

		return (object)$obj;
	}

	public function setDataMeta( $name, $value, $assoc = false ) {

		$object	= $this->generateObjectFromJson( $assoc );

		$object->$name	= $value;

		$this->generateJsonFromObject( $object );
	}

	public function getDataMeta( $name, $assoc = false ) {

		$object	= $this->generateObjectFromJson( $assoc );

		if( isset( $object->$name ) ) {

			return $object->$name;
		}

		return null;
	}

	public function updateDataMeta( $name, $value, $assoc = false ) {

		$this->setDataMeta( $name, $value, $assoc );

		// Save model meta state
		$this->update();
	}

	public function removeDataMeta( $name, $assoc = false ) {

		$object	= $this->generateObjectFromJson( $assoc );

		unset( $object->$name );

		$this->generateJsonFromObject( $object );

		// Save model meta state
		$this->update();
	}
}
