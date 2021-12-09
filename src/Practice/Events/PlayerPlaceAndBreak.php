<?php

namespace Practice\Events;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;

class PlayerPlaceAndBreak implements Listener{
    public function onBreak(BlockBreakEvent $e){
        if($e->getPlayer()->getLevel()->getName() === "Warzone" || $e->getPlayer()->getLevel()->getName() === "200"){
            $e->setCancelled(true);
        }
    }
    public function onPlace(BlockPlaceEvent $e){
        if($e->getPlayer()->getLevel()->getName() === "Warzone" || $e->getPlayer()->getLevel()->getName() === "200"){
            $e->setCancelled(true);
        }
    }
}