<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Role | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-role', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'name' ) ?>
						</div>
						<!--<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'group', [ 'class' => 'cmt-checkbox cmt-choice cmt-field-group', 'group-target' => 'group-role' ] ) ?>
						</div>-->
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'homeUrl' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'adminUrl' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap' ] ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea() ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if( count( $permissions ) > 0 ) { ?>
			<div class="filler-height filler-height-medium"></div>
			<div class="box box-crud">
				<div class="box-header">
					<div class="box-header-title">Assign <?= ucfirst( $type ) ?> Permissions</div>
				</div>
				<div class="box-content">
					<div class="box-content">
						<div class="row rp-map padding padding-small-v">
						<?php foreach( $permissions as $permission ) { ?>
							<div class="col col4">
								<input type="checkbox" name="Binder[binded][]" value="<?= $permission[ 'id' ] ?>" />
								<?= $permission[ 'name' ] ?>
							</div>
						<?php } ?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if( count( $spermissions ) > 0 ) { ?>
			<div class="filler-height filler-height-medium"></div>
			<div class="box box-crud">
				<div class="box-header">
					<div class="box-header-title">Assign System Permissions</div>
				</div>
				<div class="box-content">
					<div class="box-content">
						<div class="row rp-map padding padding-small-v">
						<?php foreach ( $spermissions as $permission ) { ?>
							<div class="col col4">
								<input type="checkbox" name="Binder[binded][]" value="<?= $permission[ 'id' ] ?>" />
								<?= $permission[ 'name' ] ?>
							</div>
						<?php } ?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Create" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
