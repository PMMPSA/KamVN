<?php

namespace HuongDan;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use pocketmine\utils\TextFormat as C;

use HuongDan\Main;

class Main extends PluginBase implements Listener {

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onCommand(CommandSender $player, Command $command, string $label, array $args) : bool {
		switch($command->getName()){
			case "thongtin":
			if($player instanceof Player){
			    $this->OpenMenu($player);
			} else {
				$player->sendMessage("§aLệnh này chỉ có thể sử dụng trong trò chơi");
					return true;
			}
			break;
		}
	    return true;
	}

	public function OpenMenu(Player $sender){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
		$result = $data;
		if($result === null){
			return;
		    }
			switch($result){
				case 0:
				break;
			}
		}); 
		$form->setTitle("§l§a♦ §eThông tin §a♦");
		$form->setContent("§l§c Chào Mừng Bạn Đến Với Kam SkyBlock .\n Server Được Tạo bởi Admin VbeeMC .\n Vì Chỉ Có Một Người Làm Nên Server Còn Bé .\nƯớc mơ làm server bao lâu giờ \n mới thành hiện thực .\n Để có server như thế này mình đã đặt rất \n nhiều tiền vào nó chúc mọi \nngười chơi game vui vẻ ");
		$form->addButton("§l§e• §cĐóng §l§e•");
		$form->sendToPlayer($sender);
			return $form;
	}
}