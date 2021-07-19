<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">   
<!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<meta name="msapplication-TileColor" content="#da532c">
<meta name="msapplication-TileImage" content="/mstile-144x144.png">
<meta name="theme-color" content="#ffffff">
<title><?php echo empty($og_title) ? "Cardition" : "$og_title"; ?></title>
<script type="text/javascript">
//    var globalURL = "https://www.cardition.com/";
//    var globalURL = "http://bcfolder.webintenerife.com/";
    var globalURL = "";
    timeOut = 1000;
</script>

<meta name="description" content="<?php echo empty($og_description) ? "" : "$og_description"; ?>"/>
<meta property="og:title" content="<?php echo empty($og_title) ? "" : "$og_title"; ?>" />
<meta property="og:url" content="<?php echo empty($og_url) ? "" : "$og_url"; ?>" />
<meta property="og:description" content="<?php echo empty($og_description) ? "" : "$og_description"; ?>">
<meta property="og:image" content="<?php echo empty($og_image_url) ? "" : "$og_image_url"; ?>">
<meta property="og:image:secure_url" content="<?php echo empty($og_image_url) ? "" : "$og_image_url"; ?>">
<meta property="og:image:type" content="<?php echo empty($og_image_type) ? "" : "$og_image_type"; ?>" />
<meta property="og:image:width" content="<?php echo empty($og_image_width) ? "" : "$og_image_width"; ?>">
<meta property="og:image:height" content="<?php echo empty($og_image_height) ? "" : "$og_image_height"; ?>">
<meta property="og:type" content="<?php echo empty($og_type) ? "" : "$og_type"; ?>">
<meta property="fb:app_id" content="159237671501505">