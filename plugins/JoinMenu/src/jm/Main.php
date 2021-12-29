<?php

namespace jm;

use pocketmine\{Player, Server};
use pocketmine\command\{Command, CommandSender};
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {
	
	public $playerList = [];
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
	}
	
	public function onCommand(CommandSender $player, Command $cmd, string $label, array $args) : bool {
		if ($cmd->getName() === "§k§k§j§j§j§k§b§b") {
			$form = $this->formapi->createSimpleForm(function (Player $player, int $data = null) {
			$result = $data;if ($result === null) { return true; }
			switch ($result) {
				case 0:
				$form = $this->formapi->createSimpleForm(function (Player $player, int $data = null) {
					$result = $data;if ($result === null) { return true; }switch ($result) {
case 0: 
$cmd1 = "§k§k§j§j§j§k§b§b";
					           $this->getServer()->getCommandMap()->dispatch($player, $cmd1);
break;
					}});
                     $form->setTitle("§l§f•§c Lệnh Cơ Bản §f•");
                     $form->setContent("§l§f•§b Lệnh Cơ Bản Của Máy Chủ:\n\n§l§f-§e /sbui :§a Hệ Thống SkyBlock\n§l§f-§e /shop:§a Hệ Thống Shop\n§l§f-§e /choden:§a Hệ Thống Chợ Đen\n§l§f-§e /ah:§a Hệ Thống Đấu Giá\n§l§f-§e /quandoan:§a Hệ Thống Quân Đoàn\n§l§f-§e /danhgia:§a Hệ Thống Đánh Giá\n§l§f-§e /nhiemvu:§a Hệ Thống Nhiệm Vụ\n§l§f-§e /kit:§a Hệ Thống Kit\n§l§f-§e /rank:§a Hệ Thống Rank\n§l§f-§e /cay:§a Hệ Thống Cây Phát Tài");
                     $form->addButton("§l§c•§a Quay Lại §c•");
                     $form->sendToPlayer($player);
				break;case 1:
				$cmd2 = "menu";
					           $this->getServer()->getCommandMap()->dispatch($player, $cmd2);
				break;case 2:
				$cmd3 = "thongtin";
					           $this->getServer()->getCommandMap()->dispatch($player, $cmd3);
				break;case 3:
			}
		});
		$form->setTitle("§l§f§e • §cKam VN§e • §f");
		$form->setContent("§l§f §c".$player->getName()."\n\n§l§6 SkyBlock ❇§e §7(§aKamVN§7)\n\n§l§c Website:§f bit.do/kamvn");
		$form->addButton("§l§f• §cLệnh Cơ Bản §r §l§f•");
		$form->addButton("§l§f• §cMenu Server §r §l§f•");
		$form->addButton("§l§f• §cThông tin máy chủ §r §l§f•");
		$form->sendToPlayer($player);
		}
		return true;
	}
}