<?php

namespace Practice\Task;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\level\Position;

use Practice\Main;

class BuildTask extends Task{

    private $plugin;

    public function __construct(Main $plugin){
        $this->pl = $plugin;
    }

    public function onRun(int $currentTick){
        $data = new Config($this->pl->getDataFolder() . "data.yml", Config::YAML);
        $fill = $data->get("build_fill");

        if($fill <= 1){
            $gp1 = $data->get("bp1");
            $gp2 = $data->get("bp2");
            $joueur1 = Server::getInstance()->getPlayer($gp1);
            $joueur2 = Server::getInstance()->getPlayer($gp2);
        }

        if($fill >= 2){
            $gp1 = $data->get("bp1");
            $gp2 = $data->get("bp2");
            $joueur1 = Server::getInstance()->getPlayer($gp1);
            $joueur2 = Server::getInstance()->getPlayer($gp2);

            if($joueur1 instanceof Player){
                if($joueur2 instanceof Player){
                    $this->addAll($joueur1, $joueur2);
                    $num = mt_rand(1, 100);
                    self::copyWorld($joueur1->getServer()->getLevelByName("BuildMap"), "build" . $num);
                    $joueur2->getServer()->loadLevel("build". $num);
                    $joueur2->teleport(new Position(256, 65, 269, $joueur1->getServer()->getLevelByName("build" . $num)));
                    $joueur1->teleport(new Position(256, 65, 243, $joueur1->getServer()->getLevelByName("build" . $num)));
                }
            }


            $data->set("build_fill", $data->get("build_fill") - 2);
            $data->save();

            if($joueur1 instanceof Player){
            $joueur1->sendTitle("§cTéléporting...", "§2Duels Build", 3);
            
            }

            if($joueur2 instanceof Player){
            $joueur2->sendTitle("§cTéléporting...", "§2Duels Build", 3);
            }

            $data->remove("bp1");
            $data->save();

            $data->remove("bp2");
            $data->save();

        }
    
    }

    public function addAll($player, $player2){

        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();

        $helmet = Item::get(Item::DIAMOND_HELMET);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
        $player->getArmorInventory()->setHelmet($helmet);

        $chestplate = Item::get(Item::DIAMOND_CHESTPLATE);
        $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
        $player->getArmorInventory()->setChestplate($chestplate);

        $leggings = Item::get(Item::DIAMOND_LEGGINGS);
        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
        $player->getArmorInventory()->setLeggings($leggings);

        $boots = Item::get(Item::DIAMOND_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
        $player->getArmorInventory()->setBoots($boots);

        $sword = Item::get(Item::DIAMOND_SWORD);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
        $player->getInventory()->setItem(0, $sword);
        $player->getInventory()->setItem(1, Item::get(261, 0, 1));
        $player->getInventory()->setItem(2, Item::get(346, 0, 1));
        $player->getInventory()->setItem(3, Item::get(4, 0, 64));
        $player->getInventory()->setItem(4, Item::get(325, 10, 1));
        $player->getInventory()->setItem(5, Item::get(325, 8, 1));
        $player->getInventory()->setItem(6, Item::get(322, 0, 16));
        $player->getInventory()->setItem(7, Item::get(466, 0, 4));
        $player->getInventory()->setItem(8, Item::get(278, 0, 1));
        $player->getInventory()->setItem(9, Item::get(4, 0, 64));
        $player->getInventory()->setItem(10, Item::get(325, 8, 1));
        $player->getInventory()->setItem(11, Item::get(325, 8, 1));
        $player->getInventory()->setItem(12, Item::get(325, 10, 1));
        $player->getInventory()->setItem(13, Item::get(262, 0, 32));
        $player->setGamemode(0);

        #########################################

        $player2->getInventory()->clearAll();
        $player2->getArmorInventory()->clearAll();

        $helmet = Item::get(Item::DIAMOND_HELMET);
        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
        $player2->getArmorInventory()->setHelmet($helmet);

        $chestplate = Item::get(Item::DIAMOND_CHESTPLATE);
        $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
        $player2->getArmorInventory()->setChestplate($chestplate);

        $leggings = Item::get(Item::DIAMOND_LEGGINGS);
        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
        $player2->getArmorInventory()->setLeggings($leggings);

        $boots = Item::get(Item::DIAMOND_BOOTS);
        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
        $player2->getArmorInventory()->setBoots($boots);

        $sword = Item::get(Item::DIAMOND_SWORD);
        $sword->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
        $player2->getInventory()->setItem(0, $sword);
        $player2->getInventory()->setItem(1, Item::get(261, 0, 1));
        $player2->getInventory()->setItem(2, Item::get(346, 0, 1));
        $player2->getInventory()->setItem(3, Item::get(4, 0, 64));
        $player2->getInventory()->setItem(4, Item::get(325, 10, 1));
        $player2->getInventory()->setItem(5, Item::get(325, 8, 1));
        $player2->getInventory()->setItem(6, Item::get(322, 0, 16));
        $player2->getInventory()->setItem(7, Item::get(466, 0, 4));
        $player2->getInventory()->setItem(8, Item::get(278, 0, 1));
        $player2->getInventory()->setItem(9, Item::get(4, 0, 64));
        $player2->getInventory()->setItem(10, Item::get(325, 8, 1));
        $player2->getInventory()->setItem(11, Item::get(325, 8, 1));
        $player2->getInventory()->setItem(12, Item::get(325, 10, 1));
        $player2->getInventory()->setItem(13, Item::get(262, 0, 32));
        $player2->setGamemode(0);

    }

    public static function copyWorld(Level $level, string $name ): bool{
        $server = Server::getInstance();
        @mkdir($server->getDataPath() . "/worlds/$name/");
        @mkdir($server->getDataPath() . "/worlds/$name/region/");
        copy($server->getDataPath() . "/worlds/" . $level->getFolderName() . "/level.dat", $server->getDataPath() . "/worlds/$name/level.dat");
        $levelPath = $server->getDataPath() . "/worlds/" . $level->getFolderName() . "/level.dat";
        $levelPath = $server->getDataPath() . "/worlds/$name/level.dat";

        $nbt = new BigEndianNBTStream();
        $levelData = $nbt->readCompressed(file_get_contents($levelPath));
        $levelData = $levelData->getCompoundTag("Data");
        $oldName = $levelData->getString("LevelName");
        $levelData->setString("LevelName", $name);
        $nbt = new BigEndianNBTStream();
        file_put_contents($levelPath, $nbt->writeCompressed(new CompoundTag("", [$levelData])));
        self::copy_directory($server->getDataPath() . "/worlds/" . $level->getFolderName() . "/region/", $server->getDataPath() . "/worlds/$name/region/");
        return true;
    }
    private static function copy_directory($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                self::copy_directory($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
        closedir($dir);
    }
}