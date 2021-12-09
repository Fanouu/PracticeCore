<?php

namespace Practice\Events;

use mysql_xdevapi\CollectionModify;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use Practice\Main;

class PlayerJoin implements Listener{

    public function onJoin(PlayerJoinEvent $event){

        $player = $event->getPlayer();
        $pname = $event->getPlayer()->getName();

        $player->setGamemode(2);

      $player->getInventory()->clearAll(true);
      $player->getArmorInventory()->clearAll(true);
      $player->getArmorInventory()->clearAll();
      $item = Item::get(Item::DIAMOND_SWORD);
      $item->setCustomName("§e- §6DUEL§e -");
      $player->getInventory()->setItem(2, $item);
      $item = Item::get(Item::GOLD_SWORD);
      $item->setCustomName("§e- §6FFA§e -");
      $player->getInventory()->setItem(6, $item);
      $item = Item::get(Item::COMPASS);
      $item->setCustomName("§e- §6Serveur§e -");
      $player->getInventory()->setItem(4, $item);
      $event->getPlayer()->teleport($player->getServer()->getLevelByName("200")->getSafeSpawn());
      $event->setJoinMessage("");
      $player->getServer()->broadcastMessage("§f[§e+§f] §e{$pname}");
      $player->removeAllEffects();
    }
}