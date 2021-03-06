<!-- BEGIN: MAIN -->
<style type="text/css">
    input[type="text"]{ width: 98% }
</style>

{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

<div class="block">
	<form action="{EDIT_FORM_ACTION_URL}" method="post" name="newcountry">
		<h2>{PHP.L.ls_cities} ({COUNTRY_NAME}, {REGION_NAME}) </h2>
		<table class="cells">
			<tr>
				<td class="coltop width60">{PHP.L.Title}</td>
                <td class="coltop"></td>
                <td class="coltop">{PHP.L.Order}</td>
				<td class="coltop width20">{PHP.L.Action}</td>
			</tr>
			<!-- BEGIN: ROWS -->
			<tr>
				<td>{CITY_ROW_NAME} </td>
                <td>{CITY_ROW_REGION}</td>
                <td>{CITY_ROW_SORT}</td>
				<td>
					<a title="{PHP.L.Delete}" href="{CITY_ROW_DEL_URL}" class="confirmLink negative button"><span class="trash icon"></span>{PHP.L.Delete}</a>
				</td>
			</tr>
			<!-- END: ROWS -->
			<!-- BEGIN: NOROWS -->
			<tr>
				<td class="centerall" colspan="3">{PHP.L.ls_nocountries}</td>
			</tr>
			<!-- END: NOROWS -->	
		</table>
		<div class="action_bar valid">
			<p class="paging">{PAGENAV_PAGES} </p>
			<input type="submit" class="submit" value="{PHP.L.Update}" />
		</div>	
	</form>
</div>

<!-- BEGIN: ADDFORM -->
<div class="block">
	<h3>{PHP.L.rec_addcity}</h3>
	<form action="{ADD_FORM_ACTION_URL}" method="post" name="newregion">
		<table class="cells">			
			<tr>
				<td class="width20">{PHP.L.rec_newcity_list}: </td>
				<td class="width80">{ADD_FORM_NAME}
					<div class="small">{PHP.L.rec_newcity_newstr}</div>
				</td>
			</tr>
		</table>
		<div class="action_bar valid">
			<input type="submit" class="submit" value="{PHP.L.Add}" />
		</div>
	</form>
</div>
<!-- END: ADDFORM -->

<!-- END: MAIN -->