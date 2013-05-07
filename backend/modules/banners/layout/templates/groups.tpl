{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblBanners|ucfirst}: {$lblGroups}</h2>

	{option:showBannersAddGroup}
	<div class="buttonHolderRight">
		<a href="{$var|geturl:'add_group'}" class="button icon iconAdd" title="{$lblAddGroup|ucfirst}">
			<span>{$lblAddGroup|ucfirst}</span>
		</a>
	</div>
	{/option:showBannersAddGroup}
</div>

{option:dgGroups}
	<div class="datagridHolder">
		{$dgGroups}
	</div>
{/option:dgGroups}

{option:!dgGroups}<p>{$msgNoGroups|sprintf:{$var|geturl:'add_group'}}</p>{/option:!dgGroups}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}