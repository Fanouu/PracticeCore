<?php

namespace Practice\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;

class PlayerNoHunger implements Listener{
    public function onEvent(PlayerExhaustEvent $e){
        $e->setCancelled(true);
    }
}