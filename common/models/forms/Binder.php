<?php
namespace cmsgears\core\common\models\forms;

// Yii Imports
use \Yii;

class Binder extends \yii\base\Model {

	public $binderId; // Binder to which binded data need to be binded

	public $allData 	= []; // All data submitted by user

	public $bindedData 	= []; // Data to be active

	// yii\base\Model

	public function rules() {

        return [
            [ [ 'binderId' ], 'required' ],
            [ [ 'allData', 'bindedData' ], 'safe' ],
            [ 'binderId', 'compare', 'compareValue' => 0, 'operator' => '>' ]
        ];
    }
}

?>