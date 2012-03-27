<?php

/**
* GameTracker-PHP
* 
* Copyright (C) 2012 João Francisco Biondo Trinca
* 
* Permission is hereby granted, free of charge, to any person 
* obtaining a copy of this software and associated documentation 
* files (the "Software"), to deal in the Software without restriction, 
* including without limitation the rights to use, copy, modify, merge, 
* publish, distribute, sublicense, and/or sell copies of the Software, 
* and to permit persons to whom the Software is furnished to do so, 
* subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be 
* included in all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
* OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
* THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
* FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS 
* IN THE SOFTWARE.
* 
* @package    GameTracker-PHP
* @author     João Francisco Biondo Trinca <wolfulus@gmail.com>
* @copyright  2012 João Francisco Biondo Trinca <wolfulus@gmail.com>
* @license    http://www.opensource.org/licenses/mit-license.html  MIT License
* @link       http://code.google.com/p/gtracker-php/
* 
*/

/**
* GameTracker Server Information class
*/
class Gt_Server
{
    /**
    * Stores the server ID
    * 
    * @var int
    */         
    private $serverid;

    /**
    * Stores the last request status.
    * 
    * @var boolean
    */
    private $success;

    /**
    * Stores the message taken from the feed server.
    * 
    * @var string
    */
    private $message;

    /**
    * Stores the update timestamp
    * 
    * @var int
    */
    private $updated;

    /**
    * Stores the server info taken from the feed server.
    *         
    * @var SimpleXMLElement
    */
    private $server;

    /**
    * Stores the online players taken from the feed server.
    *         
    * @var array
    */
    private $players = array();

    /**
    * Stores the ranking instance.
    * 
    * @var mixed
    */
    private $ranking = array();

    /**
    * Constructs the class
    * 
    * @param mixed $sid ServerID from GameTracker
    * @return Gt_Server
    */
    public function __construct($sid)
    {
        $this->success = true;

        if (!$this->retrieveServerInformation($sid))
        {
            $this->success = false;
            return;
        }      

        if (!$this->retrievePlayerInformation($sid))
        {
            $this->success = false;
            return;
        }

        $this->updated = time();
    }

    /**
    * Checks if data has been parsed successfully.
    * 
    * @return boolean
    */
    public function succeed()
    {
        return $this->success;
    }
	
	/**
    * Gets the error message.
    * 
    * @return string
    */
    public function getLastError()
    {
        return rawurldecode($this->message);
    }

    /**
    * Retrieve information fom the GameTracker's feed
    * 
    * @param $sid ServerID
    */
    private function retrieveServerInformation($sid)
    {
        $this->serverid = $sid;

        $ch = curl_init();
        if (!$ch)
        {
            throw new Gt_Exception("Failed to initialize cURL instance.");
        }

        curl_setopt($ch, CURLOPT_URL, Gt_Feeds::getServerInfoUrl($this->serverid));
        curl_setopt($ch, CURLOPT_USERAGENT, "BfWeb/0.1");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $output = curl_exec($ch);
        if (!$output || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200)
        {
            throw new Gt_Exception("Failed to fetch data from the feed server.");
        }

        curl_close($ch);

        $doc = new SimpleXmlElement($output, LIBXML_NOCDATA);
        if ($doc->result == 'SUCCESS')
        {
            $this->server = new stdClass();
            $this->server->name = (string) $doc->server->name;    
            $this->server->game = (string) $doc->server->game;    
            $this->server->ip = (string) $doc->server->ip;    
            $this->server->port = (string) $doc->server->port;    
            $this->server->status = (string) $doc->server->status;    
            $this->server->map = (string) $doc->server->map;    
            $this->server->mapimage = (string) $doc->server->mapimage;    
            $this->server->rank = (string) $doc->server->rank;    
            $this->server->playerscurrent = (string) $doc->server->playerscurrent;    
            $this->server->playersmax = (string) $doc->server->playersmax;    
            $this->server->playersbot = (string) $doc->server->playersbot;    
            return true;
        }
        else
        {
            $this->message = $doc->resultstring;
            return false;
        } 

    }

