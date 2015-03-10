<!-- BEGIN: MAIN -->
<style type="text/css">
    a.thumbicons img{
        display: inline-block;
        vertical-align: middle;
        float: none;
    }
</style>

{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

<div class="block">
	<h2>{PHP.L.rec_countries}</h2>
	<!-- BEGIN: ROWS -->
	<a href="{COUNTRY_ROW_URL}" class="thumbicons">
		{COUNTRY_ROW_FLAG}
		{COUNTRY_ROW_NAME}
	</a>
	<!-- END: ROWS -->
	<!-- BEGIN: NOROWS -->
	<p>{PHP.L.ls_nocountries}</p>
	<!-- END: NOROWS -->
	<div class="clear height0"></div>
</div>
<!-- END: MAIN -->