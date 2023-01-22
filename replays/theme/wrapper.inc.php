<?php

if ((substr($_SERVER['REMOTE_ADDR'],0,11) === '69.164.163.') ||
		(substr(@$_SERVER['HTTP_X_FORWARDED_FOR'],0,11) === '69.164.163.')) {
	die('website disabled');
}

/********************************************************************
 * Header
 ********************************************************************/

function ThemeHeaderTemplate() {
	global $panels;
?>
<!DOCTYPE html>
<html><head>

	<meta charset="utf-8" />

	<title><?php if ($panels->pagetitle) echo htmlspecialchars($panels->pagetitle).' - '; ?>Pok&eacute;mon Showdown</title>

<?php if ($panels->pagedescription) { ?>
	<meta name="description" content="<?php echo htmlspecialchars($panels->pagedescription); ?>" />
<?php } ?>

	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=IE8" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.7826856977766563" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.07952019369000651" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.9476471164309015" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.41376704684580123" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.019747450241580422" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.9842427480433249" />

	<!-- Workarounds for IE bugs to display trees correctly. -->
	<!--[if lte IE 6]><style> li.tree { height: 1px; } </style><![endif]-->
	<!--[if IE 7]><style> li.tree { zoom: 1; } </style><![endif]-->

	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-26211653-1']);
		_gaq.push(['_setDomainName', 'pokemonshowdown.com']);
		_gaq.push(['_setAllowLinker', true]);
		_gaq.push(['_trackPageview']);

		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
</head><body>

	<div class="pfx-topbar">
		<div class="header">
			<ul class="nav">
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.2565699514663049"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.2132555902034201" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.8388044394134986">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.6628920182687559">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.02026277508302976">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.5562293692380564">Forum</a></li>
			</ul>
			<ul class="nav nav-play">
				<li><a class="button greenbutton nav-first nav-last" href="http://play.pokemonshowdown.com/">Play</a></li>
			</ul>
			<div style="clear:both"></div>
		</div>
	</div>
<?php
}

/********************************************************************
 * Footer
 ********************************************************************/

function ThemeScriptsTemplate() {
?>
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.28230402016136713"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.7841017314251022"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.7790096139408103"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.6412465181191755"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.23809878513870975"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.7102474184034393"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.21495160373451316"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.8961185699982972"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.5597968535178524"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.7045073353155897"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.3747207396552341"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.9689610096293435"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.1135275042326378"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.21916805069281775"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.8070278072592285"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.5350918166687384"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.10726551576131627"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.06135816696658991"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.4450742477502214"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