    /**
    * Retrieve information fom the GameTracker's feed
    * 
    * @param $sid ServerID
    */
    private function retrievePlayerInformation($sid)
    {
        $this->serverid = $sid;

        $ch = curl_init();
        if (!$ch)
        {
            throw new Gt_Exception("Failed to initialize cURL instance.");
        }

        curl_setopt($ch, CURLOPT_URL, Gt_Feeds::getOnlinePlayersUrl($this->serverid));
        curl_setopt($ch, CURLOPT_USERAGENT, "BfWeb/0.1");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $output = curl_exec($ch);
        if (!$output || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200)
        {
            throw new Gt_Exception("Failed to fetch data from the feed server.");
        }

        curl_close($ch);

        $doc = new SimpleXmlElement($output, LIBXML_NOCDATA);
        if ($doc->result == 'SUCCESS')
        {
            if (isset($doc->player))
            {
                foreach ($doc->player as $idx => $player)
                {
                    if ($player->bot)
                    {
                        $this->players[] = new Gt_Player($player->name, $player->score, $player->time, $player->bot);
                    }                        
                }
            }
            return true;
        }
        else
        {
            $this->message = $doc->resultstring;
            return false;
        }

    }

    /**
    * Gets the server name.
    * 
    * @returns string
    */
    public function getName()
    {
        return rawurldecode($this->getProperty('name', 'Unknown'));
    }

    /**
    * Gets the server game.
    * 
    * @returns string
    */
    public function getGame()
    {
        return rawurldecode($this->getProperty('game', 'Unknown'));
    }

    /**
    * Gets the server ip.
    * 
    * @returns string
    */
    public function getIp()
    {
        return $this->getProperty('ip', 'Unknown');
    }

    /**
    * Gets the server port.
    * 
    * @returns int
    */
    public function getPort()
    {
        return (int) $this->getProperty('port', '0');
    }

    /**
    * Gets the server status (true = alive).
    * 
    * @returns boolean
    */
    public function isAlive()
    {
        return $this->getProperty('status', 'dead') == 'alive';
    }

    /**
    * Gets the current server map name.
    * 
    * @returns string
    */
    public function getMap()
    {
        return rawurldecode($this->getProperty('map', 'Unknown'));
    }

    /**
    * Gets the current server map image.
    * 
    * @returns string
    */
    public function getMapImage()
    {
        $image = $this->getProperty('mapimage', null);
        if ($image !== null)
        {
            $image = rawurldecode($image);
        }
        return $image;
    }

    /**
    * Gets the current server rank (GameTracker).
    * 
    * @returns int
    */
    public function getServerRank()
    {
        return $this->getProperty('map', -1);
    }

    /**
    * Gets the current max online player count.
    * 
    * @returns string
    */
    public function getMaxPlayers()
    {
        return $this->getProperty('playersmax', 0);
    }

    /**
    * Gets the current online player count.
    * 
    * @returns string
    */
    public function getPlayerCount()
    {
        return $this->getProperty('playerscurrent', 0);
    }

    /**
    * Gets an array containing all online players.
    * 
    * @returns array
    */
    public function getPlayers()
    {
        $instances = array();
        foreach ($this->players as $idx => $player)
        {
            if (!$player->isBot())
            {
                $instances[] = $player;
            }
        }
        return $instances;
    }

    /**
    * Gets the current server online player count.
    * 
    * @returns string
    */
    public function getBotCount()
    {
        return $this->getProperty('playersbot', 0);
    }

    /**
    * Gets an array containing all online bots.
    * 
    * @returns array
    */
    public function getBots()
    {
        $instances = array();
        foreach ($this->players as $idx => $player)
        {
            if ($player->isBot())
            {
                $instances[] = $player;
            }
        }
        return $instances;
    }

    /**
    * Returns the last update's 'timestamp 
    * 
    * @returns int
    */
    public function getLastUpdate()
    {
        return $this->updated;
    }

    /**
    * Gets a property from the data instance.
    * 
    * @param string $name Name of the property.
    * @param mixed $default Default value
    */
    private function getProperty($name, $default)
    {
        if (isset($this->server->{$name}))
        {
            return $this->server->{$name};
        }
        return false;
    }        

}