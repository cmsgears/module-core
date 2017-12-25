<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Site | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
$app		= "site";
$controller = "members";
$action		= "member";
$actionUrl	= "core/sites/member/member?siteId=". $siteId ;
?>
<div class="box-crud-wrap row">
	<div class="box-crud-wrap-main colf colf3x2">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-site', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Member Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row row-inline">
						<div class="col col2" cmt-app="" cmt-controller="" cmt-action=""  >
							<div class="mapper mapper-auto mapper-auto-categories">
								<div class="auto-fill auto-fill-basic">
									
									<div class="auto-fill-source" cmt-app="<?= $app ?>" cmt-controller="<?= $controller ?>" cmt-action="<?= $action ?>" action="<?= $actionUrl ?>" cmt-keep cmt-custom>
										<div class="relative">
											<div class="auto-fill-search clearfix">
												<div class="frm-icon-element icon-right">
													<span class="icon cmti cmti-search"></span>
													<input class="cmt-key-up auto-fill-text search-name" type="text" name="name" placeholder="user name" autocomplete="off" />
												</div>
											</div>
											<div class="auto-fill-items-wrap">
												<ul class="auto-fill-items vnav"></ul>
											</div>
										</div>
									</div>
									<div class="filler-height"></div>
									<div class="site-member hidden-easy" >
										<label> Username </label>
										<input id="username" type="text" name="username" readonly>
										<?= $form->field( $model, 'siteId' )->hiddenInput( ['value'=> $siteId])->label( false )  ?>
										<?= $form->field( $model, 'roleId' )->dropDownList( $roleMap, [ 'class' => 'cmt-select' ] ) ?>
										<?= $form->field( $model, 'userId' )->hiddenInput()->label( false )  ?>
										<span class=""></span>
									</div>
									<div class="filler-height"></div>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="filler-height filler-height-medium"></div>

		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Create" />
		</div>

		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
	<div class="box-crud-wrap-sidebar colf colf3">

	</div>
</div>
