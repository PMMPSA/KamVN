<?php

namespace KhoeItemHand;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\event\Listener;

use pocketmine\event\player\PlayerChatEvent;

class Main extends PluginBase implements Listener{
    
    public function onEnable(){
        $this->getServer()->getLogger()->notice("Plugin By S2TwKen");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    public function onChat(PlayerChatEvent $e){
        $p = $e->getPlayer();
        if($e->getMessage() != "[i]"){
            return;
        }
        $e->setCancelled();
        $i = $p->getInventory()->getItemInHand()->getName();
         foreach($this->getServer()->getOnlinePlayers() as $player){
            $player->sendMessage("§l§e{$p->getName()}§b Hiện đang nắm giữ §l§c {$i}");
        }
    }
    
    public function onTalk(PlayerChatEvent $e){
        $p = $e->getPlayer();
        if($e->getMessage() != "[coor]"){
            return;
        }
        $e->setCancelled();
        $x = intval(round($p->getX()));
        $y = intval(round($p->getY()));
        $z = intval(round($p->getZ()));
        foreach($this->getServer()->getOnlinePlayers() as $player){
            $player->sendMessage("§l§e{$p->getName()}§b Hiện tại   §l§c X: {$x} Y: {$y} Z: {$z}");
        }
    }
}
