<?php

namespace Reincarnated;

use pocketmine\{Player, Server};
use pocketmine\command\{Command, CommandSender};
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\event\player\{PlayerJoinEvent};
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\plugin\PluginBase;
use onebone\coinapi\CoinAPI;
use jojoe77777\FormAPI\{
	SimpleForm, CustomForm, ModalForm
};
class Main extends PluginBase implements Listener {

public function onEnable() {
$this->getServer()->getPluginManager()->registerEvents($this, $this);
$this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
$this->coinapi = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
//////////////// Tạo config /////////////////
@mkdir($this->getDataFolder());
        $this->cfg = new Config($this->getDataFolder() . "chuyensinh.yml", Config::YAML);
}
	////////////////// Đặt level 1 chuyển sinh khi vô máy chủ lần đầu //////////////////////////////
 public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        if (!$this->cfg->exists($name)) {
            $this->cfg->set($name, 1);
            $this->cfg->save();
        }
}

public function onBreak(BlockBreakEvent $ev){
  	     $p = $ev->getPlayer();
  $a = $this->myReincarnated($p) * 1000;
  $b = $this->myReincarnated($p) * 100;
  $c = $this->myReincarnated($p) * 1 - 1;
$rand = mt_rand(1, 100);
 if ($this->myReincarnated($p) > 1){ 
switch($rand){ 
case 5: 
$p->sendMessage("§d§l⊹⊱§eKamVN§d⊰⊹  §bBạn Đã Nhận Được§a ".$a."§a$  §bTừ §cChuyển Sinh");
$this->money->addMoney($p, $a);
break;
case 15:
$p->sendMessage("§d§l⊹⊱§eKamVN§d⊰⊹  §bBạn Đã Nhận Được Thêm §c1 Máu§b Từ §cChuyển Sinh");
    $p->setMaxHealth($p->getMaxHealth() + 2);
    $p->addTitle("§l§aBạn Đã Được §c+1 MÁU");
case 20:
$p->sendMessage("§d§l⊹⊱§eKamVN§d⊰⊹  §bBạn Đã Nhận Được§a ".$b."§a$  §bTừ §cChuyển Sinh");
$this->money->addMoney($p, $a);
break;
case 50:
$p->sendMessage("§d§l⊹⊱§eMine§bLight§d⊰⊹  §bBạn Đã Nhận Được§a ".$b."§a$  §bTừ §cChuyển Sinh");
$this->money->addMoney($p, $b);
break;
default:
break;
    }
    }
    }
///////////////////////// Câu lệnh ////////////////////////
public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
	switch($command->getName()) {
		case "chuyensinh":
		 $name = $sender->getName();
            $this->mainform($sender);
}
return true;
}
public function TopUI($sender){
		$all = $this->cfg->getAll();
		$form = new CustomForm(function($sender, $data){
			if(is_null($data)) return true;
		});
		$form->setTitle("§l§2↗§l§2 TOP Chuyển Sinh§2 ↖");
		arsort($all);
		$i = 1;
		foreach($all as $name => $money){
			$form->addLabel("§l§eHạng ".$i."§e Thuộc Về §b".$name."§f Với Cấp Chuyển Sinh §c".$money."\n");
			if($i >= 10) break;
			++$i;
		}
		$form->sendToPlayer($sender);
	}
   public function mainform($player){
        $n = $player->getName();
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }
            switch($result){                
                    case "0";
        // Fix Bởi Nguyễn Công Danh (Danh Miner) Và Master Jero.
        $money = $this->money->myMoney($player);
        if($money < $this->myReincarnated($player->getName()) * 800000){
            $player->sendMessage("§e§l⊹⊱§dChuyển Sinh§e⊰⊹  §c⇀ §bBạn Không Đủ  Để Lên Cấp Tiếp Theo");
             $player->sendMessage("§e§l⊹⊱§dChuyển Sinh§e⊰⊹  §c⇀ §cBạn Cần Phải Cày Thêm Nha§b ".$player->getName()."");
            $player->sendMessage("§e§l⊹⊱§dChuyển Sinh§e⊰⊹  §bSố  Để Lên Cấp Tiếp Theo Là ". $this->myReincarnated($player) * 800000 ."");
        } else {
            $cs = $this->myReincarnated($player->getName());
            $money = $cs*800000;
            $this->money->reduceMoney($player, $money);
              $this->cfg->set($player->getName(), (int)$this->cfg->get($player->getName()) + 1);
            $player->sendMessage("§l§b Bạn Vừa Chuyển Sinh Thành Công  Cấp§c ". $this->myReincarnated($player->getName()) ."!");
              $this->cfg->save();
        }          
                    break;  
                    case "1";               
                     $this->TopUI($player);
                    break;
                    default:
                    break;
            }
            }); 
            $money = $this->cfg->get($player->getName());
            $form->setTitle("§l§d Menu Chuyển Sinh");
            $form->setContent("§l§c⊱ §bCấp độ Chuyển Sinh: §c$money");
            $form->addButton("§l§f§2 Lên Cấp Chuyển Sinh §f\n§l§7 NHẤN ĐỂ LÊN CẤP CHUYỂN SINH");
            $form->addButton("§l§f§2 TOP Chuyển Sinh §f\n§l§7 NHẤN ĐỂ XEM TOP CHUYỂN SINH");
            $form->addButton("§l§f⩕§c THOÁT §f⩕\n§l§7 NHẤN ĐỂ THOÁT");
            $form->sendToPlayer($player);
            return $form;
    }


public function myReincarnated($player) {
if($player instanceof Player) {
$player = $player->getName();
	}
$reincarnated = $this->cfg->get($player);
return $reincarnated;
    }
    public function addLevel($player, $cfg){
     if($player instanceof Player){
         $player = $player->getName();
         }
         $this->cfg = new Config($this->getDataFolder()."config.yml",Config::YAML);
         $this->cfg->set($player, (int)$this->cfg->get($player) + $cfg);
         $this->cfg->save();
    }
}