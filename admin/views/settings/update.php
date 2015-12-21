<?php
// Yii Imports
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

// CMG Imports
use cmsgears\core\common\utilities\FormUtil;
?>
<?php include 'header.php'; ?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Update <?= ucfirst( $type ) ?> Settings</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-update-settings', 'options' => ['class' => 'frm-split' ] ] ); ?>

		<?= FormUtil::getFieldsHtml( $form, $model ); ?>

		<div class="box-filler"></div>
		<?=Html::a( "Back", [ "index?type=$type" ], ['class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>
</section>