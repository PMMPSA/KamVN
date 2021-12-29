<?php
declare(strict_types=1);
namespace SizePLayer;
use pocketmine\{
  Server, Player
};
use pocketmine\command\{
  Command, CommandSender
};
use pocketmine\utils\TextFormat as TF;
use pocketmine\entity\Entity;
  
class SizePLayerCommand extends Command {
    
    /** var Plugin */
    private $plugin;
  
    public function __construct($plugin) {
        $this->plugin = $plugin;
        parent::__construct("size", "Đổi kích thước");
    }
    
    public function execute(CommandSender $player, string $label, array $args){
        if(!$player instanceof Player){
			$player->sendMessage(TF::RED."This command only works in-game");
			return;
		}
        if($player->hasPermission("size.command")) {
            if(isset($args[0])) {
                if(is_numeric($args[0])) {
                    if($args[0] > 3) {
                      $player->sendMessage(TF::RED. "§l§c【 §fKam§c】§c Không được lớn hơn size §e3");
                      return true;
                    }elseif($args[0] <= 0) {
                      $player->sendMessage(TF::RED. "§l§c【 §fKam §c】§c Size này không được nhỏ hơn hoặc bằng §e0");
                      return true;
                    }
                    $this->plugin->size[$player->getName()] = $args[0];
                    $player->setScale((float)$args[0]);
                    $player->sendMessage("§l§c【 §fKam §c】§b Bạn đã đổi thành size §e".TF::GOLD . $args[0]."§b.");
                }elseif($args[0] == "reset") {
                    if(!empty($this->plugin->size[$player->getName()])) {
                        unset($this->plugin->size[$player->getName()]);
                        $player->setScale(1);
                        $player->sendMessage("§l§c【 §fKam §c】§b Bạn đã thay đổi lại kích thước ban đầu.");
                    }else{
                        $player->sendMessage("§l§c【 §fKam §c】§b Bạn đã đặt lại kích thước của mình.");
                    }
                }else{
                    $player->sendMessage("§l§c【 §fKam §c】§b Sử dụng: §6/size§f (§d Số Size §f)§a Để thay đổi kích thước\n§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Sử dụng: §6/size reset §aĐể đổi lại kích thước ban đầu");
                }
            } else {
              $player->sendMessage(TF::RED. "§l§c【 §fKam §c】§c Size không phải là một số hợp lệ!");
            }
            return true;
        }
        $player->sendMessage(TF::RED. " §l§c【 §fKam §c】§c Bạn không có quyền sử dụng lệnh này.");
    }
}
