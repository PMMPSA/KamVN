<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\block\BlockFactory;
use pocketmine\Player;

use RedCraftPE\RedSkyBlock\Commands\Island;
use RedCraftPE\RedSkyBlock\Tasks\Generate;
use RedCraftPE\RedSkyBlock\Blocks\Lava;

use jojoe77777\FormAPI\{SimpleForm, CustomForm, ModalForm};
use onebone\coinapi\CoinAPI;

class SkyBlock extends PluginBase {
	
	public $NCDPrefix = "§f→§c[§aSkyBlock§c] §f=>> ";
	
  private $eventListener;

  private static $instance;

  private $island;

  public function onEnable(): void {
$this->coinapi = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
    if ($this->cfg->get("SkyBlockWorld") === "") {

      $this->getLogger()->info("§l§cSkyBlock §e↣ §aĐể plugin này hoạt động bình thường, bạn phải đặt thế giới SkyBlock trong máy chủ của mình. (In order for this plugin to function properly, you must set a SkyBlock world in your server).");
      $this->level = null;
    } else {

      $this->level = $this->getServer()->getLevelByName($this->cfg->get("SkyBlockWorld"));
      if (!($this->getServer()->isLevelLoaded($this->cfg->get("SkyBlockWorld")))) {

        if ($this->getServer()->loadLevel($this->cfg->get("SkyBlockWorld"))) {

          $this->getServer()->loadLevel($this->cfg->get("SkyBlockWorld"));
          $this->level = $this->getServer()->getLevelByName($this->cfg->get("SkyBlockWorld"));
          $this->getLogger()->info("§l§cSkyBlock §e↣ §a SkyBlock is running on the world {$this->level->getFolderName()}");
        } else {

          $this->getLogger()->info("§l§cSkyBlock §e↣ §c The level currently set as the SkyBlock world does not exist.");
          $this->level = null;
        }
      } else {

        if ($this->getServer()->isLevelLoaded($this->level->getFolderName())) {

           $this->getLogger()->info(TextFormat::GREEN . "SkyBlock is running on level {$this->level->getFolderName()}");
        } else {

          $this->getServer()->loadLevel($this->level->getFolderName());
          $this->getLogger()->info(TextFormat::GREEN . "SkyBlock is running on level {$this->level->getFolderName()}");
        }
      }
    }
    $this->eventListener = new EventListener($this, $this->level);
    $this->island = new Island($this);
    self::$instance = $this;
  }
  public function onLoad(): void {

		BlockFactory::registerBlock(new Lava(), true);

    if (!is_dir($this->getDataFolder())) {

      @mkdir($this->getDataFolder());
    }
    if (!file_exists($this->getDataFolder() . "skyblock.yml")) {

      $this->saveResource("skyblock.yml");
      $this->skyblock = new Config($this->getDataFolder() . "skyblock.yml", Config::YAML);
    } else {

      $this->skyblock = new Config($this->getDataFolder() . "skyblock.yml", Config::YAML);
    }
    if (!file_exists($this->getDataFolder() . "config.yml")) {

      $this->saveResource("config.yml");
      $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    } else {

      $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }

    if (!$this->cfg->exists("PVP")) {

      $this->cfg->set("PVP", "off");
      $this->cfg->save();
    }

    $this->cfg->reload();
    $this->skyblock->reload();
  }
  public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {

    switch(strtolower($command->getName())) {

      case "island":

        return $this->island->onIslandCommand($sender, $command, $label, $args);
      break;
    }
    return false;
  }

  //API FUNCTIONS:
  public static function getInstance(): self {

    return self::$instance;
  }
  public function calcRank(string $name): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $users = [];

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    foreach(array_keys($skyblockArray) as $user) {

      $userValue = $skyblockArray[$user]["Value"];
      $users[$user] = $userValue;
    }

    arsort($users);
    $rank = array_search($name, array_keys($users)) + 1;

