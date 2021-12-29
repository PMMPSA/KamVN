<?php

namespace tichluy;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};

class Main extends PluginBase implements Listener {
    
    
public function onEnable() {
		$this->getLogger()->info("Tích Lũy Bật");
$this->getServer()->getPluginManager()->registerEvents($this ,$this);
@mkdir($this->getDataFolder(), 0744, true);
		$this->coin = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
		$this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        $this->d = new Config($this->getDataFolder()."diem.yml",Config::YAML);
        $this->bcoin = new Config($this->getDataFolder()."bcoin.yml",Config::YAML);
        $this->ecoin = new Config($this->getDataFolder()."ecoin.yml",Config::YAML);
        $this->onb = new Config($this->getDataFolder()."onb.yml",Config::YAML);
        $this->one = new Config($this->getDataFolder()."one.yml",Config::YAML);
        $this->at = new Config($this->getDataFolder()."autosell.yml",Config::YAML);
        $this->atfix = new Config($this->getDataFolder()."autofix.yml",Config::YAML);
}

   public function onJoin(PlayerJoinEvent $ev) {
     if(!$this->d->exists($ev->getPlayer()->getName())) {
     $this->d->set($ev->getPlayer()->getName(), 0);
     $this->d->save();
    }
     if(!$this->bcoin->exists($ev->getPlayer()->getName())) {
     $this->bcoin->set($ev->getPlayer()->getName(), 0);
     $this->bcoin->save();
    }
 
     if(!$this->ecoin->exists($ev->getPlayer()->getName())) {
     $this->ecoin->set($ev->getPlayer()->getName(), 0);
     $this->ecoin->save();
    }
    
     if(!$this->onb->exists($ev->getPlayer()->getName())) {
     $this->onb->set($ev->getPlayer()->getName(), 0);
     $this->onb->save();
    }
  
     if(!$this->one->exists($ev->getPlayer()->getName())) {
     $this->one->set($ev->getPlayer()->getName(), 0);
     $this->one->save();
    }
     if(!$this->at->exists($ev->getPlayer()->getName())) {
     $this->at->set($ev->getPlayer()->getName(), 0);
     $this->at->save();
    }
     if(!$this->atfix->exists($ev->getPlayer()->getName())) {
     $this->atfix->set($ev->getPlayer()->getName(), 0);
     $this->atfix->save();
    }
}

    public function onQuit(PlayerQuitEvent $ev) {
   $this->d->save();
   $this->bcoin->save();
   $this->ecoin->save();
   $this->onb->save();
   $this->one->save();
   $this->at->save();
   $this->atfix->save();
    }
    
