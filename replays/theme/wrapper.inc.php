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
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/font-awesome.css?0.8487696047611564" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/panels.css?0.2872137247584108" />
	<link rel="stylesheet" href="//dragonheavenserver.herokuapp.com/theme/main.css?0.5561177872947667" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/battle.css?0.02960440530875097" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/replay.css?0.16106362684996678" />
	<link rel="stylesheet" href="//dragonheaven.herokuapp.com/style/utilichart.css?0.41457417819548725" />

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
				<li><a class="button nav-first<?php if ($panels->tab === 'home') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/?0.5062617969242769"><img src="//dragonheavenserver.herokuapp.com/images/pokemonshowdownbeta.png?0.225019195177945" alt="Pok&eacute;mon Showdown! (beta)" /> Home</a></li>
				<li><a class="button<?php if ($panels->tab === 'pokedex') echo ' cur'; ?>" href="//dex.pokemonshowdown.com/?0.7312307372652083">Pok&eacute;dex</a></li>
				<li><a class="button<?php if ($panels->tab === 'replay') echo ' cur'; ?>" href="/?0.4813034504729299">Replays</a></li>
				<li><a class="button<?php if ($panels->tab === 'ladder') echo ' cur'; ?>" href="//dragonheavenserver.herokuapp.com/ladder/?0.5396384345997196">Ladder</a></li>
				<li><a class="button nav-last" href="//dragonheavenserver.herokuapp.com/forums/?0.36231931197744816">Forum</a></li>
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
	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-1.11.0.min.js?0.42140635709149743"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/lodash.core.js?0.6488311966976037"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/backbone.js?0.26613206130032263"></script>
	<script src="//dex.pokemonshowdown.com/js/panels.js?0.8426072846879955"></script>
<?php
}

function ThemeFooterTemplate() {
	global $panels;
?>
<?php $panels->scripts(); ?>

	<script src="//dragonheaven.herokuapp.com/js/lib/jquery-cookie.js?0.6097897363638409"></script>
	<script src="//dragonheaven.herokuapp.com/js/lib/html-sanitizer-minified.js?0.057426961658386455"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-sound.js?0.43573677406787836"></script>
	<script src="//dragonheaven.herokuapp.com/config/config.js?0.5678594039653864"></script>
	<script src="//dragonheaven.herokuapp.com/js/battledata.js?0.08513902644448379"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini.js?0.44732415214644705"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex-mini-bw.js?0.13180628743113965"></script>
	<script src="//dragonheaven.herokuapp.com/data/graphics.js?0.4047743327150257"></script>
	<script src="//dragonheaven.herokuapp.com/data/pokedex.js?0.37994088576426766"></script>
	<script src="//dragonheaven.herokuapp.com/data/items.js?0.8984792087544275"></script>
	<script src="//dragonheaven.herokuapp.com/data/moves.js?0.39724265175851636"></script>
	<script src="//dragonheaven.herokuapp.com/data/abilities.js?0.3083262207751172"></script>
	<script src="//dragonheaven.herokuapp.com/data/teambuilder-tables.js?0.017656229368737675"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle-tooltips.js?0.08994881689010215"></script>
	<script src="//dragonheaven.herokuapp.com/js/battle.js?0.4758111733485175"></script>
	<script src="/js/replay.js?c81925c8"></script>

</body></html>
<?php
}
