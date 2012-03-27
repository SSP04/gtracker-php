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
* GameTracker's Feed Urls
*/
class Gt_Feeds
{
    /**
    * Stores the gametracker's feed URL for server information.
    */
    const FEED_URL_SERVERINFO = 'http://www.gametracker.com/datafeeds/?server_info&GAMESERVERID={SERVERID}'; 
    
    /**
    * Stores the gametracker's feed URL for online players.
    */
    const FEED_URL_ONLINEPLAYERS = 'http://www.gametracker.com/datafeeds/?server_online_players&GAMESERVERID={SERVERID}'; 
    
    /**
    * Stores the gametracker's feed URL for rankings.
    */                                 
    const FEED_URL_RANKING = 'http://www.gametracker.com/datafeeds/?server_top_players&GAMESERVERID={SERVERID}'; 
    
    /**
    * Gets the ranking feed url   
    * 
    * @param string $serverid
    */
    public static function getRankingUrl($serverid)
    {
        return str_replace("{SERVERID}", $serverid, self::FEED_URL_RANKING);
    }
    
    /**
    * Gets the server info feed url   
    * 
    * @param string $serverid
    */
    public static function getServerInfoUrl($serverid)
    {
        return str_replace("{SERVERID}", $serverid, self::FEED_URL_SERVERINFO);
    }
    
    /**
    * Gets the online players feed url   
    * 
    * @param string $serverid
    */
    public static function getOnlinePlayersUrl($serverid)
    {
        return str_replace("{SERVERID}", $serverid, self::FEED_URL_ONLINEPLAYERS);
    }
    
}