    //Kiếm Bcoin
 
public function onbreak(BlockBreakEvent $ev) {
    if($this->onb->get($ev->getPlayer()->getName()) == 1) {
$this->bcoin->set($ev->getPlayer()->getName(), ($this->bcoin->get($ev->getPlayer()->getName()) + 1));
$this->bcoin->save();
        }
    if($this->one->get($ev->getPlayer()->getName()) == 1) {
$this->d->set($ev->getPlayer()->getName(), ($this->d->get($ev->getPlayer()->getName()) + 1));
$this->d->save();
    if($this->d->get($ev->getPlayer()->getName()) == 50){
$this->ecoin->set($ev->getPlayer()->getName(), ($this->ecoin->get($ev->getPlayer()->getName()) + 1));
$this->ecoin->save();
    }
        }
        //Autosel 
         $at = $this->at->get($ev->getPlayer()->getName());
        if($at == 1){
		$player = $ev->getPlayer();
		foreach($ev->getDrops() as $drop) {
			if(!$player->getInventory()->canAddItem($drop)) {
				$ev->getPlayer()->addTitle("§l§a✠§6 Full Inventory §a✠", "§l§bTự động bán!");
                $this->getServer()->getCommandMap()->dispatch($player,"sell all");

            }
            

				break; 
			}
		}
	    //autofix
         $atfix = $this->atfix->get($ev->getPlayer()->getName());
        if($atfix == 1){
		$player = $ev->getPlayer();
		$item = $player->getInventory()->getItemInHand();
		$damage = $item->getDamage();
		if($damage > 500){
		    ///Lệnh /fix
                $this->getServer()->getCommandMap()->dispatch($player,"fix");
		}

		}
    }
    
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "tichluy":
            $this->tl($sender);
            return true;
        }
        return true;
	}
	
    public function tl($sender){
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(Function (Player $sender, $data){
				
				$result = $data;
				if ($result == null){
				}
				switch ($result) {
					case 0:
					$sender->sendMessage("");
					break;
					case 1:
					$this->kiemdiem($sender);
					break;
					case 2:
					$this->menu($sender);
					break;
					case 3:
					$this->shopbcoin($sender);
					break;
				}
			});
	$ecoin = $this->ecoin->get($sender->getPlayer()->getName());
	$bcoin = $this->bcoin->get($sender->getPlayer()->getName());
			$form->setTitle("§l§9⚫§a Hệ Thống Tích Lũy §9⚫");
			$form->setContent("§l§6•§b Bcoin:§e ".$bcoin."\n§6§l• §bEcoin: §e".$ecoin);
			
			$form->addButton("§l§e•§c Thoát §e•");
			$form->addButton("§l§e•§a Kiếm Điểm §e•");
			$form->addButton("§l§e•§a Đổi Tích Lủy §e•");
			$form->addButton("§l§e•§a Shop Bcoin §e•");
			$form->sendToPlayer($sender);
}

	 public function shopbcoin($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
              case 1:
                $this->autofix($sender);
              break;
          }
                  
        });
	$bcoin = $this->bcoin->get($sender->getPlayer()->getName());
        $form->setTitle("§l§9⚫§a Shop Bcoin §9⚫");
			$form->setContent("§l§6•§b Bcoin: §e".$bcoin);
		$form->addButton("§l§e•§c Thoát §e•");
		$form->addButton("§l§e•§a Buy AutoFix §e•");
        $form->sendToPlayer($sender);
	}
	
	 public function autosell($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
              case 1:
          	$at = $this->at->get($sender->getName());
        if($at == 0){          
          	$bcoin = $this->bcoin->get($sender->getPlayer()->getName());
          	if($bcoin >= 50000){
                $this->at->set($sender->getName(), 1);
                $this->at->save();
                $this->bcoin->set($sender->getName(), ($this->bcoin->get($sender->getName()) - 50000));
                $this->bcoin->save();
	$sender->sendMessage("§l§e•§a Bạn Đã Mua Autosell Thành Công..!");
          	}else{
			     $sender->sendMessage("§l§e•§c Bạn Không Đủ Bcoin Để Mua Autosell");
          	}
        }elseif($at > 0){
			$sender->sendMessage("§l§e•§c Bạn Đã Mua Auto Sell Rồi");
        }  	
              break;
          }
                  
        });
	$bcoin = $this->bcoin->get($sender->getPlayer()->getName());
        $form->setTitle("§l§9⚫§a Auto Sell §9⚫");
			$form->setContent("§l§6•§b Bcoin:§e ".$bcoin."\n§l§6↣ §aBạn Có Đồng Ý Mua Auto Sell Với Giá 50 Bcoin?");
		$form->addButton("§l§e•§c Thoát §e•");
		$form->addButton("§l§e•§a Đồng Ý §e•");
        $form->sendToPlayer($sender);
	}
	
	 public function autofix($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
              case 1:
          	$atfix = $this->atfix->get($sender->getName());
        if($atfix == 0){          
          	$bcoin = $this->bcoin->get($sender->getPlayer()->getName());
          	if($bcoin >= 50000){
                $this->atfix->set($sender->getName(), 1);
                $this->atfix->save();
                $this->bcoin->set($sender->getName(), ($this->bcoin->get($sender->getName()) - 50000));
                $this->bcoin->save();
	$sender->sendMessage("§l§6•§a Bạn Đã Mua Auto Fix Thành Công");
          	}else{
			     $sender->sendMessage("§l§6• §cBạn Không Đủ Bcoin Để Mua Auto Fix ");
          	}
        }elseif($atfix > 0){
			$sender->sendMessage("§l§6•§c Bạn Đã Mua Auto Fix Rồi");
        }  	
              break;
          }
                  
        });
	$bcoin = $this->bcoin->get($sender->getPlayer()->getName());
        $form->setTitle("§l§9⚫§a Auto Fix §9⚫");
			$form->setContent("§l§6• §bBcoin:§e ".$bcoin."\n§6§l↣§a Bạn Có Đồng Ý Mua Auto fix Với Giá 50 Bcoin");
		$form->addButton("§l§e•§c Thoát §e•");
		$form->addButton("§l§e•§a Đồng Ý §e•");
        $form->sendToPlayer($sender);
	}
	
	 public function kiemdiem($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
              case 1:
     if($this->onb->get($sender->getPlayer()->getName()) == 1) {
     $this->onb->set($sender->getPlayer()->getName(), 0);
			$sender->sendMessage("§l§eKiếm Bcoin §f[§c Tắt §f]");
}elseif($this->onb->get($sender->getPlayer()->getName()) == 0) {
     $this->onb->set($sender->getPlayer()->getName(), 1);
			$sender->sendMessage("§l§eKiếm Bcoin §f[§a Bật §f]");
     }
     if($this->one->get($sender->getPlayer()->getName()) == 1) {
     $this->one->set($sender->getPlayer()->getName(), 0);
     }
              break;
              case 2:
     if($this->one->get($sender->getPlayer()->getName()) == 1) {
     $this->one->set($sender->getPlayer()->getName(), 0);
			$sender->sendMessage("§l§eKiếm Bcoin §f[§c Tắt §f]");
}elseif($this->one->get($sender->getPlayer()->getName()) == 0) {
     $this->one->set($sender->getPlayer()->getName(), 1);
			$sender->sendMessage("§l§eKiếm Bcoin §f[§a Bật §f]");
     }
     if($this->onb->get($sender->getPlayer()->getName()) == 1) {
     $this->onb->set($sender->getPlayer()->getName(), 0);
     }
              break;

         }
        });
     if($this->one->get($sender->getPlayer()->getName()) == 0) {
     $of2 = "§cTắt";
    }elseif($this->one->get($sender->getPlayer()->getName()) == 1) {
     $of2 = "§aBật";
     }
     if($this->onb->get($sender->getPlayer()->getName()) == 0) {
     $of1 = "§cTắt";
    }elseif($this->onb->get($sender->getPlayer()->getName()) == 1) {
     $of1 = "§aBật";
     }
        $form->setTitle("§l§9⚫§a Kiếm Điểm §9⚫");
		$form->setContent("§l§6• §bBcoin:§e Đập 1 Block = 1 Bcoin\n§l§6•§b Ecoin: §eĐập 100 Block = 1 Ecoin");
		$form->addButton("§l§e•§c Thoát §e•");
		$form->addButton("§l§e•§b Kiếm Bcoin §f[§f".$of1."§f] §e•");
		$form->addButton("§l§e• §bKiếm Ecoin §f[§f".$of2."§f] §e•");
        $form->sendToPlayer($sender);
	}

    public function menu($sender){
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(Function (Player $sender, $data){
				
				$result = $data;
				if ($result == null){
				}
				switch ($result) {
					case 0:
					break;
					case 1:
					$this->Bcoin($sender);
					break;
					case 2:
					$this->Ecoin($sender);
					break;
				}
			});
			
			$form->setTitle("§l§9⚫§a Đổi Tích Lũy §9⚫");
			$form->setContent("§l§6•§b Hãy chọn 1 trong 3 ô này..!");
			$form->addButton("§l§e•§c Thoát §e•");
			$form->addButton("§l§e•§a Đổi Xu §e•");
			$form->addButton("§l§e•§a Đổi Coin §e•");
 
			$form->sendToPlayer($sender);
	}
	
	public function Ecoin($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(function (Player $sender, $data){
			if($data == null) return;
			if(!(is_numeric($data[1]))){
				$sender->sendMessage("§l§cPhải là số..!");
				return true;
			}
			$tien = $this->ecoin->get($sender->getName());
			if($tien >= $data[1]*1000){
				$sender->sendMessage("§l§c【 §fKam §c】 §bBạn đã đổi thành công§e " . $data[1] . "§b Coin");

     $this->ecoin->set($sender->getPlayer()->getName(), ($this->ecoin->get($sender->getPlayer()->getName()) - $data[1]*1000));
     $this->ecoin->save();
				$this->coin->addCoin($sender, $data[1]);
			}else{
				$sender->sendMessage("§l§c【 §fKam §c】§c Bạn không đủ §aEcoin§c để đổi Coin");
				return true;
			}
		});
	$ecoin = $this->ecoin->get($sender->getPlayer()->getName());
		$form->setTitle("§l§9⚫§a Đổi Coin §9⚫");
			$form->addLabel("§l§6•§b Ecoin:§e ".$ecoin."\n§l§6•§b 1000 Ecoin = 1 Coin");
		$form->addInput("§l§6•§a Nhập số coin bạn muốn:");
		$form->sendToPlayer($sender);
	}
	
	public function Bcoin($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(function (Player $sender, $data){
			if($data == null) return;
			if(!(is_numeric($data[1]))){
				$sender->sendMessage("§l§cPhải là số..!");
				return true;
			}
		    $data[1] >= 1;
			$tien = $this->bcoin->get($sender->getName());
			if($tien >= $data[1]){
				$sender->sendMessage("§l§c【 §fKam §c】 §bBạn đã đổi thành công§e " . $data[1] . "§b Xu");
     $this->bcoin->set($sender->getPlayer()->getName(), ($this->bcoin->get($sender->getPlayer()->getName()) - $data[1]));
     $this->bcoin->save();
				$this->money->addMoney($sender, $data[1]);
			}else{
				$sender->sendMessage("§l§c【 §fKam §c】§c Bạn không đủ Bcoin để Đổi Xu");
				return true;
			}
		});
	$bcoin = $this->bcoin->get($sender->getPlayer()->getName());
		$form->setTitle("§l§9⚫§a Đổi Xu §9⚫");
			$form->addLabel("§l§6•§b Bcoin: §e".$bcoin."\n§l§6• §b1 Bcoin = 1 Xu");
		$form->addInput("§l§6•§a Nhập số xu bạn muốn:");
		$form->sendToPlayer($sender);
	}
	
	
	}