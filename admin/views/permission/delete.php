<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Delete Permission | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-permission', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row">
						<div class="col col2">
							<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'slug' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= IconChooser::widget( [ 'model' => $model, 'disabled' => true, 'options' => [ 'class' => 'icon-picker-wrap' ] ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'group', [ 'disabled' => true ], 'cmti cmti-checkbox' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php if( $model->group ) { ?>
			<div class="box box-crud">
				<div class="box-header">
					<div class="box-header-title">Grouped <?= ucfirst( $type ) ?> Permissions</div>
				</div>
				<div class="box-content">
					<div class="box-content">
						<div class="row padding padding-small-v">
						<?php
							$children = $model->getChildrenIdList();

							foreach( $permissions as $permission ) {
						?>
								<div class="col col4">
						<?php
								if( in_array( $permission[ 'id' ], $children ) ) {
						?>
									<input type="checkbox" name="Children[binded][]" value="<?= $permission[ 'id' ] ?>" checked disabled /><?= $permission[ 'name' ] ?>
						<?php
								}
								else {
						?>
									<input type="checkbox" name="Children[binded][]" value="<?= $permission[ 'id' ] ?>" disabled /><?= $permission[ 'name' ] ?>
						<?php
								}
						?>
								</div>
						<?php
							}
						?>
						</div>
					</div>
				</div>
			</div>
			<?php if( count( $spermissions ) > 0 ) { ?>
				<div class="box box-crud">
					<div class="box-header">
						<div class="box-header-title">Grouped System Permissions</div>
					</div>
					<div class="box-content">
						<div class="box-content">
							<div class="row padding padding-small-v">
							<?php
								$children = $model->getChildrenIdList();

								foreach( $spermissions as $permission ) {
							?>
									<div class="col col4">
							<?php
									if( in_array( $permission[ 'id' ], $children ) ) {
							?>
										<input type="checkbox" name="Children[binded][]" value="<?= $permission[ 'id' ] ?>" checked disabled /><?= $permission[ 'name' ] ?>
							<?php
									}
									else {
							?>
										<input type="checkbox" name="Children[binded][]" value="<?= $permission[ 'id' ] ?>" disabled /><?= $permission[ 'name' ] ?>
							<?php
									}
							?>
									</div>
							<?php
								}
							?>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
		<?php if( count( $roles ) > 0 ) { ?>
			<div class="filler-height filler-height-medium"></div>
			<div class="box box-crud">
				<div class="box-header">
					<div class="box-header-title">Assigned Roles</div>
				</div>
				<div class="box-content">
					<div class="box-content">
						<div class="row padding padding-small-v">
						<?php
							$modelRoles	= $model->getRolesIdList();

							foreach( $roles as $role ) {
						?>
								<div class="col col4">
						<?php
								if( in_array( $role[ 'id' ], $modelRoles ) ) {
						?>
									<input type="checkbox" name="Binder[binded][]" value="<?= $role[ 'id' ] ?>" checked disabled /><?= $role[ 'name' ] ?>
						<?php
								}
								else {
						?>
									<input type="checkbox" name="Binder[binded][]" value="<?= $role[ 'id' ] ?>" disabled /><?= $role[ 'name' ] ?>
						<?php
								}
						?>
								</div>
						<?php
							}
						?>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Delete" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
