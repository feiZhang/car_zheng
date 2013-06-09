<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/** 
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 *
 * See http://code.google.com/p/minify/wiki/CustomSource for other ideas
 **/

return array(
	'headerCss' => array(
		$groupBasePath."/Public/public/bootstrap/css/bootstrap.css",
		$groupBasePath."/Public/public/bootstrap/css/bootstrap-responsive.css",
		$groupBasePath."/Public/project/css/default.css",
	),
	'headerJs' => array(
		$groupBasePath."/Public/public/Jquery/jquery-1.8.2.js",
		$groupBasePath."/Public/public/bootstrap/js/bootstrap.js",
		$groupBasePath."/Public/basic/js/selectselectselect.js"
	),
	'footerCss'	=> array(
		$groupBasePath."/Public/public/explain_prompt/example.css",
		$groupBasePath."/Public/basic/js/explain_prompt/explain.imprompt.css",
		$groupBasePath."/Public/public/artDialog5/skins/default.css",
	),
	'footerJs'	=> array(
		$groupBasePath."/Public/public/artDialog5/source/jquery.artDialog.js",
		$groupBasePath."/Public/public/artDialog5/source/artDialog.plugins.js",
		$groupBasePath."/Public/public/explain_prompt/jquery-impromptu.4.0.js",
		$groupBasePath."/Public/basic/js/explain_prompt/explain.impromptu.js",
		$groupBasePath."/Public/public/jquery-imgpreview.js",
		$groupBasePath."/Public/basic/js/DxShowMessage.js",
		$groupBasePath."/Public/basic/js/dxFunction.js",
	),
);