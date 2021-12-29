<?php

namespace CH;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\PlayerJoinEvent;
use onebone\economyapi\EconomyAPI;
use jojoe77777\FormAPI;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\level\sound\PopSound;
use pocketmine\level\sound\GhastSound;

Class Main extends PluginBase implements Listener{
	
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->getLogger()->info("Cường Hóa Đã Được Bật");
        @mkdir($this->getDataFolder(), 0744, true);
       $this->m = new Config($this->getDataFolder()."max.yml",Config::YAML);
}
    public function onJoin(PlayerJoinEvent $ev) {
        if(!$this->m->exists($ev->getPlayer()->getName())) {
            $this->m->set($ev->getPlayer()->getName(), 5);
            $this->m->save();
         }
    }
	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "cuonghoa":
                $this->menu($sender);
           return true;
        }
        return true;
	}
                
    public function menu($sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
        $result = $data;
        if ($result == null) {
             }
             switch ($result) {
              case 0:
              break;
              case 1:
              $inv = $sender->getInventory()->getItemInHand();
              $id = $inv->getId();                   
              $cus = $sender->getInventory()->getItemInHand()->getCustomName();
              $pas = explode(" ", $cus);
	          if($pas[0] == "§l§c|§a"){
                  return;
              }
              if($id ==0 ){
                $sender->getLevel()->addSound(new GhastSound($sender));
                $sender->sendMessage("§l§c【 §fKam §c】§a Bạn có thể cường hóa vật phẩm §eCúp, Rìu, Kiếm, Mũ, Áo, Quần, Giày");
                  return;
              }   
              if($this->eco->myMoney($sender) < 50000){
                $sender->getLevel()->addSound(new GhastSound($sender));
                $sender->sendMessage("§l§c【 §fKam §c】§c Bạn không đủ tiền để cường hóa vật phẩm này");
                return;
              }
              //Witch 2
              switch($id){
              case 278:
                $f = $inv->getEnchantmentLevel(Enchantment::FORTUNE) + 1;
                $e = $inv->getEnchantmentLevel(Enchantment::EFFICIENCY) + 1; 
                $u = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($f > $this->m->get($sender->getName())){
                  $sender->sendMessage(" §l§c【 §fKam §c】§c Đã cường hóa max!");  
                  return;  
                }
            //id Enchant
                $item = Item::get($id, 0, 1);
			    $item->setLore(array("§l§c•§e Cúp đã cường hóa"));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $e));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $u));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->getLevel()->addSound(new AnvilUseSound($sender));
$sender->sendMessage("§l§c【 §fKam §c】§a Chúc mừng bạn §e".$sender->getName()."§a Đã Nâng Cấp Cúp Lên §c+".$f);
                $this->eco->reduceMoney($sender, 50000);
              break;
              case 257:
                $f = $inv->getEnchantmentLevel(Enchantment::FORTUNE) + 1;
                $e = $inv->getEnchantmentLevel(Enchantment::EFFICIENCY) + 1; 
                $u = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($f > $this->m->get($sender->getName())){
                  $sender->sendMessage(" §l§c【 §fKam §c】§c Đã cường hóa max!");  
                  return;  
                }
            //id Enchant
                $item = Item::get($id, 0, 1);
			    $item->setLore(array("§l§c•§e Cúp đã cường hóa"));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $e));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $u));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->getLevel()->addSound(new AnvilUseSound($sender));
$sender->sendMessage("§l§c【 §fKam §c】§a Chúc mừng bạn §e".$sender->getName()."§a Đã Nâng Cấp Cúp Lên §c+".$f);
                $this->eco->reduceMoney($sender, 50000);
              break;
              case 270:
                $f = $inv->getEnchantmentLevel(Enchantment::FORTUNE) + 1;
                $e = $inv->getEnchantmentLevel(Enchantment::EFFICIENCY) + 1; 
                $u = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($f > $this->m->get($sender->getName())){
                  $sender->sendMessage(" §l§c【 §fKam §c】§c Đã cường hóa max!");  
                  return;  
                }
            //id Enchant
                $item = Item::get($id, 0, 1);
			    $item->setLore(array("§l§c•§e Cúp đã cường hóa"));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $e));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $u));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->getLevel()->addSound(new AnvilUseSound($sender));
