<?php

namespace RankShopSystem;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\Sound;
use pocketmine\math\Vector3;
use jojoe77777\FormAPI;
use onebone\coinapi\CoinAPI;
use pocketmine\Player;
use pocketmine\Server;
use RankShopSystem\Main;

class Main extends PluginBase implements Listener {
    
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->coinapi = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
		$this->getLogger()->notice("§bRankShop§eSystem §esuccessfully enabled. §aBy zZPROGAMERZz423");
		
		@mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->getResource("config.yml");
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args):bool
    {
        switch($cmd->getName()){
        case "muarank":
        if(!$sender instanceof Player){
                $sender->sendMessage("§cThis command can't be used here!.");
                return true;
        }
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 0:
                    $sender->sendMessage("§l§aCảm Ơn Bạn Đã Sử Dụng Mua Rank ! ");
                        break;
                    case 1:
                    $this->group1($sender);

                        break;
                    case 2:
                    $this->group2($sender);
               
                        break;
                    case 3:
                    $this->group3($sender);
       
                        break;
                    case 4:
                    $this->group4($sender);
 
                        break;
                    case 5:
                    $this->group5($sender);
            
                        break;                    
                    case 6:
                    $this->group6($sender);
            
                        break;
                    case 7:
                    $this->group7($sender);

                        break;
                    case 8:
                    $this->group8($sender);
           
                        break;
            }
        });
      $form->setTitle($this->getConfig()->get("title.muarank"));
      
        $form->addButton("§cThoát", 0);
         $form->addButton($this->getConfig()->get("group1.name"), 1);
          $form->addButton($this->getConfig()->get("group2.name"), 2);
			$form->addButton($this->getConfig()->get("group3.name"), 3);
			$form->addButton($this->getConfig()->get("group4.name"), 4);
			$form->addButton($this->getConfig()->get("group5.name"), 5);
			$form->addButton($this->getConfig()->get("group6.name"), 6);
			$form->addButton($this->getConfig()->get("group7.name"), 7);
			$form->addButton($this->getConfig()->get("group8.name"), 8);
            $form->sendToPlayer($sender);
        }
        return true;
    }
    public function group1($sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 1:
            $coins = $this->coinapi->myCoin($sender);
            $cost = $this->getConfig()->get("group1.cost");
            if($coins >= $cost){

               $this->coinapi->reduceCoin($sender, $cost);	
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " VipI 3");               
              $sender->getLevel()->addSound(new EndermanTeleportSound($sender));
               $sender->sendMessage($this->getConfig()->get("group1.complete"));
		    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "hub");
		    $sender->sendMessage($this->getConfig()->get("othermsg1complete.txt"));
		    $this->vipfeatures($sender);
		    $sender->addTitle($this->getConfig()->get("purchase1.title"));
              return true;
            }else{
               $sender->sendMessage($this->getConfig()->get("group1.failed"));
               $sender->getLevel()->addSound(new AnvilFallSound($sender));
               $sender->sendMessage("§cBạn không đủ §ecoin§c để mua!!!!");
		    $sender->sendMessage($this->getConfig()->get("othermsg1fail.txt"));
            }
                        break;
                    case 2:
               $sender->sendMessage("§cBạn không thể mua gói này");
                        break;
            }
        });
        $form->setTitle($this->getConfig()->get("group1.name"));
$form->setContent($this->getConfig()->get("group1.info")); 
        $form->setButton1("§l§aMua", 1);
        $form->setButton2("§l§cKhông mua", 2);
        $form->sendToPlayer($sender);
    }
	
	      
	public function vipfeatures($sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 1:
               $sender->sendMessage("§aBạn đã mua gói§l§c Vιᴘ§eI");

            }
        });
        $form->setTitle("§e Thông Báo");
$form->setContent($this->getConfig()->get("group1.features"));
        $form->setButton1("§l§aTiếp tục", 1);
        $form->sendToPlayer($sender);
    }
			    
			    public function translateMessage($scut, $message) {
    $message = str_replace($scut."{name}", $sender->getName(), $message);
			 return $message;
			 }
	
	public function vipplusfeatures($sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 1:
               $sender->sendMessage("§aBạn đã mua gói§l§c Vιᴘ§eII");
                        break;
            }
        });
        $form->setTitle("§e Thông Báo");
$form->setContent($this->getConfig()->get("group2.features"));
        $form->setButton1("§l§aTiếp tục", 1);
        $form->sendToPlayer($sender);
    }
	
	public function mvpfeatures($sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 1:
               $sender->sendMessage("§aBạn đã mua gói§l§c Vιᴘ§eIII");
                        break;
            }
        });
        $form->setTitle("§e Thông Báo");
$form->setContent($this->getConfig()->get("group3.features"));
        $form->setButton1("§l§aTiếp tục", 1);
        $form->sendToPlayer($sender);
    }
	
	public function mvpplusfeatures($sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 1:
               $sender->sendMessage("§aBạn đã mua gói §l§cVιᴘ§eIV");
                        break;
            }
        });
        $form->setTitle("§e Thông Báo");
