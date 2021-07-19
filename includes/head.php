<?php
ob_start();
//<p>Developed by <a href="http://www.webintenerife.com">webintenerife.com</a>
function getTimestamp()
{
	date_default_timezone_set( 'Europe/London' );
	return date( "YmdTHis" ) . substr( ( string ) microtime(), 1, 8 );
}
function force_reload( $filename )
{
	$now = getTimestamp();
	$new_filename = $filename . '?version=' . $now;
	return $new_filename;
}
require_once('head_with_variables.php');
require_once 'ChromePhp.php';
?>

<!--following line to prevent Safari from increasing text size but lets the user pinch to zoom
see this link: http://www.456bereastreet.com/archive/201012/controlling_text_size_in_safari_for_ios_without_disabling_user_zoom/-->
<!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />-->
<!--<link rel="stylesheet" type="text/css" href="assets/sat_css/bootstrap.min.css" />-->
<link rel="stylesheet" type="text/css" href="assets/remote/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="assets/sat_css/jquery.mCustomScrollbar.css" />
<link rel="stylesheet" type="text/css" href="<?php echo force_reload( 'assets/sat_css/style.css' ) ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo force_reload( 'assets/mg_css/mg.css' ) ?>" />
<link rel="stylesheet" type="text/css" href="assets/mimiz-jquery-loader/jquery.loader.css">
<link rel="stylesheet" type="text/css" href="assets/colpick/css/colpick.css">
<link rel="stylesheet" type="text/css" href="assets/css/jquery.fancybox.css">
<!--<link rel="stylesheet" type="text/css" href="assets/css/magnific-popup.css">-->
<!--<link rel="stylesheet" type="text/css" href="assets/css/featherlight.css">-->
<link rel="stylesheet" type="text/css" href="assets/remote/codemirror.min.css" />
<link rel="stylesheet" type="text/css" href="assets/remote/monokai.min.css" />
<link rel="stylesheet" type="text/css" href="assets/context/src/jquery.contextMenu.css" />
<!--<link rel="stylesheet" type="text/css" href="assets/summernote/summernote.css" />-->
<link rel="stylesheet" type="text/css" href="assets/mg_css/mgintexted.css" />
<link rel="stylesheet" type="text/css" href="assets/mg_css/spectrum.css" />
<link rel="stylesheet" type="text/css" href="assets/css/sweetalert.css" rel="stylesheet" type="text/css">
<link href="assets/css/star-rating.css" media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/themes/base/jquery-ui.css"/>


<!--<link rel="stylesheet" href="<?php // echo auto_version('assets/css/bcfolder.css');  ?>" type="text/css" />-->
<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="/manifest.json">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<!--<script type="text/javascript" src="assets/sat_js/jquery-1.11.3.min.js"></script>-->
<script type="text/javascript" src="assets/js/jquery-2.2.3.min.js"></script>
<script>
	(function (i, s, o, g, r, a, m) {
		i['GoogleAnalyticsObject'] = r;
		i[r] = i[r] || function () {
			(i[r].q = i[r].q || []).push(arguments)
		}, i[r].l = 1 * new Date();
		a = s.createElement(o),
						m = s.getElementsByTagName(o)[0];
		a.async = 1;
		a.src = g;
		m.parentNode.insertBefore(a, m)
	})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
	ga('create', 'UA-57697833-1', 'auto');
	ga('send', 'pageview');
	var trackOutboundLink = function (url) {
		ga('send', 'event', 'outbound', 'click', url, {'hitCallback':
							function () {
								document.location = url;
							}
		});
	}
</script>
</head>