$sender->sendMessage("§l§c【 §fKam §c】§a Chúc mừng bạn §e".$sender->getName()."§a Đã Nâng Cấp Cúp Lên §c+".$f);
                $this->eco->reduceMoney($sender, 50000);
              break;
              case 274:
                $f = $inv->getEnchantmentLevel(Enchantment::FORTUNE) + 1;
                $e = $inv->getEnchantmentLevel(Enchantment::EFFICIENCY) + 1; 
                $u = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($f > $this->m->get($sender->getName())){
                  $sender->sendMessage(" §l§c【 §fKam §c】§c Đã cường hóa max!");  
                  return;  
                }
            //id Enchant
                $item = Item::get($id, 0, 1);
			    $item->setLore(array("§l§c•§e Cúp đã cường hóa"));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $e));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $u));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->getLevel()->addSound(new AnvilUseSound($sender));
$sender->sendMessage("§l§c【 §fKam §c】§a Chúc mừng bạn §e".$sender->getName()."§a Đã Nâng Cấp Cúp Lên §c+".$f);
                $this->eco->reduceMoney($sender, 50000);
              break;
              case 285:
                $f = $inv->getEnchantmentLevel(Enchantment::FORTUNE) + 1;
                $e = $inv->getEnchantmentLevel(Enchantment::EFFICIENCY) + 1; 
                $u = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($f > $this->m->get($sender->getName())){
                  $sender->sendMessage(" §l§c【 §fKam §c】§c Đã cường hóa max!");  
                  return;  
                }
            //id Enchant
                $item = Item::get($id, 0, 1);
			    $item->setLore(array("§l§c•§e Cúp đã cường hóa"));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $e));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $u));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->getLevel()->addSound(new AnvilUseSound($sender));
$sender->sendMessage("§l§c【 §fKam §c】§a Chúc mừng bạn §e".$sender->getName()."§a Đã Nâng Cấp Cúp Lên §c+".$f);
                $this->eco->reduceMoney($sender, 50000);
              break;
              case 745:
                $f = $inv->getEnchantmentLevel(Enchantment::FORTUNE) + 1;
                $e = $inv->getEnchantmentLevel(Enchantment::EFFICIENCY) + 1; 
                $u = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($f > $this->m->get($sender->getName())){
                  $sender->sendMessage(" §l§c【 §fKam §c】§c Đã cường hóa max!");  
                  return;  
                }
            //id Enchant
                $item = Item::get($id, 0, 1);
			    $item->setLore(array("§l§c•§e Cúp đã cường hóa"));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $e));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $u));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->getLevel()->addSound(new AnvilUseSound($sender));
$sender->sendMessage("§l§c【 §fKam §c】§a Chúc mừng bạn §e".$sender->getName()."§a Đã Nâng Cấp Cúp Lên §c+".$f);
                $this->eco->reduceMoney($sender, 50000);
              break;
              case 258:
                $f = $inv->getEnchantmentLevel(Enchantment::FORTUNE) + 1;
                $e = $inv->getEnchantmentLevel(Enchantment::EFFICIENCY) + 1; 
                $u = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($f > $this->m->get($sender->getName())){
                  $sender->sendMessage(" §l§c【 §fKam §c】§c Đã cường hóa max!");  
                  return;  
                }
            //id Enchant
                $item = Item::get($id, 0, 1);
			    $item->setLore(array("§l§c•§e Rìu đã cường hóa"));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $e));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $u));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->getLevel()->addSound(new AnvilUseSound($sender));
$sender->sendMessage("§l§c【 §fKam §c】§a Chúc mừng bạn §e".$sender->getName()."§a Đã Nâng Cấp Rìu Lên §c+".$f);
                $this->eco->reduceMoney($sender, 50000);
              break;
              case 271:
                $f = $inv->getEnchantmentLevel(Enchantment::FORTUNE) + 1;
                $e = $inv->getEnchantmentLevel(Enchantment::EFFICIENCY) + 1; 
                $u = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($f > $this->m->get($sender->getName())){
                  $sender->sendMessage(" §l§c【 §fKam §c】§c Đã cường hóa max!");  
                  return;  
                }
            //id Enchant
                $item = Item::get($id, 0, 1);
			    $item->setLore(array("§l§c•§e Rìu đã cường hóa"));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $e));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $u));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->getLevel()->addSound(new AnvilUseSound($sender));
