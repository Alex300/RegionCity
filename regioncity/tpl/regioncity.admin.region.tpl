<!-- BEGIN: MAIN -->
<style type="text/css">
    input[type="text"]{ width: 98% }
</style>

{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

<div class="block">	
	<h2>{PHP.L.rec_regions} ({COUNTRY_NAME}) </h2>
		<form action="{EDIT_FORM_ACTION_URL}" method="post" name="newcountry" enctype="multipart/form-data">
	<table class="cells">
		<tr>
			<td class="coltop width60">{PHP.L.Title}</td>
			<td class="coltop width50">{PHP.L.Action}</td>
		</tr>
		<!-- BEGIN: ROWS -->
		<tr>
			<td>{REGION_ROW_NAME}</td>
			<td>
				<a href="{REGION_ROW_URL}" class="special button"><span class="view icon"></span>{PHP.L.Open}</a>
				<a href="{REGION_ROW_DEL_URL}" class="confirmLink negative button"><span class="trash icon"></span>{PHP.L.Delete}</a>
			</td>
		</tr>
		<!-- END: ROWS -->
		<!-- BEGIN: NOROWS -->
		<tr>
			<td class="centerall" colspan="3">{PHP.L.ls_noregions}</td>
		</tr>
		<!-- END: NOROWS -->
	</table>	
	<div class="action_bar valid">
		<div class="paging">{PAGENAV_PAGES} </div>
		<input type="submit" class="submit" value="{PHP.L.Update}" />
	</div>
	</form>
</div>	

<!-- BEGIN: ADDFORM -->
<div class="block">
	<h3>{PHP.L.ls_addregion}</h3>
	<form action="{ADD_FORM_ACTION_URL}" method="post" name="newregion" enctype="multipart/form-data">
		<table class="cells">	
			<tr>
				<td class="coltop width60">{PHP.L.Title}</td>
				<td class="coltop width50">{PHP.L.Action}</td>
			</tr>
			<tr>
				<td>{ADD_FORM_NAME}</td>
				<td>
                    <div class="action_bar valid">
                        <input type="submit" class="submit" value="{PHP.L.Add}" />
                    </div>
                </td>
			</tr>
		</table>

	</form>
</div>
<!-- END: ADDFORM -->

<!-- END: MAIN -->