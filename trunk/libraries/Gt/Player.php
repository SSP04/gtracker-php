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
* Player class
*/
class Gt_Player
{
    /**
    * Stores the online time of the player.
    * 
    * @var int
    */
    private $time;
    
    /**
    * Stores the name of the player.
    * 
    * @var mixed
    */
    private $name;
    
    /**
    * Stores the score of the player.
    * 
    * @var mixed
    */
    private $score;
    
    /**
    * Stores if the player is a bot.
    * 
    * @var mixed
    */
    private $bot;
    
    /**
    * Creates a new instance of the Player class.
    * 
    * @param mixed $pname Player name.
    * @param mixed $pscore Player score.
    * @param mixed $otime Online time.
    * @return Gt_Player
    */
    public function __construct($pname, $pscore, $otime, $pbot)
    {
        $this->name = (string) $pname;
        $this->score = (string) $pscore;
        $this->time = (string) $otime;
        $this->bot = (string) $pbot;
    }
    
    /**
    * Gets the player name.
    * 
    * @returns string
    */
    public function getName()
    {
        return $this->name;
    }
    
    /**
    * Gets the player score.
    * 
    * @returns int
    */
    public function getScore()
    {
        return $this->score;
    }
    
    /**
    * Gets the player score.
    * 
    * @returns boolean
    */
    public function isBot()
    {
        return $this->bot != 0;
    }
    
    /**
    * Get online time.
    * 
    * Formats: 
    *   {d} = Days
    *   {h} = Hours
    *   {m} = Minutes
    * 
    * @param mixed $format
    * @return string
    */
    public function getTime($format = null)
    {
        if($format === null)
        {
            return $this->time;
        }
        
        $days = floor ($this->time / 1440);
        $hours = floor (($this->time - $days * 1440) / 60);
        $minutes = $this->time - ($days * 1440) - ($hours * 60);
        
        $final = $format;
        $final = str_replace("{d}", $days, $final);
        $final = str_replace("{h}", $hours, $final);
        $final = str_replace("{m}", $minutes, $final);
        
        return $final;
        
    }
    
}
