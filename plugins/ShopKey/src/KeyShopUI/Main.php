<?php

namespace KeyShopUI;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\Config;
use jojoe77777\FormAPI;
use onebone\coinapi\CoinAPI;
use jojoe77777\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener {
	
	public function onEnable(){
		$this->getLogger()->info("Enable Plugin ShopKey By Vbee");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label,array $args): bool{
		switch($cmd->getName()){
			case "shopkey":
			if(!$sender instanceof Player){
			$sender->sendMessage("Sử dụng lệnh trong game");
			return false;
			}
			if($sender instanceof Player){
			$this->shopFrom($sender);
			}
			break;		
		}
		return true;
	}
	
	public function shopFrom(Player $player){
		$form = new SimpleForm(function (Player $player, $data){
		$result = $data;
		if($result === null){
			return true;
			}
			switch($result){
				case 0:
					if(\pocketmine\Server::getInstance()->getPluginManager()->getPlugin("CoinAPI")->myCoin($player) >= $this->getConfig()->get("common.price")){
						$this->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender(), "key Common ".$player->getName()." ".$this->getConfig()->get("common.amount"));
						$player->sendMessage($this->getConfig()->get("common.success.purchase"));
						CoinAPI::getInstance()->reduceCoin($player, $this->getConfig()->get("common.price"));
					} else {
						$player->sendMessage($this->getConfig()->get("not.enough.money"));
					}
				break;
				case 1:
					if(\pocketmine\Server::getInstance()->getPluginManager()->getPlugin("CoinAPI")->myCoin($player) >= $this->getConfig()->get("uncommon.price")){
						$this->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender(), "key UnCommon ".$player->getName()." ".$this->getConfig()->get("uncommon.amount"));
						$player->sendMessage($this->getConfig()->get("uncommon.success.purchase"));
						CoinAPI::getInstance()->reduceCoin($player, $this->getConfig()->get("uncommon.price"));
					} else {
						$player->sendMessage($this->getConfig()->get("not.enough.money"));
					}
				break;
				case 2:
					if(\pocketmine\Server::getInstance()->getPluginManager()->getPlugin("CoinAPI")->myCoin($player) >= $this->getConfig()->get("mythic.price")){
						$this->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender(), "key Mythic ".$player->getName()." ".$this->getConfig()->get("mythic.amount"));
						$player->sendMessage($this->getConfig()->get("mythic.success.purchase"));
						CoinAPI::getInstance()->reduceCoin($player, $this->getConfig()->get("mythic.price"));
					} else {
						$player->sendMessage($this->getConfig()->get("not.enough.money"));
					}
				break;
				case 3:
					if(\pocketmine\Server::getInstance()->getPluginManager()->getPlugin("CoinAPI")->myCoin($player) >= $this->getConfig()->get("legendary.price")){
						$this->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender(), "key Legendary ".$player->getName()." ".$this->getConfig()->get("legendary.amount"));
						$player->sendMessage($this->getConfig()->get("legendary.success.purchase"));
						CoinAPI::getInstance()->reduceCoin($player, $this->getConfig()->get("legendary.price"));
					} else {
						$player->sendMessage($this->getConfig()->get("not.enough.money"));
					}
				break;
			}
		});
		$config = $this->getConfig();
		$this->getServer()->getPluginManager()->getPlugin("CoinAPI")->myCoin($player);
		$form->setTitle("§l§dShop§eKey");
		$form->setContent("§l§e➛ §aXin chào , §f". $player->getName(). "\n§aCoin Hiện Tại : §f" . $this->getServer()->getPluginManager()->getPlugin("CoinAPI")->myCoin($player). "\n§aRank : §f". $this->getServer()->getPluginManager()->getPlugin("PurePerms")->getUserDataMgr()->getGroup($player)->getName() . "\n§aNhớ vote cho server tại : bit.do/kamvn");
		$form->addButton("§l§eCommon ✳\n§aGiá: 20§eCoin", 0, "textures/ui/accessibility_glyph_color");
		$form->addButton("§l§eUnCommon ✳\n§aGiá: 30§eCoin", 0, "textures/ui/accessibility_glyph_color");
		$form->addButton("§l§eMythic ✳\n§aGiá: 40§eCoin", 0, "textures/ui/accessibility_glyph_color");
		$form->addButton("§l§eLegendary ✳\n§aGiá: 50§eCoin", 0, "textures/ui/accessibility_glyph_color");
		$form->sendToPlayer($player);
	}
}
