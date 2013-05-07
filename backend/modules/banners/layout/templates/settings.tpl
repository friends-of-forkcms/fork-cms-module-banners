{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

<div class="pageTitle">
	<h2>{$lblModuleSettings|ucfirst}: {$lblBanners}</h2>
</div>

{form:settings}
	<div class="box">
		<div class="heading">
			<h3>{$lblSizes|ucfirst}</h3>
		</div>
		<div class="options">
			{* Sizes *}
			<div class="datagridHolder" id="sizesDatagrid">
				<table class="datagrid" cellspacing="0" cellpadding="0" border="0">
					<thead>
						<tr>
							<th><span>{$lblName|ucfirst}</span></th>
							<th><span>{$lblWidth|ucfirst}</span></th>
							<th><span>{$lblHeight|ucfirst}</span></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody id="sizesTable">
						{iteration:sizes}
							<tr class="sizeRow" data-size-id="{$sizes.id}" {option:sizes.hidden}style="display: none;"{/option:sizes.hidden}>
								<td>{$sizes.txtSize} {$sizes.txtSizeError}</td>
								<td>{$sizes.txtSizeWidth} {$sizes.txtSizeWidthError}</td>
								<td>{$sizes.txtSizeHeight} {$sizes.txtSizeHeightError}</td>
								<td>
									{option:sizes.add}
										<a title="{$lblAdd|ucfirst}" class="button icon iconAdd iconOnly" id="addSize" href="#add-size">
											<span>{$lblAdd|ucfirst}</span>
										</a>
									{/option:sizes.add}
									{option:sizes.delete}
										<a title="{$lblDelete|ucfirst}" class="button icon iconDelete iconOnly deleteSize" href="#delete-size">
											<span>{$lblDelete|ucfirst}</span>
										</a>
									{/option:sizes.delete}
									{option:sizes.hasBanners}<span class="helpTxt">*</span>{/option:sizes.hasBanners}
								</td>
							</tr>
						{/iteration:sizes}
					</tbody>
				</table>
			</div>

			<p class="helpTxt">*: {$msgSizeHasBanners}</p>
		</div>
	</div>

	<div class="fullwidthOptions">
		<div class="buttonHolderRight">
			<input id="save" class="inputButton button mainButton" type="submit" name="save" value="{$lblSave|ucfirst}" />
		</div>
	</div>
{/form:settings}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}