<?php

namespace MDevPMMP\Nick;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Main extends PluginBase{
    public function onEnable()
    {
        $this->getLogger()->info("Nick Plugin by MDevPMMP was activated.");
    }
    public function onDisable()
    {
        $this->getLogger()->info("Nick Plugin by MDevPMMP was deactivated.");
    }
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        switch($cmd->getName()){
            case "nick":
                if(!$sender instanceof Player){
                    $this->getLogger()->info("§cPlease use this command in-game!");
                    return true;
                }
                if(!$sender->hasPermission("nick.command")){
                    $sender->sendMessage("§e§lNick §r§8>> §cYou have n permissions to do this.");
                    return true;
                }
                if(!isset($args[0])){
                    $sender->sendMessage("§e§lNick §r§8>> §cSử dụng : /nick <tên>");
                    return true;
                }
                $sender->setDisplayName($args[0]);
                $sender->sendMessage("§e§lNick §r§8>> §aNickname được set thành công.");
                break;
            case "unnick":
                if(!$sender instanceof Player){
                    $this->getLogger()->info("§cPlease use this command in-game!");
                    return true;
                }
                if(!$sender->hasPermission("nick.command")){
                    $sender->sendMessage("§e§lNick §r§8>> §cYou have no permissions to do this.");
                    return true;
                }
                $sender->setDisplayName($sender->getName());
                $sender->sendMessage("§e§lNick §r§8>> §a Xoá tên thành công.");
        }
        return true;
    }
}