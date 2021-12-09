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

class GappleTask extends Task{

    private $plugin;

    public function __construct(Main $plugin){
        $this->pl = $plugin;
    }

    public function onRun(int $currentTick){
        $data = new Config($this->pl->getDataFolder() . "data.yml", Config::YAML);
        $fill = $data->get("debuff_fill");

        if($fill <= 1){
            $gp1 = $data->get("dp1");
            $gp2 = $data->get("dp2");
            $joueur1 = Server::getInstance()->getPlayer($gp1);
            $joueur2 = Server::getInstance()->getPlayer($gp2);
            if($joueur1 instanceof Player){
            $joueur1->sendPopup("§cEn recherche d'adversaire...");
            }

            if($joueur2 instanceof Player){
            $joueur2->sendPopup("§cEn recherche d'adversaire...");
            }
        }

        if($fill >= 2){
            $gp1 = $data->get("dp1");
            $gp2 = $data->get("dp2");
            $joueur1 = Server::getInstance()->getPlayer($gp1);
            $joueur2 = Server::getInstance()->getPlayer($gp2);

            if($joueur1 instanceof Player){
                if($joueur2 instanceof Player){
                    $this->addAll($joueur1, $joueur2);
                    $num = mt_rand(1, 100);
                    self::copyWorld($joueur1->getServer()->getLevelByName("duel"), "debuff" . $num);
                    $joueur2->getServer()->loadLevel("debuff". $num);
                    $joueur2->teleport(new Position(212, 70, 277, $joueur1->getServer()->getLevelByName("debuff" . $num)));
                    $joueur1->teleport(new Position(255, 70, 277, $joueur1->getServer()->getLevelByName("debuff" . $num)));
                }
            }


            $data->set("debuff_fill", $data->get("debuff_fill") - 2);
            $data->save();

            if($joueur1 instanceof Player){
            $joueur1->sendTitle("§cTéléporting...", "§2Duels Debuff", 3);
            
            }

            if($joueur2 instanceof Player){
            $joueur2->sendTitle("§cTéléporting...", "§2Duels Debuff", 3);
            }

            $data->remove("dp1");
            $data->save();

            $data->remove("dp2");
            $data->save();

        }
    
    }

    public function addAll($player, $player2){

        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();

        #########################################

        $player2->getInventory()->clearAll();
        $player2->getArmorInventory()->clearAll();

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