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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.4251002113362987" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.4920663214177776" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.16735079697690547" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.9551914500348768" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.5047258582875755" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.919203474597525" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.6366907100223294"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.05263498650966514" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.8888359423977068">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.07898902650655248">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.0652702930522755">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.18058027826113343">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.20843208387099388"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.5854384371742389"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.062243662505168995"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.3829179965957554"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.7799088555656728"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.6424269684807218"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.1499268141336081"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.5815049515990838"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.1236219020100755"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.030674046200444227"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.18822397678843683"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.3688326373794013"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.3164965208879933"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.13153865130648024"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.7488612900998097"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.36601206831144406"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.8838146926254284"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.5659822444853269"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.002970391140563189"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
