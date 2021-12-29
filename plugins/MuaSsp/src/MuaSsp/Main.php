<?php

namespace MuaSsp;

use pocketmine\{Player, Server};
use pocketmine\plugin\PluginBase;
use pocketmine\utils\{TextFormat};
use pocketmine\event\Listener;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use jojoe77777\FormAPI;
use onebone\coinapi\CoinAPI;

class Main extends PluginBase implements Listener{
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$this->coin = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		$player = $sender->getPlayer();
		switch ($cmd->getName()){
			case "muassp":
			if(!($sender instanceof Player)){
				$this->getLogger()->notice("Dùng lệnh trong game.");
				return true;
			}
			$this->mainForm($player);
			break;
		}
		return true;
    }
    
	public function mainForm($player){

		$form = $this->formapi->createSimpleForm(function (Player $player, $result){

			if($result === null){
				return;
			}
			switch($result){
				case 0:
				    if($player->hasPermission("royalPass.Tungsten.Perm")){
				       $player->sendMessage("§l§a【Ｓeason Ｐass】 §r§cBạn đã mua Royal Pass rồi");
				       return;
				    }
			        if($this->coin->myCoin($player) >= 50){
				        $player->sendMessage("§l§a【Ｓeason Ｐass】 §r§eBạn đã mua thành công §6Royal Pass.");
				        $this->coin->reduceCoin($player, 50);
				        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm " .  $player->getName() . " royalPass.Tungsten.Perm");
			        }else{
			          $player->sendMessage("§l§a【Ｓeason Ｐass】 §r§cBạn cần §6500 COIN§c để mua Royal Pass này!");
			        }
				break;
				
		}
		});
		$form->setTitle("§l【 MUA THẺ HUYỀN THOẠI MÙA I 】");
		$form->setContent("\n§l§7➣ Mua thẻ huyền thoại để nhận đặc quyền dùng thẻ huyền thoại ở §6/seasonpass\n\n");
		$form->addButton("§lKÍCH HOẠT ROYAL PASS\n§l§8GIÁ: 50 COIN");
		$form->sendToPlayer($player);
		
	}
 
}