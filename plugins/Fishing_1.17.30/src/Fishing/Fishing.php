<?php

/*
Bend95280 do not know what should i put here
*/

namespace Fishing;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Fishing\utils\FishingLootTable;
use Fishing\utils\FishingLevel;
use Fishing\entity\EntityManager;
use Fishing\item\ItemManager;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\PlayerJoinEvent;

class Fishing extends PluginBase {
	
	/** @var Fishing */
	private static $instance = null;
	/** @var Session[] */
	private $sessions = [];
	/** @var Config */
	public static $cacheFile;
	public static $levelFile;
	
	public $lang;
	
	public static $randomFishingLootTables = true;
	public static $registerVanillaEnchantments = true;

	public static function getInstance(): Fishing{
		return self::$instance;
	}
	
	public function onLoad(){
	    if(!self::$instance instanceof Fishing){
	        self::$instance = $this;
	    }
		@mkdir($this->getDataFolder());
		self::$cacheFile = new Config($this->getDataFolder() . "cache.json", Config::JSON);
		//Lang init
        new Config($this->getDataFolder() . 'lang.yml', Config::YAML, array(
            "lvlup" => "§l§aFishing Level Up",
            "lvltoolownight" => "§4Cấp độ của bạn không thể câu vào buổi tối",
            "fishsize" => "Size",
            "fishhasgoneaway" => "§l§aFishing Fall",
            "linebreaklvltoolow" => "§cError",
            "tooslowfishhasgoneaway" => "§l§bFishing Fall",
        ));
		
		$this->lang = (array)yaml_parse_file($this->getDataFolder() . "lang.yml");
       	 $this->sl = new Config($this->getDataFolder()."soluong.yml",Config::YAML);
	}
	
    public function onEnable(){
		FishingLootTable::init();
		FishingLevel::init();
		ItemManager::init();
		EntityManager::init();
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }
    public function onJoin(PlayerJoinEvent $ev) {
        if(!$this->sl->exists($ev->getPlayer()->getName())) {
            $this->sl->set($ev->getPlayer()->getName(), 0);
            $this->sl->save();
         }
    }
		public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "topfishing":
                $this->TopFishing($sender);
          return true;
        }
        return true;
	}
		public function TopFishing(Player $player){
		$point = $this->sl->getAll();
		$message = ""; $rank = "100+";
		if(count($point) > 0){
			arsort($point);
			$i = 1;
			foreach($point as $name => $p){
			    if($i == 1) $message .= "§l§cTOP $i : §6$name §c➢ §f$p §cPoint\n";
			    if($i == 2) $message .= "§l§eTOP $i : §6$name §e➢ §f$p §ePoint\n";
			    if($i == 3) $message .= "§l§aTOP $i : §6$name §a➢ §f$p §aPoint\n";
			    if($i >3) $message .= "§l§fTOP $i : §6$name §f➢ §f$p Point\n";
				if($name == $player->getName())$rank = $i;
				if($i >= 100) break;
				++$i;
			}
		}
		$point = $this->sl->get($player->getName());;
		$form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function (Player $sender, $data ){});
		$form->setTitle("§l§f•§bTop Fishing§f•");
		$form->setContent("§e•§l§bRank of you: §f$rank \n".$message);
		$form->addButton("§l§f•§b $point §ePoint §f•");
		$form->sendToPlayer($player);
		return $form;
	}
	
	public function getSessionById(int $id){
		if(isset($this->sessions[$id])){
			return $this->sessions[$id];
		}else{
			return null;
		}
	}	
	
	public function createSession(Player $player): bool{
		if(!isset($this->sessions[$player->getId()])){
			$this->sessions[$player->getId()] = new Session($player);
			$this->getLogger()->debug("Created " . $player->getName() . "'s Session");

			return true;
		}

		return false;
	}	
	
	public function destroySession(Player $player): bool{
		if(isset($this->sessions[$player->getId()])){
			unset($this->sessions[$player->getId()]);
			$this->getLogger()->debug("Destroyed " . $player->getName() . "'s Session");

			return true;
		}

		return false;
	}
}