    return strval($rank);
  }
  public function getIslandName(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    return $skyblockArray[$name]["Name"];
  }
  public function getMembers(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    return implode(", ", $skyblockArray[$name]["Members"]);
  }
  public function getValue(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    return strval($skyblockArray[$name]["Value"]);
  }
  public function getBanned(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    return implode(", ", $skyblockArray[$name]["Banned"]);
  }
  public function getLockedStatus(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    if ($skyblockArray[$name]["Locked"]) {

      return "Yes";
    } else {

      return "No";
    }
  }
  public function getSize(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    $startX = intval($skyblockArray[$name]["Area"]["start"]["X"]);
    $startZ = intval($skyblockArray[$name]["Area"]["start"]["Z"]);
    $endX = intval($skyblockArray[$name]["Area"]["end"]["X"]);
    $endZ = intval($skyblockArray[$name]["Area"]["end"]["Z"]);

    $length = $endX - $startX;
    $width = $endZ - $startZ;

    return "{$length} x {$width}";
  }
	
	# CODE FORM BY NGUYỄN CÔNG DANH (NCD)
	public function NCDMenuForm(Player $player, string $text) {
		$form = new SimpleForm(function (Player $player, ?int $data = null) {
			$result = $data;
			if ($result === null) {
				return;
			}
			switch ($result) {
				case 0:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdcreate");
				break;
				case 1:
				$this->NCDWarpForm($player, "");
				break;
				case 2:
				$this->NCDSettingsForm($player);
				break;
				case 3:
				$this->NCDMoRongForm($player, "");
				break;
				case 4:
				$this->NCDInfoForm($player, "");
				break;
				case 5:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdtop");
				break;
			}
		});
		$form->setTitle("§l§cSkyBlock");
		$form->setContent($text."§l§c↣ §eXếp hạng đảo của bạn: §f" . $this->getValue($player));
		$form->addButton("§l§e• §cVào đảo §e•");
		$form->addButton("§l§e• §cĐến đảo người khác §e•");
		$form->addButton("§l§e• §cQuản lí đảo §e•");
		$form->addButton("§l§e• §cMở rộng đảo §e•");
		$form->addButton("§l§e• §cTra cứu đảo §e•");
		$form->addButton("§l§e• §cTop đảo §e•");
		$form->sendToPlayer($player);
		return $form;
	}
	
	public function NCDWarpForm($player, string $text)
	{
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDMenuForm($player, "");
				return false;
			}
			if (empty($data[1])) {
				$this->NCDMenuForm($player, "");
				return true;
			}
			$this->getServer()->getCommandMap()->dispatch($player, "is ncdtp " . $data[1]);
			return false;
		});
		$form->setTitle("§l§b♦ §cĐến đảo người khác §b♦");
		$form->addLabel($text);
		$form->addInput("§l§c↣ §aNhập tên người chơi:", "Nhập tên người chơi vào đây");
		$form->sendToPlayer($player);
	}
	
	public function NCDInfoForm($player, string $text)
	{
		$list = [];
		foreach ($this->getServer()->getOnlinePlayers() as $p) {
			$list[] = $p->getName();
		}
		$this->playerList[$player->getName()] = $list;
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDMenuForm($player, "");
				return false;
			}
			$index = $data[1];
			$playerName = $this->playerList[$player->getName()][$index];
			if ($playerName instanceof Player) {
			}
			$this->getServer()->getCommandMap()->dispatch($player, "is ncdinfo " . $playerName);
			return false;
		});
		$form->setTitle("§l§b♦ §cTra cứu đảo §b♦");
		$form->addLabel($text);
		$form->addDropdown("§l§c↣ §aChọn người chơi:", $this->playerList[$player->getName()]);
		$form->sendToPlayer($player);
	}
	
	public function NCDMoRongForm(Player $player, $text) {
		$form = new SimpleForm(function (Player $player, ?int $data = null) {
			$result = $data;
			if ($result === null) {
				$this->NCDMenuForm($player, "");
				return;
			}
			switch ($result) {
				case 0:
				$coin = $this->coinapi->myCoin($player);
				if ($coin >= 150) {
					$this->coinapi->reduceCoin($sender, $cost);
					$this->NCDMoRongForm($player, "§c↣ §aBạn đã mở rộng đảo được 25 block\n");
				} else {
					$this->NCDMoRongForm($player, "§c↣ §fBạn không đủ coin để mở rộng đảo\n");
				}
				break;
				case 1:
				$coin = $this->coinapi->myCoin($player);
				if ($coin >= 300) {
					$this->coinapi->reduceCoin($sender, $cost);
					$this->NCDMoRongForm($player, "§c↣ §aBạn đã mở rộng đảo được 50 block\n");
				} else {
					$this->NCDMoRongForm($player, "§c↣ §fBạn không đủ coin để mở rộng đảo\n");
				}
				break;
			}
		});
		$coin = $this->coinapi->myCoin($player);
		$form->setTitle("§l§b♦ §cMở rộng đảo §b♦");
		$form->setContent($text."§l§c↣ §eYour Coin : §f".$coin);
		$form->addButton("§l§e• §c25 Block §9- §c150 Coin §e•");
		$form->addButton("§l§e• §c50 Block §9- §c300 Coin §e•");
		$form->sendToPlayer($player);
		return $form;
	}
	
	# Settings Form By Nguyễn Công Danh (NCD)
	public function NCDSettingsForm(Player $player) {
		$form = new SimpleForm(function (Player $player, ?int $data = null) {
			$result = $data;
			if ($result === null) {
				$this->NCDMenuForm($player, "");
				return;
			}
			switch ($result) {
				case 0:
				$this->NCDReNameForm($player, "");
				break;
				case 1:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdsetspawn");
				break;
				case 2:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdlock");
				break;
				case 3:
				$this->NCDAddRemoveForm($player, "");
				break;
				case 4:
				$this->NCDKickForm($player, "");
				break;
				case 5:
				$this->NCDBanUnBanForm($player, "");
				break;
				case 6:
				$this->NCDSettingSkyBlock($player, "");
				break;
			}
		});
		$form->setTitle("§l§cSetting SkyBlock");
		$form->setContent("§l§c↣ §eMembers: §f" . $this->getMembers($player));
		$form->addButton("§l§e• §cĐổi tên §e•");
		$form->addButton("§l§e• §cĐặt chỗ hồi sinh của đảo §e•");
		$form->addButton("§l§e• §cKhoá/Mở khóa đảo §e•");
		$form->addButton("§l§e• §cThêm/Xóa thành viên §e•");
		$form->addButton("§l§e• §cKick người chơi §e•");
		$form->addButton("§l§e• §cCấm/Hủy cấm người chơi §e•");
		$form->addButton("§l§e• §cCài đặt đảo §e•");
		$form->sendToPlayer($player);
		return $form;
	}
	
	public function NCDReNameForm($player, string $text)
	{
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDSettingsForm($player);
				return false;
			}
			if (empty($data[1])) {
				$this->NCDSettingsForm($player);
				return true;
			}
			$this->getServer()->getCommandMap()->dispatch($player, "is ncdrename " . $data[1]);
			return false;
		});
		$form->setTitle("§l§b♦ §cĐổi tên đảo §b♦");
		$form->addLabel($text);
		$form->addInput("§l§c↣ §aNhập tên:", "Nhập tên đảo mới vào đây");
		$form->sendToPlayer($player);
	}
	
	public function NCDAddRemoveForm($player, string $text)
	{
		$list = [];
		foreach ($this->getServer()->getOnlinePlayers() as $p) {
			$list[] = $p->getName();
		}
		$this->playerList[$player->getName()] = $list;
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDSettingsForm($player);
				return false;
			}
			$index = $data[1];
			$playerName = $this->playerList[$player->getName()][$index];
			if ($playerName instanceof Player) {
			}
			switch ($data[2]) {
				case 0:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdadd " . $playerName);
				break;
				case 1:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdremove " . $playerName);
				break;
			}
			return false;
		});
		$form->setTitle("§l§b♦ §cThêm/Xóa thành viên §b♦");
		$form->addLabel($text."§l§c↣ §eThành viên: §f" . $this->getMembers($player) . "\n");
		$form->addDropdown("§l§c↣ §aChọn người chơi:", $this->playerList[$player->getName()]);
		$form->addToggle("§l§c↣ §aGạt sang để xóa");
		$form->sendToPlayer($player);
	}
	
	public function NCDKickForm($player, string $text)
	{
		$list = [];
		foreach ($this->getServer()->getOnlinePlayers() as $p) {
			$list[] = $p->getName();
		}
		$this->playerList[$player->getName()] = $list;
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDSettingsForm($player);
				return false;
			}
			$index = $data[1];
			$playerName = $this->playerList[$player->getName()][$index];
			if ($playerName instanceof Player) {
			}
			$this->getServer()->getCommandMap()->dispatch($player, "is kick " . $playerName);
			return false;
		});
		$form->setTitle("§l§b♦ §cKick thành viên §b♦");
		$form->addLabel($text);
		$form->addDropdown("§l§c↣ §aChọn người chơi:", $this->playerList[$player->getName()]);
		$form->sendToPlayer($player);
	}
	
	public function NCDBanUnBanForm($player, string $text)
	{
		$list = [];
		foreach ($this->getServer()->getOnlinePlayers() as $p) {
			$list[] = $p->getName();
		}
		$this->playerList[$player->getName()] = $list;
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDSettingsForm($player);
				return false;
			}
			$index = $data[1];
			$playerName = $this->playerList[$player->getName()][$index];
			if ($playerName instanceof Player) {
			}
			switch ($data[2]) {
				case 0:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdban " . $playerName);
				break;
				case 1:
				$this->getServer()->getCommandMap()->dispatch($player, "is ncdunban " . $playerName);
				break;
			}
			return false;
		});
		$form->setTitle("§l§b♦ §cCấm/Bỏ cấm thành viên §b♦");
		$form->addLabel($text."§l§c↣ §eDanh sách bị cấm: §f" . $this->getBanned($player) . "\n");
		$form->addDropdown("§l§c↣ §aChọn người chơi:", $this->playerList[$player->getName()]);
		$form->addToggle("§l§c↣ §aGạt sang để bỏ cấm");
		$form->sendToPlayer($player);
	}
	
	public function NCDSettingSkyBlock($player, string $text)
	{
		$form = new CustomForm(function(Player $player, $data) {
			$result = $data;
			if ($result === null) {
				$this->NCDSettingsForm($player);
				return false;
			}
			switch ($data[1]) {
				case 0:
				$name = strtolower($player->getName());
				$skyblockArray = $this->skyblock->get("SkyBlock", []);
				$skyblockArray[$name]["Settings"]["PVP"] = "off";
				$this->skyblock->set("SkyBlock", $skyblockArray);
				$this->skyblock->save();
				break;
				case 1:
				$name = strtolower($player->getName());
				$skyblockArray = $this->skyblock->get("SkyBlock", []);
				$skyblockArray[$name]["Settings"]["PVP"] = "on";
				$this->skyblock->set("SkyBlock", $skyblockArray);
				$this->skyblock->save();
				
				break;
			}
			switch ($data[2]) {
				case 0:
				$name = strtolower($player->getName());
				$skyblockArray = $this->skyblock->get("SkyBlock", []);
				$skyblockArray[$name]["Settings"]["Pickup"] = "off";
				$this->skyblock->set("SkyBlock", $skyblockArray);
				$this->skyblock->save();
				$this->NCDSettingSkyBlock($player, "§l§c↣ §aCài đặt đảo của bạn đã được cập nhật!\n");
				break;
				case 1:
				$name = strtolower($player->getName());
				$skyblockArray = $this->skyblock->get("SkyBlock", []);
				$skyblockArray[$name]["Settings"]["Pickup"] = "on";
				$this->skyblock->set("SkyBlock", $skyblockArray);
				$this->skyblock->save();
				$this->NCDSettingSkyBlock($player, "§l§c↣ §aCài đặt đảo của bạn đã được cập nhật!\n");
				break;
			}
			return false;
		});
		$name = strtolower($player->getName());
		$form->setTitle("§l§b♦ §cCài đặt đảo §b♦");
		$form->addLabel($text."\n§l§c↣ §aGạt sang bên trái để tắt.\n");
		$skyblockArray = $this->skyblock->get("SkyBlock", []);
		if ($skyblockArray[$name]["Settings"]["PVP"] === "on") {
			$form->addToggle("§l§c↣ §eTắt/Bật PvP", true);
		} else {
			$form->addToggle("§l§c↣ §eTắt/Bật PvP", false);
		}
		if ($skyblockArray[$name]["Settings"]["Pickup"] === "on") {
			$form->addToggle("§l§c↣ §eTắt/Bật Pickup Protection", true);
		} else {
			$form->addToggle("§l§c↣ §eTắt/Bật Pickup Protection", false);
		}
		$form->sendToPlayer($player);
	}
}