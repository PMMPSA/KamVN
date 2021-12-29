<?php

namespace RandomBlock;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\item\Item;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\block\Block;
use pocketmine\block\Cobblestone;
use pocketmine\block\Fence;
use pocketmine\block\Water;
use pocketmine\block\IronOre;
use pocketmine\block\DiamondOre;
use pocketmine\block\EmeraldOre;
use pocketmine\block\GoldOre;
use pocketmine\block\LapisOre;
use pocketmine\block\Stone;
use pocketmine\block\CoalOre;

class Main extends PluginBase implements Listener{
    
    public function onEnable(){
        $this->getLogger()->info("§f\n§r§b======================================§r\n\n§a-§f RandomBlock Edit Bởi Nờ Cờ Dờ§r\n§a-§f Dành Cho Phiên Bản MCBE 1.5.x§r\n\n§b======================================");
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }

        public function onBlockSet(BlockUpdateEvent $event){
        $block = $event->getBlock();
        $water = false;
        $fence = false;
        for ($i = 2; $i <= 5; $i++) {
            $nearBlock = $block->getSide($i);
            if ($nearBlock instanceof Water) {
                $water = true;
            } else if ($nearBlock instanceof Fence) {
                $fence = true;
            }
            if ($water && $fence) {
                $id = mt_rand(1, 20);
                switch ($id) {
                    case 2;
                        $newBlock = new Cobblestone();
                        break;
					case 4;
                        $newBlock = new IronOre();
                        break;
                    case 6;
                        $newBlock = new GoldOre();
                        break;
                    case 8;
                        $newBlock = new EmeraldOre();
                        break;
                    case 10;
                        $newBlock = new Stone();
                        break;
                    case 12;
                        $newBlock = new DiamondOre();

                        break;
                     case 14;
                         $newBlock
                             = new CoalOre();
                        break;
                    default:
                        $newBlock = new Cobblestone();
                        $newBlock = new Cobblestone();
                        $newBlock = new Cobblestone();
                        $newBlock = new Cobblestone();
                }
                $block->getLevel()->setBlock($block, $newBlock, true, false);
                return;
            }
        }
    }
}
