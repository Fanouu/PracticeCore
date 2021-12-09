<?php

namespace Practice\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Server;
use Practice\Main;

class PlayerDeath implements Listener{

    public function onJoin(PlayerDeathEvent $event){

        $player = $event->getPlayer();
        $pname = $event->getPlayer()->getName();

        $event->setDeathMessage("");
   
        $cause = $player->getLastDamageCause()->getCause();
       if($cause === EntityDamageByEntityEvent::CAUSE_ENTITY_ATTACK){
        $killer = $player->getLastDamageCause()->getDamager();
        $killeur = $player->getLastDamageCause()->getDamager()->getName();
        $killer->removeAllEffects();

        Server::getInstance()->broadcastMessage("§1[§cSolaria§1] §2{$killeur} §fhumilie §c{$pname}");

        $list = [ "Warzone", "WarzonePro", "Spawn", "World", "Jump", "Sumo1" ];
        if($killer->getLevel()->getFolderName() != "200" and($killer->getLevel()->getFolderName() != "Warzone")){
            $level = $killer->getLevel()->getFolderName();
            $dir = Main::getInstance()->getServer()->getDataPath() . "/worlds/{$level}";
            $this->deleteTree($dir);
            rmdir($dir);
            $killer->setHealth(20);
            $killer->setFood(20);
            $killer->setGamemode(2);
            $killer->teleport($killer->getServer()->getLevelByName("200")->getSafeSpawn());
            $killer->getInventory()->clearAll();
            $killer->getArmorInventory()->clearAll();
            $item = Item::get(Item::DIAMOND_SWORD);
            $item->setCustomName("§e- §6DUEL§e -");
            $killer->getInventory()->setItem(2, $item);
            $item = Item::get(Item::GOLD_SWORD);
            $item->setCustomName("§e- §6FFA§e -");
            $killer->getInventory()->setItem(6, $item);
            $item = Item::get(Item::COMPASS);
            $item->setCustomName("§e- §6Serveur§e -");
            $killer->getInventory()->setItem(4, $item);
            
            Server::getInstance()->broadcastMessage("§1[§cSolaria§1] §6{$killeur} §fà gagné un duel contre §c{$pname}");
            $killer->sendMessage("------------------------------\n§e{$killeur}§f: Win | §c{$pname}§f: Lose\n------------------------------");
            $player->sendMessage("------------------------------\n§e{$killeur}§f: Win | §c{$pname}§f: Lose\n------------------------------");
        }

       }else{
           $player->getServer()->broadcastMessage("§1[§cSolaria§1] §c{$pname} §fest mort !");
       }
    }
    
    public function deleteTree($dir){
        foreach(glob($dir . "/*") as $element){
            if(is_dir($element)){
                $this->deleteTree($element);
                 rmdir($element);
             } else {
                  unlink($element);
             }
          }
    }
}