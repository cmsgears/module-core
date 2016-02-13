<?php
// Yii Imports
use \Yii;

// CMG Imports
use cmsgears\widgets\aform\AjaxForm;
?>

<div class="box-form box-form-regular">
	<span class="cmti cmti-edit btn-edit"></span>
	<div class="wrap-info">
		<?php
			if( isset( $fieldsMap ) && count( $fieldsMap ) > 0 ) {

				foreach ( $fieldsMap as $field ) {
		?>
				<label><?= $field->label ?></label>
				<label><?= $field->getFieldValue() ?></label>
		<?php 
				}
			}
			else {
		?>
		<p>No settings found.</p>
		<?php } ?>
	</div>
	<div class="wrap-form frm-split-40-60">
		<?php if( isset( $fieldsMap ) && count( $fieldsMap ) > 0 ) { ?>

		<?= AjaxForm::widget([ 
			'options' => [ 'class' => 'cmt-form', 'id' => "frm-setting-$type", 'cmt-clear' => 0 ],
			'slug' => "config-$type", 'showLabel' => true, 'model' => $model,
			'ajaxUrl' => "cmgcore/settings/update?type=$type",
			'cmtController' => 'settings', 'cmtAction' => 'update',
			'modelName' => "setting$type"
		])?>

		<?php } ?>
	</div>
</div>