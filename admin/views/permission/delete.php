<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Permission';

// Sidebar and Return URL
$sidebar						= $this->context->sidebar;
$returnUrl						= $this->context->returnUrl;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
	<h1>Delete Permission</h1>
		<?php $form = ActiveForm::begin( ['id' => 'frm-permission-delete', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'name' )->textInput( [ 'readonly'=>'true' ] ) ?>
    	<?= $form->field( $model, 'description' )->textarea( [ 'readonly'=>'true' ] ) ?>

		<h4>Mapped Roles</h4>
		<?php 
			$permissionRoles	= $model->getRolesIdList();

			foreach ( $roles as $role ) { 

				if( in_array( $role['id'], $permissionRoles ) ) {
		?>		
					<span class="box-half"><input type="checkbox" name="roles" value="<?=$role['id']?>" checked readonly /><?=$role['name']?></span>
		<?php 
				}
				else {
		?>
					<span class="box-half"><input type="checkbox" name="roles" value="<?=$role['id']?>" readonly /><?=$role['name']?></span>
		<?php
				}
			}
		?>
		<div class="box-filler"></div>
		<?=Html::a( "Cancel", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>