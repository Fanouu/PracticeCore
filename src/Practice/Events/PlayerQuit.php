<?php

namespace Practice\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\item\Item;

use Practice\Main;

class PlayerQuit implements Listener{

  private $plugin;
  public static $levels;

    public function __construct(Main $plugin){
      $this->plugin = $plugin;
    }

    public function onQuit(PlayerQuitEvent $event){

        $player = $event->getPlayer();
        $pname = $event->getPlayer()->getName();
        $data = new Config($this->plugin->getDataFolder() . "data.yml", Config::YAML);
        $event->setQuitMessage("");
        
        $player->removeAllEffects();
        foreach(Server::getInstance()->getOnlinePlayers() as $playersz){
                if($playersz === $pname){
                    self::$levels = $playersz->getLevel()->getName();
                }
        }
        
        if(str_contains(self::$levels, 'gapple') || str_contains(self::$levels, 'nodebuff') || str_contains(self::$levels,'build')){
            foreach(Server::getInstance()->getOnlinePlayers() as $players){

                if($players->getName()->getLevel()->getName() === self::$levels){
                    $dir = Main::getInstance()->getServer()->getDataPath() . "/worlds/{$players->getName()->getLevel()->getName()}";
                    $this->deleteTree($dir);
                    rmdir($dir);
                    $pnames = $player->getName();
                    Server::getInstance()->broadcastMessage("§1[§cSolaria§1] §6$pnames §fà gagné un duel contre §c$pname");
                    $players->sendMessage("------------------------------\n§e$pnames§f: Win | §c$pname§f: Lose\n------------------------------");
                    $players->teleport($players->getServer()->getLevelByName("200")->getSafeZone());
                    
                    $players->setHealth(20);
                    $players->setFood(20);
                    $players->setGamemode(2);
                    $players->getInventory()->clearAll();
                    $players->getArmorInventory()->clearAll();
                    $item = Item::get(Item::DIAMOND_SWORD);
                    $item->setCustomName("§e- §6DUEL§e -");
                    $players->getInventory()->setItem(2, $item);
                    $item = Item::get(Item::GOLD_SWORD);
                    $item->setCustomName("§e- §6FFA§e -");
                    $players->getInventory()->setItem(6, $item);
                    $item = Item::get(Item::COMPASS);
                    $item->setCustomName("§e- §6Serveur§e -");
                    $players->getInventory()->setItem(4, $item);
                    $players->removeAllEffects();
                }
            }
        }

        if($data->get("gp1") === $player->getName()){
          $data->remove("gp1");
          $data->save();
          $data->set("gapple_fill", $data->get("gapple_fill") - 1);
          $data->save();
      }

      if($data->get("gp2") === $player->getName()){
          $data->remove("gp2");
          $data->save();
          $data->set("gapple_fill", $data->get("gapple_fill") - 1);
         $data->save();
      }

      if($data->get("bp1") === $player->getName()){
          $data->remove("bp1");
          $data->save();
          $data->set("build_fill", $data->get("build_fill") - 1);
          $data->save();
      }

      if($data->get("bp2") === $player->getName()){
          $data->remove("bp2");
          $data->save();
          $data->set("build_fill", $data->get("build_fill") - 1);
         $data->save();
      }

      if($data->get("np1") === $player->getName()){
          $data->remove("np1");
          $data->save();
          $data->set("nodebuff_fill", $data->get("nodebuff_fill") - 1);
          $data->save();
      }

      if($data->get("np2") === $player->getName()){
          $data->remove("np2");
          $data->save();
          $data->set("nodebuff_fill", $data->get("nodebuff_fill") - 1);
         $data->save();
      }

      if($data->get("dp1") === $player->getName()){
          $data->remove("dp1");
          $data->save();
          $data->set("debuff_fill", $data->get("debuff_fill") - 1);
          $data->save();
      }

      if($data->get("dp2") === $player->getName()){
          $data->remove("dp2");
          $data->save();
          $data->set("debuff_fill", $data->get("debuff_fill") - 1);
         $data->save();
      }
        
      $player->getServer()->broadcastMessage("§f[§c-§f] §c{$pname}");
    }
   

    public function deleteWorld($dir){
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