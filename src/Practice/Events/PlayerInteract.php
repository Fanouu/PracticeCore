<?php

namespace Practice\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\Player;
use Practice\Main;
use pocketmine\level\Position;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\Server;

class PlayerInteract implements Listener{

    private $plugin;
    private static $cooldown;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $ids = $event->getItem()->getId();
        $levels = $player->getLevel()->getFolderName();

        if(!isset(self::$cooldown[$player->getName()]) or self::$cooldown[$player->getName()] - time() <= 0){
            self::$cooldown[$player->getName()] = time() + 0.5;
            if($ids === Item::DIAMOND_SWORD and($levels === "200")){
               $this->openDualls($player);
            }
            if($ids === Item::GOLD_SWORD and($levels === "200")){
                $this->openFFA($player);
            }

            if($ids === Item::COMPASS){
                self::KitMapForm($player);
            }
            if($ids === Item::FEATHER){
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
                $data = new Config($this->plugin->getDataFolder() . "data.yml", Config::YAML);

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


                $player->sendPopup("§cYou are succesfully leave fill");

            }
        }
    }

    public function openDualls($player){
        $api = $player->getServer()->getPluginManager()->getPlugin("FormAPI");

        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }             
            switch($result){
                case 0:
                    $player->getArmorInventory()->clearAll();
                    $player->getInventory()->clearAll();

                    $item = Item::get(Item::FEATHER);
                    $item->setCustomName("§cQuitté");
                    $player->getInventory()->setItem(6, $item);
                    $data = new Config($this->plugin->getDataFolder() . "data.yml", Config::YAML);

                                      

                    $data->set("gapple_fill", $data->get("gapple_fill") + 1);
                    $data->save();

                    if(!$data->exists("gp1") and($data->exists("gp2"))){
                        $data->set("gp1", $player->getName());
                        $data->save();
                    }else if(!$data->exists("gp2") and($data->exists("gp1"))){
                        $data->set("gp2", $player->getName());
                        $data->save();
                    }

                    if(!$data->exists("gp1") and(!$data->exists("gp2"))){
                        $data->set("gp1", $player->getName());
                        $data->save();
                    }

                    $player->sendTitle("§cVous êtes dans la fille d'attente", "§2Duels Gapple", 3);
                break;

                case 1:
                    $player->getArmorInventory()->clearAll();
                    $player->getInventory()->clearAll();

                    $item = Item::get(Item::FEATHER);
                    $item->setCustomName("§cQuitté");
                    $player->getInventory()->setItem(6, $item);
                    $data = new Config($this->plugin->getDataFolder() . "data.yml", Config::YAML);

                                      

                    $data->set("build_fill", $data->get("build_fill") + 1);
                    $data->save();

                    if(!$data->exists("bp1") and($data->exists("bp2"))){
                        $data->set("bp1", $player->getName());
                        $data->save();
                    }else if(!$data->exists("bp2") and($data->exists("bp1"))){
                        $data->set("bp2", $player->getName());
                        $data->save();
                    }

                    if(!$data->exists("bp1") and(!$data->exists("bp2"))){
                        $data->set("bp1", $player->getName());
                        $data->save();
                    }

                    $player->sendTitle("§cVous êtes dans la fille d'attente", "§2Duels Build", 3);
                break;

                case 2:
                    $player->getArmorInventory()->clearAll();
                    $player->getInventory()->clearAll();

                    $item = Item::get(Item::FEATHER);
                    $item->setCustomName("§cQuitté");
                    $player->getInventory()->setItem(6, $item);
                    $data = new Config($this->plugin->getDataFolder() . "data.yml", Config::YAML);

                                      

                    $data->set("nodebuff_fill", $data->get("nodebuff_fill") + 1);
                    $data->save();

                    if(!$data->exists("np1") and($data->exists("np2"))){
                        $data->set("np1", $player->getName());
                        $data->save();
                    }else if(!$data->exists("np2") and($data->exists("np1"))){
                        $data->set("np2", $player->getName());
                        $data->save();
                    }

                    if(!$data->exists("np1") and(!$data->exists("np2"))){
                        $data->set("np1", $player->getName());
                        $data->save();
                    }

                    $player->sendTitle("§cVous êtes dans la fille d'attente", "§2Duels NoDebuff", 3);
                break;

                case 3:
                    $player->getArmorInventory()->clearAll();
                    $player->getInventory()->clearAll();

                    $item = Item::get(Item::FEATHER);
                    $item->setCustomName("§cQuitté");
                    $player->getInventory()->setItem(6, $item);
                    $data = new Config($this->plugin->getDataFolder() . "data.yml", Config::YAML);

                                      

                    $data->set("debuff_fill", $data->get("debuff_fill") + 1);
                    $data->save();

                    if(!$data->exists("dp1") and($data->exists("dp2"))){
                        $data->set("dp1", $player->getName());
                        $data->save();
                    }else if(!$data->exists("dp2") and($data->exists("dp1"))){
                        $data->set("dp2", $player->getName());
                        $data->save();
                    }

                    if(!$data->exists("dp1") and(!$data->exists("dp2"))){
                        $data->set("dp1", $player->getName());
                        $data->save();
                    }

                    $player->sendTitle("§cVous êtes dans la fille d'attente", "§2Duels NoStuff", 3);
                break;
            

            }
            return true; 
            
        });
        $data = new Config($this->plugin->getDataFolder() . "data.yml", Config::YAML);

        $form->setTitle("§f- §cDuels §f-");
        $form->setContent("Choisissez une option!");
        $form->addButton("§cGapple \n§6§l" . $data->get("gapple_fill") . " §r§7dans la fille d'attente");
        $form->addButton("§cBuild \n§6§l" . $data->get("build_fill") . " §r§7dans la fille d'attente");
        $form->addButton("§cNoDebuff \n§6§l" . $data->get("nodebuff_fill") . " §r§7dans la fille d'attente");
        //$form->addButton("§cNoStuff \n§7" . $data->get("debuff_fill" . " dans la fille d'attente"));  
        $player->sendForm($form);
    }
    
    public static function openFFA($player){
        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            switch($result){

                case 0:
                    $player->setHealth(20);
                    $player->setFood(20);
                    $player->teleport(Main::getInstance()->getServer()->getLevelByName("Warzone")->getSafeSpawn());

                    $player->getInventory()->clearAll();
                    $player->getArmorInventory()->clearAll();

                    $helmet = Item::get(Item::GOLD_HELMET);
                    $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
                    $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                    $player->getArmorInventory()->setHelmet($helmet);

                    $chestplate = Item::get(Item::GOLD_CHESTPLATE);
                    $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
                    $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                    $player->getArmorInventory()->setChestplate($chestplate);
                    $leggings = Item::get(Item::GOLD_LEGGINGS);
                    $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
                    $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                    $player->getArmorInventory()->setLeggings($leggings);
                    $boots = Item::get(Item::GOLD_BOOTS);
                    $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
                    $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                    $player->getArmorInventory()->setBoots($boots);

                    $sword = Item::get(Item::GOLD_SWORD);
                    $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 4));
                    $player->getInventory()->setItem(0, $sword);

                    $potions = Item::get(438, 22, 35);
                    $player->getInventory()->addItem($potions);
                    break;

                case 1:
                    $player->setHealth(20);
                    $player->setFood(20);
                    $player->teleport(Main::getInstance()->getServer()->getLevelByName("Warzone")->getSafeSpawn());
                    $player->getInventory()->clearAll();
                    $player->getArmorInventory()->clearAll();

                    $helmet = Item::get(Item::DIAMOND_HELMET);
                    $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
                    $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                    $player->getArmorInventory()->setHelmet($helmet);

                    $chestplate = Item::get(Item::DIAMOND_CHESTPLATE);
                    $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
                    $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                    $player->getArmorInventory()->setChestplate($chestplate);
                    $leggings = Item::get(Item::DIAMOND_LEGGINGS);
                    $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
                    $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                    $player->getArmorInventory()->setLeggings($leggings);
                    $boots = Item::get(Item::DIAMOND_BOOTS);
                    $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
                    $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                    $player->getArmorInventory()->setBoots($boots);

                    $sword = Item::get(Item::DIAMOND_SWORD);
                    $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS), 4));
                    $player->getInventory()->setItem(0, $sword);

                    $potions = Item::get(438, 22, 35);
                    $player->getInventory()->addItem($potions);
                    break;
            }
            return true;

        });
        $form->setTitle("§1[§cSolaria FFA§1]");
        $form->setContent("Choisissez une option!");
        $form->addButton("§6- Saphir §6-");
        $form->addButton("§6- Diams §6-");
        $player->sendForm($form);
    }
    public static function KitMapForm($player){
        $api = Main::getInstance()->getServer()->getPluginManager()->getPlugin("FormAPI");

        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            switch($result){

                case 0:
                    $player->sendPopup("§eRedirection...");
                    $player->sendMessage("§cServeur fermé !");
                    break;
            }
            return true;

        });
        $form->setTitle("§1[§cSolaria Server§1]");
        $form->setContent("Choisissez une option!");
        $form->addButton("§6- Kitmap §c§lMAINTENANCE §6-");
        $player->sendForm($form);
    }
}