$form->setContent($this->getConfig()->get("group4.features"));
        $form->setButton1("§l§aTiếp tục");
        $form->sendToPlayer($sender);
    }
	
	public function goatfeatures($sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 1:
               $sender->sendMessage("§aBạn đã mua gói §l§cVιᴘ§eV");
                        break;
            }
        });
        $form->setTitle("§bFeatures");
$form->setContent($this->getConfig()->get("group5.features"));
        $form->setButton1("§l§aTiếp tục", 1);
        $form->sendToPlayer($sender);
    }
    
    public function group2($sender){
    
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 1:
            $coins = $this->coinapi->myCoin($sender);
            $cost = $this->getConfig()->get("group2.cost");
            if($coins >= $cost){

               $this->coinapi->reduceCoin($sender, $cost);
               $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " VipII 5");
               $sender->sendMessage($this->getConfig()->get("group2.complete"));
		    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "hub");
		    $this->vipplusfeatures($sender);
		    $sender->sendMessage($this->getConfig()->get("othermsg2complete.txt"));
		    $sender->addTitle($this->getConfig()->get("purchase2.title"));
				      
              return true;
            }else{
               $sender->sendMessage($this->getConfig()->get("group2.failed"));
               $sender->sendMessage("§cBạn không đủ §ecoin §cđể mua gói này");
		    $sender->sendMessage($this->getConfig()->get("othermsg2fail.txt"));
            }
                        break;
                    case 2:
               $sender->sendMessage("§cBạn không thể mua rank");
			    ;
                        break;
            }
        });
        $form->setTitle($this->getConfig()->get("group2.name")); 
        $form->setContent($this->getConfig()->get("group2.info"));
        $form->setButton1("§l§aMua", 1);
        $form->setButton2("§l§cKhông mua", 2);
        $form->sendToPlayer($sender);
    }
    
    public function group3($sender){  
      
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 1:
            $coins = $this->coinapi->myCoin($sender);
            $cost = $this->getConfig()->get("group3.cost");
            if($coins >= $cost){

               $this->coinapi->reduceCoin($sender, $cost);
          $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " VipIII 7");
               $sender->sendMessage($this->getConfig()->get("group3.complete"));
		    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "hub");
		    $this->mvpfeatures($sender);
		    $sender->sendMessage($this->getConfig()->get("othermsg3complete.txt"));
		    $sender->addTitle($this->getConfig()->get("purchase3.title"));
              return true;
            }else{
               $sender->sendMessage($this->getConfig()->get("group3.failed"));
               $sender->sendMessage("§cBạn không đủ §ecoin §cđể mua gói này");
		    $sender->sendMessage($this->getConfig()->get("othermsg3fail.txt"));
            }
                        break;
                    case 2:
               $sender->sendMessage("§cBạn không thể mua rank");
                        break;
            }
        });
        $form->setTitle($this->getConfig()->get("group3.name"));
        $form->setContent($this->getConfig()->get("group3.info"));
        $form->setButton1("§l§aMua", 1);
        $form->setButton2("§l§cKhông mua", 2);
        $form->sendToPlayer($sender);
   }
   
        public function group4($sender){
    
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 1:
            $coins = $this->coinapi->myCoin($sender);
            $cost = $this->getConfig()->get("group4.cost");
            if($coins >= $cost){

               $this->coinapi->reduceCoin($sender, $cost);
               $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " VipIV 10");
               $sender->sendMessage($this->getConfig()->get("group4.complete"));
		    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "hub");
		    $this->mvpplusfeatures($sender);
		    $sender->sendMessage($this->getConfig()->get("othermsg4complete.txt"));
		    $sender->addTitle($this->getConfig()->get("purchase4.title"));
              return true;
            }else{
               $sender->sendMessage($this->getConfig()->get("group4.failed"));;
               $sender->sendMessage("§cBạn không đủ §ecoin §cđể mua gói này");
		    $sender->sendMessage($this->getConfig()->get("othermsg4fail.txt"));
            }
                        break;
                    case 2:
               $sender->sendMessage("§cKhông thể mua rank");
                        break;
            }
        });
        $form->setTitle($this->getConfig()->get("group4.name"));
        $form->setContent($this->getConfig()->get("group4.info"));
        $form->setButton1("§l§aMua", 1);
        $form->setButton2("§l§cKhông mua", 2);
        $form->sendToPlayer($sender);
     }
     
       public function group5($sender){
    
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 1:
            $coins = $this->coinapi->myCoin($sender);
            $cost = $this->getConfig()->get("group5.cost");
            if($coins >= $cost){

               $this->coinapi->reduceCoin($sender, $cost);
		    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " VipV 15");
               $sender->sendMessage($this->getConfig()->get("group5.complete"));
		    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "hub");
		    $this->goatfeatures($sender);
		    $sender->sendMessage($this->getConfig()->get("othermsg5complete.txt"));
		    $sender->addTitle($this->getConfig()->get("purchase5.title"));
              return true;
            }else{
               $sender->sendMessage($this->getConfig()->get("group5.failed"));
               $sender->sendMessage("§cBạn không đủ §ecoin §cđể mua gói này");
		    $sender->sendMessage($this->getConfig()->get("othermsg5fail.txt"));
            }
                        break;
                    case 2:
               $sender->sendMessage("§cYou cancelled buying the rank");
                        break;
            }
        });
        $form->setTitle($this->getConfig()->get("group5.name"));
        $form->setContent($this->getConfig()->get("group5.info"));
        $form->setButton1("§l§aMua", 1);
        $form->setButton2("§l§cKhông mua", 2);
        $form->sendToPlayer($sender);
      }
      
     public function group6($sender){
    
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 1:
            $coins = $this->coinapi->myCoin($sender);
            $cost = $this->getConfig()->get("group6.cost");
            if($coins >= $cost){

               $this->coinapi->reduceCoin($sender, $cost);
		    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " VipVI 20");
               $sender->sendMessage($this->getConfig()->get("group6.complete"));
		    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "hub");
		    $this->goatfeatures($sender);
		    $sender->sendMessage($this->getConfig()->get("othermsg6complete.txt"));
		    $sender->addTitle($this->getConfig()->get("purchase6.title"));
              return true;
            }else{
               $sender->sendMessage($this->getConfig()->get("group6.failed"));
               $sender->sendMessage("§cBạn không đủ §ecoin §cđể mua gói này");
		    $sender->sendMessage($this->getConfig()->get("othermsg6fail.txt"));
            }
                        break;
                    case 2:
               $sender->sendMessage("§cYou cancelled buying the rank");
                        break;
            }
        });
        $form->setTitle($this->getConfig()->get("group6.name"));
        $form->setContent($this->getConfig()->get("group6.info"));
        $form->setButton1("§l§aMua", 1);
        $form->setButton2("§l§cKhông mua", 2);
        $form->sendToPlayer($sender);
      }
      
      public function group7($sender){
    
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 1:
            $coins = $this->coinapi->myCoin($sender);
            $cost = $this->getConfig()->get("group7.cost");
            if($coins >= $cost){

               $this->coinapi->reduceCoin($sender, $cost);
		    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " VipVII 25");
               $sender->sendMessage($this->getConfig()->get("group7.complete"));
		    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "hub");
		    $this->goatfeatures($sender);
		    $sender->sendMessage($this->getConfig()->get("othermsg7complete.txt"));
		    $sender->addTitle($this->getConfig()->get("purchase7.title"));
              return true;
            }else{
               $sender->sendMessage($this->getConfig()->get("group7.failed"));
               $sender->sendMessage("§cBạn không đủ §ecoin §cđể mua gói này");
		    $sender->sendMessage($this->getConfig()->get("othermsg7fail.txt"));
            }
                        break;
                    case 2:
               $sender->sendMessage("§cYou cancelled buying the rank");
                        break;
            }
        });
        $form->setTitle($this->getConfig()->get("group7.name"));
        $form->setContent($this->getConfig()->get("group7.info"));
        $form->setButton1("§l§aMua", 1);
        $form->setButton2("§l§cKhông mua", 2);
        $form->sendToPlayer($sender);
      }
      
      public function group8($sender){
    
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 1:
            $coins = $this->coinapi->myCoin($sender);
            $cost = $this->getConfig()->get("group8.cost");
            if($coins >= $cost){

               $this->coinapi->reduceCoin($sender, $cost);
		    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip " .  $sender->getName() . " VipVIII 30");
               $sender->sendMessage($this->getConfig()->get("group8.complete"));
		    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "hub");
		    $this->goatfeatures($sender);
		    $sender->sendMessage($this->getConfig()->get("othermsg8complete.txt"));
		    $sender->addTitle($this->getConfig()->get("purchase8.title"));
              return true;
            }else{
               $sender->sendMessage($this->getConfig()->get("group8.failed"));
               $sender->sendMessage("§cBạn không đủ §ecoin §cđể mua gói này");
		    $sender->sendMessage($this->getConfig()->get("othermsg8fail.txt"));
            }
                        break;
                    case 2:
               $sender->sendMessage("§cYou cancelled buying the rank");
                        break;
            }
        });
        $form->setTitle($this->getConfig()->get("group8.name"));
        $form->setContent($this->getConfig()->get("group8.info"));
        $form->setButton1("§l§aMua", 1);
        $form->setButton2("§l§cKhông mua", 2);
        $form->sendToPlayer($sender);
      }
	
	public function processor(Player $player, string $string): string{		$string = str_replace("{name}", $player->getName(), $string);
	return $string;
	}

}
