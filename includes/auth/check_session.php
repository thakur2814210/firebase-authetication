<?php

$timeout_duration = 3600;
if ( isset( $_SESSION[ "LAST_ACTIVITY" ] ) && ($time - $_SESSION[ "LAST_ACTIVITY" ]) > $timeout_duration )
{
	echo "0";
}
else
{
	echo "1";
}
	
