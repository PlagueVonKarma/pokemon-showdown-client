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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.35368439210311076" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.22581339670460454" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.829016890143333" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.4672280076932558" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.46311614902632114" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.46680575170116523" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.47802740122701803"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.2312764184689271" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.6030075377975488">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.6996045198525664">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.9340434998058993">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.7567486305458193">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.17476427653028148"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.8184551282341614"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.38859946944310697"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.8668890516586243"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.7316976812578884"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.5126683588658978"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.940472219452916"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.2023717463933472"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.4399941804285843"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.9869745670724455"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.2682376842173517"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.9422281129316474"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.4251220046034252"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.7196561274363056"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.8475995120016628"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.22814766812547793"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.4741162787039801"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.5643247157858697"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.2255283373314647"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
