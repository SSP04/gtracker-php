<?php   
    
    /**
    * Forcing display errors.
    */
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);
    
    /**
    * Sets the library/controllers/views paths
    */
    set_include_path
    (
        get_include_path() . PATH_SEPARATOR . 
        realpath("../libraries/") . PATH_SEPARATOR
    );    
    
    /**
    * Requires the library files
    */
	require('Gt/Feeds.php');
	require('Gt/Exception.php');
	require('Gt/Player.php');
    require('Gt/Server.php');
    
    /**
    * Get information
	*
	* Please take note: if you send too much requests your host can be blocked
	* into GameTracker servers and they WILL suspend your GameTracker account, 
	* so, cache these results for at least 1 minute before executing this code
	* again.
	*
	* There are a lot of cache engines out there: 
	* 
	* Stash @ https://github.com/tedivm/Stash/
	* Zend_Cache @ http://framework.zend.com/
	*
    */
	$server = new Gt_Server('1234567'); // This is your ServerID (see serverid.png)
	if ($server->succeed())
	{
		ob_start();
		echo "Status: " . ($server->isAlive() ? "Online" : "Offline") . "\n";
		echo "Name: " . $server->getName() . "\n";
		echo "Players: " . $server->getPlayerCount() . "/" . $server->getMaxPlayers() . "\n";
		echo "Connection: " . $server->getIp() . ":" . $server->getPort() . "\n";
		echo "\n";
		echo "Game: " . $server->getGame() . "\n";	
		echo "Current Map: " . $server->getMap() . "\n";			
		echo "<img src=\"" . $server->getMapImage() . "\" />\n";
		echo "\n";
		echo "Online Players: \n";
		foreach($server->getPlayers() as $idx => $player)
		{
			echo $player->getName() . " (Online time: " . $player->getTime("{d} days, {h} hours, {m} minutes)") . "\n";
		}
		echo nl2br(ob_get_clean());
	}
	else
	{
		echo "Failed to retrieve server information: " . $server->getLastError();
    }
    