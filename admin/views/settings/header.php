<?php
use yii\helpers\Html; 

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Settings';
?>
<div class="grid-header clearfix">
	<ul class="settings-tab-wrapper">
		<li class="btn core"><?= Html::a( "General", ["/cmgcore/settings/index?type=core"], ['class'=>''] )?></li> 
		<li class="btn email"><?= Html::a( "Email", ["/cmgcore/settings/index?type=email"], ['class'=>''] )?></li>
		<li class="btn website"><?= Html::a( "Website", ["/cmgcore/settings/index?type=website"], ['class'=>''] )?></li>
		<li class="btn admin"><?= Html::a( "Admin", ["/cmgcore/settings/index?type=admin"], ['class'=>''] )?></li>
		<li class="btn payment"><?= Html::a( "Payments", ["/cmgcore/settings/index?type=payment"], ['class'=>''] )?></li>
		<li class="btn shipping"><?= Html::a( "Shipping", ["/cmgcore/settings/index?type=shipping"], ['class'=>''] )?></li>
	</ul>
</div>