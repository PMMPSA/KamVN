<?php


namespace Vbee\DanhGia;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\{Player, Server};
use jojoe7777\FormAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as CP;

class Main extends PluginBase{
	public $config;
	
	public function onEnable(){
		$this->getServer()->getLogger()->info("DanhGia By Vbee");
		 $this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		
		$this->ratefile = new Config($this->getDataFolder(). "DanhGia.yml", Config::YAML);
	}
	
	public function onLoad(): void{
		$this->getServer()->getLogger()->notice("Đánh Giá v2 By Vbee");
	}
	
	public function onCommand(CommandSender $player, Command $cmd, string $label, array $args) : bool {
		if ($cmd->getName() === "danhgia") {
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(Function (Player $s, $data){
			$result = $data;if ($result === null) { return true; }
			switch($data[0]){
				case 0:
				$rate = "5 ✰";

				break;
				case 1:
				$rate = "4 ✰";
	
				break;
				case 2:
				$rate = "3 ✰";
				
				break;
				case 3:
				$rate = "2 ✰";
				
				break;
				case 4:
				$rate = "1 ✰";
				
				break;
			}
			
			$this->getServer()->getLogger()->notice("Có bạn  ".$s->getName().", Ở DanhGia.yml");
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
				$form = $api->createCustomForm(Function (Player $player, $data){
				});
				
			$form->setTitle("§l§e•§a Success §e•");
			$form->addLabel("§l§c↣§a Bạn đã đánh giá server thành công!");
			$form->sendToPlayer($s);
			
			$this->ratefile->set( $s->getName(), ["DanhGia" => $rate, "NhanXet" => $data[1]]);
			$this->ratefile->save();
		});
		$form->setTitle("§l§e•§a Đánh Giá §e•");
		$form->addDropdown("§l§c↣§e Đánh Giá:", ["§l§a5§e ✰", "§l§a4§e ✰", "§l§a3§e ✰", "§l§a2§e ✰", "§l§a1§e ✰"]);
		$form->addInput("§l§c↣§e Nhận Xét:");
		$form->sendToPlayer($player);
		return true;
	}
	}
}
	
		
	
			
			