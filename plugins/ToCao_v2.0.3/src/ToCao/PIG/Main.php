<?php

namespace ToCao\PIG;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\Config;
use pocketmine\{Player, Server};
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use jojoe7777\FormAPI;

class Main extends PluginBase implements Listener{
	public $tag = "";
	public $report = "•§a Tố Cáo §r";
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getLogger()->info($this->tag . " §cReport§aSPN is Always Online.....");
		$this->getLogger()->info("\n\n§c§l•§b R༶E༶P༶O༶R༶T༶S༶P༶N༶V༶N༶ §6Version §e4\n§c❤️ §aStarting Plugin By §aMine§bPig\n\n");
		$this->rp = new Config($this->getDataFolder() . "Report.yml", Config::YAML, []);
		$this->rp->save();
		$this->cr = new Config($this->getDataFolder() ."Cancel-Report.yml", Config::YAML, []);
		$this->cr->save();
	}
	
	public function onJoin(PlayerJoinEvent $ev){
		$player = $ev->getPlayer();
		if($this->rp->exists($player->getName())){
		}
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		switch(strtolower($cmd->getName())){
			case "tocao":
			if(!($sender instanceof Player)){
				$this->getServer()->getLogger()->warning("Please Use In Server!");
				return true;
			}
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(Function (Player $sender, $data){
				
				$result = $data;
				if ($result == null) {
				}
				switch ($result) {
					case 0:
					break;
					case 1:
					$this->onReport($sender);
					break;
					case 2:
					$this->onAdminTools($sender);
					break;
				}
			});
			
			$form->setTitle("§l§e•§c Tố Cáo §e•");
			$form->addButton("§l§e•§c Thoát §e•", 0);
			$form->addButton("§l§e•§c Tố Cáo §e•", 1);
			$form->addButton("§l§e•§c Report Manager §e•", 2);
			$form->sendToPlayer($sender);
		}
		return true;
	}
	
	public function onReport($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(Function (Player $sender, $data){
			switch($data[2]){
				case 0:
				$reason = "SpeedHack";
				break;
				case 1:
				$reason = "Lừa Đảo Scam";
				break;
				case 2:
				$reason = "Không Tôn Trọng Người Chơi";
				break;
				case 3:
				$reason = "Cố Ý Gây War Chửi Người Khác";
				break;
				case 4:
				$reason = "Ý Kiến Của Bạn";
				break;
			}
			$this->rp->set( $sender->getName(), ["Tên" => $data[1], "Lý Do" => $reason, "Lý Do Khác" => $data[3]]);
			$this->rp->save();
			$sender->sendMessage($this->report . "•§aTố Cáo§c ".$data[1]."§a Thành Công!");
			$this->getServer()->getLogger()->info($this->report . "•§a Trường Hợp §c".$reason."§a Của §c".$data[1]."§a Bị báo Cáo Bởi§e ". $sender->getName());
			$sender->sendMessage($this->tag . "•§aĐợi Hệ Thống Xét Duyệt!");
			$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "tell ".strtolower($data[1])." §eYou Have Report From§a ".$sender->getName()."§e With Reason §c".$reason." \ ". $data[3]);
			if(!(isset($data[3]))){
				$sender->sendMessage("•§c [1] §aĐiền Rõ Lý Do Tuỳ Chọn Nếu Có!");
				return true;
			}
		});
		$form->setTitle("§l§f•§9 Tố Cáo §f•");
		$form->addLabel("§l§f•§a Tố cáo người chơi khác");
		$form->addInput("§l§f•§c Tên Người Chơi");
		$form->addDropdown("§l§f•§c Lí do", ["SpeedHack", "Lừa Đảo Scam", "Không Tôn Trong Người Chơi", "Cố Ý Gây War Chửi Người Khác", "Khác"]);
		$form->addInput("§l§f• §cLý Do Khác (Không Bắc Buộc)");
		$form->sendToPlayer($sender);
	}
	
	public  function onAdminTools($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(Function (Player $sender, $data){
			
			$ketqua = $data;
			if ($ketqua == null) {
			}
			switch ($ketqua) {
				case 0:
				$this->onCancelReport($sender);
				break;
				case 1:
				$this->managerReport($sender);
				break;
			}
		});
		$form->setTitle("§l§e•§f Report Manager §e•");
		$form->addButton("§l§e•§f Hủy Tố Cáo §e•", 0);
		$form->addButton("§l§e•§f Lịch Sử Tố Cáo §e•", 1);
		$form->sendToPlayer($sender);
	}
	
	public function onCancelReport($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(Function (Player $sender, $data){
			$this->cr->set( $sender->getName(), ["Tên" => $data[1], "Lý Do Huỷ" => $data[2]]);
			$this->cr->save();
			$sender->sendMessage($this->report . "•§a Huỷ Đơn Tố cáo Của §c".$data[1]."§a Thành Công!");
			$this->rp->remove($sender->getName());
		});
		$form->setTitle("§l§f•§9 Hủy Tố Cáo §f•");
		$form->addLabel("§l§f•§e Hủy Đơn Tố Cáo Nếu Bạn Nhầm Lẫn!");
		$form->addInput("§l§f•§e Tên:");
		$form->addInput("§l§f•§e Lí Do Hủy:");
		$form->sendToPlayer($sender);
	}
	
	public function managerReport($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(Function (Player $sender, $data){
			if($sender->hasPermission("lichsu.admin")){
				$sender->sendMessage("§c");
			}else{
				$sender->sendMessage("§l§f•§e Bạn không có quyền xem lịch sử của admin!");
			}
		});
		$form->setTitle("§l§f•§9 Lịch Sử Tố Cáo §f•");
		$form->addLabel("§fReport #1:");
		$form->addLabel("§l§c•§e Tên Người Chơi:§a ". $this->rp->get("Tên"));
		$form->addLabel("§l§c•§e Lí Do:§a ". $this->rp->get("Lý Do"));
		$form->addLabel("§l§c•§e Lí Do Khác:§a ". $this->rp->get("Lý Do Khác"));
		$form->sendToPlayer($sender);
	}
}