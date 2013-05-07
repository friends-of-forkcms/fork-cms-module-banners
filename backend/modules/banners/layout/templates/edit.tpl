{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

{form:edit}
	{option:isOnlyMemberOfAGroup}
		<div class="generalMessage infoMessage content">
			<span>{$msgIsOnlyMemberOfAGroup}</span>
		</div>
	{/option:isOnlyMemberOfAGroup}
	<div class="box">
		<div class="heading">
			<h3>{$lblEditBanner|ucfirst}</h3>
		</div>
		<div class="options horizontal">
			<p>
				<label for="name">{$lblTitle|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtName} {$txtNameError}
			</p>
			<p>
				<label for="url">{$lblURL|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$txtUrl} {$txtUrlError}
				<span class="helpTxt">
					{$msgTrackerUrlHelp}<br />
					{$lblTrackerUrl|ucfirst}: <span>{$trackerUrl}<span id="generatedUrl">{$url}</span></span>
				</span>
			</p>
			<p>
				<label>{$lblSize|ucfirst}</label>
				<select disabled="disabled">
					<option value="{$standard.name} - {$standard.width}x{$standard.height}">{$standard.name} - {$standard.width}x{$standard.height}</option>
				</select>
			</p>
			<p>
				<label for="file">{$lblFile|ucfirst}</label>
				{$fileFile}
				{$fileFileError}
			</p>
		</div>
	</div>
	<div class="box">
		<div class="options imageWrapper">
			<p>
				<img src="/frontend/files/banners/{option:isSWF}original{/option:isSWF}{option:!isSWF}resized{/option:!isSWF}/{$item.id}_{$item.file}" alt="">
			</p>
		</div>
	</div>
	<div class="box">
		<div class="heading">
			<h3>{$lblDate|ucfirst}</h3>
		</div>
		<div class="options">
			<div class="oneLiner">
				<p class="firstChild">
					<label for="startDate">{$lblStartDate|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
					{$txtStartDate}
				</p>
				<p>
					<label for="startTime">{$lblAt}</label>
					{$txtStartTime}
				</p>
				{$txtStartDateError} {$txtStartTimeError}
			</div>
			<div class="oneLiner">
				<p class="firstChild">
					<label for="endDate">{$lblEndDate|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
					{$txtEndDate}
				</p>
				<p>
					<label for="endTime">{$lblAt}</label>
					{$txtEndTime}
				</p>
				{$txtEndDateError} {$txtEndTimeError}
			</div>
		</div>
		<div class="options">
			<ul class="inputList pb0">
				<li class="pb0">{$chkShowPermanently} <label for="showPermanently">{$lblShowPermanently|ucfirst}</label></li>
			</ul>
		</div>
	</div>

		{option:groups}
			<div class="box">
				<div class="heading">
					<h3>{$lblGroups|ucfirst}</h3>
				</div>
				<div class="options content">
					<p>{$lblIsMemberOf|ucfirst}:</p>
					{iteration:groups}
						<p><a href="{$var|geturl:'edit_group'}&id={$groups.id}">{$groups.name}</a></p>
					{/iteration:groups}
				</div>
			</div>
		{/option:groups}

	<div class="fullwidthOptions">
		{option:showBannersDelete}
		<a href="{$var|geturl:'delete'}&amp;id={$item.id}" data-message-id="confirmDelete" class="askConfirmation button linkButton icon iconDelete">
			<span>{$lblDelete|ucfirst}</span>
		</a>
		<div id="confirmDelete" title="{$lblDelete|ucfirst}?" style="display: none;">
			<p>
				{$msgConfirmDeleteBanner|sprintf:{$item.name}}
			</p>
		</div>
		{/option:showBannersDelete}

		<div class="buttonHolderRight">
			<input id="editButton" class="inputButton button mainButton" type="submit" name="editBanner" value="{$lblSave|ucfirst}" />
		</div>
	</div>
{/form:edit}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}