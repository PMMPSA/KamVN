<?php

namespace SonsaYT\TempBanUI;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {
	
	public $staffList = [];
	public $targetPlayer = [];
	
    public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		@mkdir($this->getDataFolder());
		$this->db = new \SQLite3($this->getDataFolder() . "TempBanUI.db");
		$this->db->exec("CREATE TABLE IF NOT EXISTS banPlayers(player TEXT PRIMARY KEY, banTime INT, reason TEXT, staff TEXT);");
		$this->message = (new Config($this->getDataFolder() . "Message.yml", Config::YAML, array(
			"BroadcastBanMessage" => "§e{player} §c đã bị ban bởi Admin §e{staff} §cThời gian hết hạn : §b{day} ngày, {hour} giờ, {minute} phút . §cLí do: §e{reason}",
			"KickBanMessage" => "§c Bạn đã bị ban bởi Admin §e{staff} §c Thời gian hết hạn §b{day} §b ngày, §b{hour} §b giờ, §b{minute} §a phút. \n§c Lí do: §e{reason}",
			"LoginBanMessage" => "§c Bạn đã bị ban . Thời gian hết hạn §b{day} §b Ngày, §b{hour} §b Giờ, §b{minute} §b Phút, §b{second} §b Giây. \n§c Lí do: §e{reason} \n§c Ban bởi Admin: §e{staff}",
			"BanMyself" => "§c Bạn không thể tự ban chính mình!",
			"BanModeOn" => "§aBan mode on",
			"BanModeOff" => "§cBan mode off",
			"NoBanPlayers" => "§eKhông có player bị ban",
			"UnBanPlayer" => "§e{player} §a đã được unban",
			"AutoUnBanPlayer" => "§e{player} §a đã được unban tự động . Thời gian ban đã hết",
			"BanListTitle" => "§9§l« §r§1 Danh Sách Người Bị Ban §9§l»§r",
			"BanListContent" => "§r ",
			"PlayerListTitle" => "§6§l« §r§e Danh Sách Người Chơi §6§l»",
			"PlayerListContent" => "§r ",
			"InfoUIContent" => "§3§lInformations:§r \n§b Ngày : {day} \n§b Giờ : §b{hour} \n§b Phút: §b{minute} \n§b Giây: §b{second} \n§c Lí do : §e{reason} \n§cBanned bởi Admin : §e{staff}\n\n\n\n\n",
			"InfoUIUnBanButton" => "§aUnban Player",
		)))->getAll();
    }
	
    public function onCommand(CommandSender $sender, Command $cmd, string $label,array $args) : bool {
		switch($cmd->getName()){
			case "tempoban":
				if($sender instanceof Player) {
					if($sender->hasPermission("use.tban")){
						if(count($args) == 0){
							$this->openPlayerListUI($sender);
						}
						if(count($args) == 1){
							if($args[0] == "on"){
								if(!isset($this->staffList[$sender->getName()])){
									$this->staffList[$sender->getName()] = $sender;
									$sender->sendMessage($this->message["BanModeOn"]);
								}
							} else if ($args[0] == "off"){
								if(isset($this->staffList[$sender->getName()])){
									unset($this->staffList[$sender->getName()]);
									$sender->sendMessage($this->message["BanModeOff"]);
								}
							} else {
								$this->targetPlayer[$sender->getName()] = $args[0];
								$this->openTbanUI($sender);
							}
						}
					}
				}
				else{
					$sender->sendMessage("Use this Command in-game.");
					return true;
				}
			break;
			case "tempocheck":
				if($sender instanceof Player) {
					if($sender->hasPermission("use.tcheck")){
						$this->openTcheckUI($sender);
					}
				}
			break;
		}
		return true;
    }
	
	public function openPlayerListUI($player){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, $data = null){
			$target = $data;
			if($target === null){
				return true;
			}
			$this->targetPlayer[$player->getName()] = $target;
			$this->openTbanUI($player);
		});
		$form->setTitle($this->message["PlayerListTitle"]);
		$form->setContent($this->message["PlayerListContent"]);
		foreach($this->getServer()->getOnlinePlayers() as $online){
			$form->addButton($online->getName(), -1, "", $online->getName());
		}
		$form->sendToPlayer($player);
		return $form;
	}
	
	public function hitBan(EntityDamageEvent $event){
		if($event instanceof EntityDamageByEntityEvent) {
			$damager = $event->getDamager();
			$victim = $event->getEntity();
			if($damager instanceof Player && $victim instanceof Player){
				if(isset($this->staffList[$damager->getName()])){
					$event->setCancelled(true);
					$this->targetPlayer[$damager->getName()] = $victim->getName();
					$this->openTbanUI($damager);
				}
			}
		}
	}
	
	public function openTbanUI($player){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(function (Player $player, array $data = null){
			$result = $data[0];
			if($result === null){
				return true;
			}
			if(isset($this->targetPlayer[$player->getName()])){
				if($this->targetPlayer[$player->getName()] == $player->getName()){
					$player->sendMessage($this->message["BanMyself"]);
					return true;
				}
				$now = time();
				$day = ($data[1] * 86400);
				$hour = ($data[2] * 3600);
				if($data[3] > 1){
					$min = ($data[3] * 60);
				} else {
					$min = 60;
				}
				$banTime = $now + $day + $hour + $min;
				$banInfo = $this->db->prepare("INSERT OR REPLACE INTO banPlayers (player, banTime, reason, staff) VALUES (:player, :banTime, :reason, :staff);");
				$banInfo->bindValue(":player", $this->targetPlayer[$player->getName()]);
				$banInfo->bindValue(":banTime", $banTime);
				$banInfo->bindValue(":reason", $data[4]);
				$banInfo->bindValue(":staff", $player->getName());
				$banInfo->execute();
				$target = $this->getServer()->getPlayerExact($this->targetPlayer[$player->getName()]);
				if($target instanceof Player){
					$target->kick(str_replace(["{day}", "{hour}", "{minute}", "{reason}", "{staff}"], [$data[1], $data[2], $data[3], $data[4], $player->getName()], $this->message["KickBanMessage"]));
				}
				$this->getServer()->broadcastMessage(str_replace(["{player}", "{day}", "{hour}", "{minute}", "{reason}", "{staff}"], [$this->targetPlayer[$player->getName()], $data[1], $data[2], $data[3], $data[4], $player->getName()], $this->message["BroadcastBanMessage"]));
				unset($this->targetPlayer[$player->getName()]);

			}
		});
		$list[] = $this->targetPlayer[$player->getName()];
		$form->setTitle("§4§l« §r§cBan Người Chơi §4§l»§r");
		$form->addDropdown("\n§cDanh sách người chơi trực tuyến:", $list);
		$form->addSlider("§b•> Ngày", 0, 30);
		$form->addSlider("§b•> Giờ", 0, 24);
		$form->addSlider("§b•> Phút", 0, 60);
		$form->addInput("§e•> Lí do");
		$form->sendToPlayer($player);
		return $form;
	}

	public function openTcheckUI($player){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, $data = null){
			if($data === null){
				return true;
			}
			$this->targetPlayer[$player->getName()] = $data;
			$this->openInfoUI($player);
		});
		$banInfo = $this->db->query("SELECT * FROM banPlayers;");
		$array = $banInfo->fetchArray(SQLITE3_ASSOC);	
		if (empty($array)) {
			$player->sendMessage($this->message["NoBanPlayers"]);
			return true;
		}
		$form->setTitle($this->message["BanListTitle"]);
		$form->setContent($this->message["BanListContent"]);
		$banInfo = $this->db->query("SELECT * FROM banPlayers;");
		$i = -1;
		while ($resultArr = $banInfo->fetchArray(SQLITE3_ASSOC)) {
			$j = $i + 1;
			$banPlayer = $resultArr['player'];
			$form->addButton("§e$banPlayer", -1, "", $banPlayer);
			$i = $i + 1;
		}
		$form->sendToPlayer($player);
		return $form;
	}
	
	public function openInfoUI($player){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createSimpleForm(function (Player $player, int $data = null){
		$result = $data;
		if($result === null){
			return true;
		}
			switch($result){
				case 0:
					$banplayer = $this->targetPlayer[$player->getName()];
					$banInfo = $this->db->query("SELECT * FROM banPlayers WHERE player = '$banplayer';");
					$array = $banInfo->fetchArray(SQLITE3_ASSOC);
					if (!empty($array)) {
						$this->db->query("DELETE FROM banPlayers WHERE player = '$banplayer';");
						$player->sendMessage(str_replace(["{player}"], [$banplayer], $this->message["UnBanPlayer"]));
					}
					unset($this->targetPlayer[$player->getName()]);
				break;
			}
		});
		$banPlayer = $this->targetPlayer[$player->getName()];
		$banInfo = $this->db->query("SELECT * FROM banPlayers WHERE player = '$banPlayer';");
		$array = $banInfo->fetchArray(SQLITE3_ASSOC);
		if (!empty($array)) {
			$banTime = $array['banTime'];
			$reason = $array['reason'];
			$staff = $array['staff'];
			$now = time();
			if($banTime < $now){
				$banplayer = $this->targetPlayer[$player->getName()];
				$banInfo = $this->db->query("SELECT * FROM banPlayers WHERE player = '$banplayer';");
				$array = $banInfo->fetchArray(SQLITE3_ASSOC);
				if (!empty($array)) {
					$this->db->query("DELETE FROM banPlayers WHERE player = '$banplayer';");
					$player->sendMessage(str_replace(["{player}"], [$banplayer], $this->message["AutoUnBanPlayer"]));
				}
				unset($this->targetPlayer[$player->getName()]);
				return true;
			}
			$remainingTime = $banTime - $now;
			$day = floor($remainingTime / 86400);
			$hourSeconds = $remainingTime % 86400;
			$hour = floor($hourSeconds / 3600);
			$minuteSec = $hourSeconds % 3600;
			$minute = floor($minuteSec / 60);
			$remainingSec = $minuteSec % 60;
			$second = ceil($remainingSec);
		}
		$form->setTitle(TextFormat::BOLD . $banPlayer);
		$form->setContent(str_replace(["{day}", "{hour}", "{minute}", "{second}", "{reason}", "{staff}"], [$day, $hour, $minute, $second, $reason, $staff], $this->message["InfoUIContent"]));
		$form->addButton($this->message["InfoUIUnBanButton"]);
		$form->sendToPlayer($player);
		return $form;
	}
	
	public function onPlayerLogin(PlayerPreLoginEvent $event){
		$player = $event->getPlayer();
		$banplayer = $player->getName();
		$banInfo = $this->db->query("SELECT * FROM banPlayers WHERE player = '$banplayer';");
		$array = $banInfo->fetchArray(SQLITE3_ASSOC);
		if (!empty($array)) {
			$banTime = $array['banTime'];
			$reason = $array['reason'];
			$staff = $array['staff'];
			$now = time();
			if($banTime > $now){
				$remainingTime = $banTime - $now;
				$day = floor($remainingTime / 86400);
				$hourSeconds = $remainingTime % 86400;
				$hour = floor($hourSeconds / 3600);
				$minuteSec = $hourSeconds % 3600;
				$minute = floor($minuteSec / 60);
				$remainingSec = $minuteSec % 60;
				$second = ceil($remainingSec);
				$player->close("", str_replace(["{day}", "{hour}", "{minute}", "{second}", "{reason}", "{staff}"], [$day, $hour, $minute, $second, $reason, $staff], $this->message["LoginBanMessage"]));
			} else {
				$this->db->query("DELETE FROM banPlayers WHERE player = '$banplayer';");
			}
		}
		if(isset($this->staffList[$player->getName()])){
			unset($this->staffList[$player->getName()]);
		}
	}

}
