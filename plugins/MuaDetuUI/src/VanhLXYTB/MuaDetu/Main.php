<?php

namespace VanhLXYTB\MuaDetu;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\{command\ConsoleCommandSender, Player, utils\TextFormat};

use jojoe77777\FormAPI;

class Main extends PluginBase implements Listener{

	public function onEnable(): void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->Info("\n\n\nMua Đệ Tử UI by VanhLXYTB\n\n\n");
		$this->coinapi = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		switch($cmd->getName()) {
			case "buydetu":
			if(!($sender instanceof Player)){
                return true;
			}
		$this->Fu1($sender);
		}
		return true;
	}

	
	public function Fu1($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
        $result = $data;
        if ($result == null) {
             }
             switch ($result) {
                 case 1:
				 $coin = $this->coinapi->myCoin($sender);
				 $cost = 50;
				 if($coin >= $cost){
        $id = $sender->getInventory()->getItemInHand()->getId();
     if($id == 0){
					 $this->coinapi->reduceCoin($sender, $cost);
					 $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "givedetu " . $sender->getName());
					 $sender->sendMessage("§aBạn đã mua Đệ Tử thành công");
     }else{
         $sender->sendMessage("§l§c Hãy Để Tay Không (Đừng Cầm Item Nào) Để Mua Đệ Tử");
     }
					 return true;
            }else{
				$sender->sendMessage("§l§c Bạn Không Đủ §6Coin§c Để Mua Đệ Tử");
			}
			break;
                    case 2:
                        break;
            }
        });
		$form->setTitle("§eMua §bĐệ tử");
        $form->setContent("§aBạn có muốn mua §bĐệ Tử§c giá §650 §eCoin");
        $form->setButton1("§l§2Mua Luôn");
        $form->setButton2("§l§cEm Xin Nghĩ Lại");
        $form->sendToPlayer($sender);
	}
}