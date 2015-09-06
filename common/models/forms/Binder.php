<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;

class Binder extends \yii\base\Model {

	public $binderId;
	public $bindedData;
	
	// yii\base\Model

	public function rules() {

        return [
            [ [ 'binderId', 'bindedData' ], 'required' ],
            [ 'binderId', 'compare', 'compareValue' => 0, 'operator' => '>' ]
        ];
    }
}

?>