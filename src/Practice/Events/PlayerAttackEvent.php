<?php

namespace Practice\Events;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use Practice\Main;

class PlayerAttackEvent implements Listener{
    public function onDamage(EntityDamageEvent $e){
        if($e->getEntity() instanceof Player){
            $e->setKnockback(0.5);
        if($e->getEntity()->getLevel()->getName() == Main::getInstance()->getServer()->getDefaultLevel()->getName()){
            if(!$e->getEntity()->isOp()){
            	$e->setCancelled(true);
            }
        }
        }
    }
}