$sender->sendMessage("§l§c【 §fKam §c】§a Chúc mừng bạn §e".$sender->getName()."§a Đã Nâng Cấp Rìu Lên §c+".$f);
                $this->eco->reduceMoney($sender, 50000);
              break;
              case 275:
                $f = $inv->getEnchantmentLevel(Enchantment::FORTUNE) + 1;
                $e = $inv->getEnchantmentLevel(Enchantment::EFFICIENCY) + 1; 
                $u = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($f > $this->m->get($sender->getName())){
                  $sender->sendMessage(" §l§c【 §fKam §c】§c Đã cường hóa max!");  
                  return;  
                }
            //id Enchant
                $item = Item::get($id, 0, 1);
			    $item->setLore(array("§l§c•§e Rìu đã cường hóa"));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $e));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $u));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->getLevel()->addSound(new AnvilUseSound($sender));
$sender->sendMessage("§l§c【 §fKam §c】§a Chúc mừng bạn §e".$sender->getName()."§a Đã Nâng Cấp Rìu Lên §c+".$f);
                $this->eco->reduceMoney($sender, 50000);
              break;
              case 279:
                $f = $inv->getEnchantmentLevel(Enchantment::FORTUNE) + 1;
                $e = $inv->getEnchantmentLevel(Enchantment::EFFICIENCY) + 1; 
                $u = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($f > $this->m->get($sender->getName())){
                  $sender->sendMessage(" §l§c【 §fKam §c】§c Đã cường hóa max!");  
                  return;  
                }
            //id Enchant
                $item = Item::get($id, 0, 1);
			    $item->setLore(array("§l§c•§e Rìu đã cường hóa"));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $e));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $u));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->getLevel()->addSound(new AnvilUseSound($sender));
$sender->sendMessage("§l§c【 §fKam §c】§a Chúc mừng bạn §e".$sender->getName()."§a Đã Nâng Cấp Rìu Lên §c+".$f);
                $this->eco->reduceMoney($sender, 50000);
              break;
              case 286:
                $f = $inv->getEnchantmentLevel(Enchantment::FORTUNE) + 1;
                $e = $inv->getEnchantmentLevel(Enchantment::EFFICIENCY) + 1; 
                $u = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($f > $this->m->get($sender->getName())){
                  $sender->sendMessage(" §l§c【 §fKam §c】§c Đã cường hóa max!");  
                  return;  
                }
            //id Enchant
                $item = Item::get($id, 0, 1);
			    $item->setLore(array("§l§c•§e Rìu đã cường hóa"));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $e));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $u));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->getLevel()->addSound(new AnvilUseSound($sender));
$sender->sendMessage("§l§c【 §fKam §c】§a Chúc mừng bạn §e".$sender->getName()."§a Đã Nâng Cấp Rìu Lên §c+".$f);
                $this->eco->reduceMoney($sender, 50000);
              break;
              case 746:
                $f = $inv->getEnchantmentLevel(Enchantment::FORTUNE) + 1;
                $e = $inv->getEnchantmentLevel(Enchantment::EFFICIENCY) + 1; 
                $u = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($f > $this->m->get($sender->getName())){
                  $sender->sendMessage(" §l§c【 §fKam §c】§c Đã cường hóa max!");  
                  return;  
                }
            //id Enchant
                $item = Item::get($id, 0, 1);
			    $item->setLore(array("§l§c•§e Rìu đã cường hóa"));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15), $e));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $u));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->getLevel()->addSound(new AnvilUseSound($sender));
