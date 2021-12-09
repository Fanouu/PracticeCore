<?php

namespace Practice;

use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use Practice\Events\PlayerAttackEvent;
use Practice\Events\PlayerInteract;
use Practice\Events\PlayerPlaceAndBreak;
use Practice\Events\PlayerNoHunger;
use Practice\Events\PlayerRespawn;
use Practice\Events\PlayerJoin;
use Practice\Events\PlayerDeath;
use Practice\Events\PlayerQuit;

class Main extends PluginBase implements Listener{
    public static $core;
    public function onEnable(){
        $this->getLogger("PRACTICE");
        $this->getServer()->getPluginManager()->registerEvents(new PlayerInteract($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerRespawn(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerJoin(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerDeath(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerPlaceAndBreak(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerAttackEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerQuit($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerNoHunger(), $this);
        $this->getServer()->loadLevel("BuildMap");
        $this->getServer()->loadLevel("Warzone");
        $this->getServer()->loadLevel("GappleMap");
        $this->getServer()->loadLevel("BuildMap");
        $this->getServer()->loadLevel("NoDebuffMap");
        $this->getScheduler()->scheduleRepeatingTask(new Task\GappleTask($this), 5);
        $this->getScheduler()->scheduleRepeatingTask(new Task\NoDebuffTask($this), 5);
        $this->getScheduler()->scheduleRepeatingTask(new Task\BuildTask($this), 5);
        //$this->getScheduler()->scheduleRepeatingTask(new Task\SumoTask($this), 5);
        self::$core = $this;
    }
    public static function getInstance() : Main{
        return self::$core;
    }
}