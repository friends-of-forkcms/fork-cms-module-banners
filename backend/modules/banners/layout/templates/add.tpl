{include:{$BACKEND_CORE_PATH}/layout/templates/head.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/structure_start_module.tpl}

{form:add}
	<div class="box">
		<div class="heading">
			<h3>{$lblAddBanner|ucfirst}</h3>
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
					{$lblTrackerUrl|ucfirst}: <span>{$trackerUrl}<span id="generatedUrl"></span></span>
				</span>
			</p>
			<p>
				<label for="size">{$lblSize|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$ddmSize} {$ddmSizeError}
			</p>
			<p>
				<label for="file">{$lblFile|ucfirst}<abbr title="{$lblRequiredField}">*</abbr></label>
				{$fileFile}
				{$fileFileError}
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
				<li class="">{$chkShowPermanently} <label for="showPermanently">{$lblShowPermanently|ucfirst}</label></li>
			</ul>
		</div>
	</div>

	<div class="fullwidthOptions">
		<div class="buttonHolderRight">
			<input id="addButton" class="inputButton button mainButton" type="submit" name="addCategory" value="{$lblAddBanner|ucfirst}" />
		</div>
	</div>
{/form:add}

{include:{$BACKEND_CORE_PATH}/layout/templates/structure_end_module.tpl}
{include:{$BACKEND_CORE_PATH}/layout/templates/footer.tpl}