$sender->sendMessage("§l§c【 §fKam §c】§a Chúc mừng bạn §e".$sender->getName()."§a Đã Nâng Cấp Rìu Lên §c+".$f);
                $this->eco->reduceMoney($sender, 50000);
              break;
              case 276:
                $ec1 = $inv->getEnchantmentLevel(Enchantment::SHARPNESS) + 1;
                $ec2 = $inv->getEnchantmentLevel(Enchantment::FIRE_ASPECT) + 1; 
                $ec3 = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                $item = Item::get($id, 0, 1);
                if($ec1 > $this->m->get($sender->getName())){
                   $sender->sendMessage("§l§§eGiới Hạn Cấp");   
                   return;  
                }
              	$item->setCustomName("§l§bKiếm Thần Thụ | + ".$ec1);
			    $item->setLore(array("§l§bVũ Khí Được thần Thụ +".$ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9), $ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(13), $ec2));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $ec3));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->sendMessage("§a§lChúc Mừng §f".$sender->getName()."§a Đã Nâng Cấp Kiếm Lên §c+".$ec1);                 $this->eco->reduceMoney($sender, 50000);
              break;    
              case 310:
                $ec1 = $inv->getEnchantmentLevel(Enchantment::PROTECTION) + 1;
                $ec2 = $inv->getEnchantmentLevel(Enchantment::THORNS) + 1; 
                $ec3 = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($ec1 > $this->m->get($sender->getName())){
                   $sender->sendMessage("Giới Hạn Cấp");   
                   return;  
                } 
                $item = Item::get($id, 0, 1);
                            			                $item->setCustomName("§l§bMủ Thần Thụ | + ".$ec1);
			    $item->setLore(array("§l§bVũ Khí Được thần Thụ +".$ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), $ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(5), $ec2));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $ec3));
                $sender->getInventory()->setItemInHand($item);
            	$sender->sendMessage("§a§lChúc Mừng §f".$sender->getName()."§a Đã Nâng Cấp Nón Lên §c+".$ec1);                 $this->eco->reduceMoney($sender, 50000);           
              break;
              case 311:
                $ec1 = $inv->getEnchantmentLevel(Enchantment::PROTECTION) + 1;
                $ec2 = $inv->getEnchantmentLevel(Enchantment::THORNS) + 1; 
                $ec3 = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($ec1 > $this->m->get($sender->getName())){
                   $sender->sendMessage("§l§eGiới Hạn Cấp");   
                   return;  
                }
                $item = Item::get($id, 0, 1);
                $item->setCustomName("§l§bÁo Thần Thụ | + ".$ec1);
			    $item->setLore(array("§l§bVũ Khí Được thần Thụ +".$ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), $ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(5), $ec2));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $ec3));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->sendMessage("§a§lChúc Mừng §f".$sender->getName()."§a Đã Nâng Cấp Áo Lên §c+".$ec1);                  $this->eco->reduceMoney($sender, 50000);
    	      break;  
              case 312:
                $ec1 = $inv->getEnchantmentLevel(Enchantment::PROTECTION) + 1;
                $ec2 = $inv->getEnchantmentLevel(Enchantment::THORNS) + 1; 
                $ec3 = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($ec1 > $this->m->get($sender->getName())){
                  $sender->sendMessage("§l§eGiới Hạn Cấp");   
                  return;  
                }
                $item = Item::get($id, 0, 1);
        		$item->setCustomName("§l§bQuần Thần Thụ | + ".$ec1);
			    $item->setLore(array("§l§bVũ Khí Được thần Thụ +".$ec1));       
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), $ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(5), $ec2));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $ec3));
                $sender->getInventory()->setItemInHand($item);
             	$sender->sendMessage("§a§lChúc Mừng §f".$sender->getName()."§a Đã Nâng Cấp Quần Lên §c+".$ec1);                 $this->eco->reduceMoney($sender, 50000);
              break;
              case 313:
                $ec1 = $inv->getEnchantmentLevel(Enchantment::PROTECTION) + 1;
                $ec2 = $inv->getEnchantmentLevel(Enchantment::THORNS) + 1; 
                $ec3 = $inv-> getEnchantmentLevel(Enchantment::UNBREAKING) + 1;
                if($ec1 > $this->m->get($sender->getName())){
                  $sender->sendMessage("§l§eGiới Hạn Cấp");
                  return;  
                }
                $item = Item::get($id, 0, 1);
        		$item->setCustomName("§l§bDép Thần Thụ | + ".$ec1);
			    $item->setLore(array("§l§bVũ Khí Được thần Thụ +".$ec1));                  $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0), $ec1));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(5), $ec2));
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17), $ec3));
                $sender->getInventory()->setItemInHand($item);
    	        $sender->sendMessage("§l§c【 §fKam §c】§a Chúc mừng bạn §e".$sender->getName()."§a đã cường hóa lên cấp:§e ".$ec1);                 $this->eco->reduceMoney($sender, 50000);    
              break;
              case 2:
              break;
              }}
        });
        $sender->getLevel()->addSound(new PopSound($sender));
        $money = EconomyAPI::getInstance()->myMoney($sender);
        $m = $this->m->get($sender->getName());
        $form->setTitle("§l§e•§a Cường Hóa §e•");
        $form->setContent("§l§c↣§a Tiền của bạn:§e ".$money."\n§l§c↣§a Vật Phẩm:§e ".$sender->getInventory()->getItemInHand()->getName()."\n§l§6❖§e Bạn có đồng ý cường hóa vật phẩm này với giá §c50.000Xu §ekhông?");
        $form->setButton1("§l§e•§a Đồng Ý §e•");
        $form->setButton2("§l§e•§c Không §e•");
        $form->sendToPlayer($sender);
	}
}
