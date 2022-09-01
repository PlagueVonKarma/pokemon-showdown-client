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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.25492892246721377" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.964252077399208" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.5249102348368766" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.16615502265750437" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.7271076546936293" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.6497870939540642" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.5856660053067575"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.8356271862830289" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.6709284688191361">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.21324411441545754">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.5100678353435188">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.6465483985385607">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.6319631966175101"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.9580317666076805"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.9740670536804841"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.7201252823138296"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.8109825321498185"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.9027760703370582"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.4997013022305119"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.5166568889759859"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.9816536907494067"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.290980244731329"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.3151180602650152"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.9580824298660888"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.2364514588393254"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.4934724232164698"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.28362823011387106"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.08296872859229354"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.05978504703773613"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.8851171698850504"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.7150246327850538"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
