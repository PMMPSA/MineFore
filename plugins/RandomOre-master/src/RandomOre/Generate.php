<?php

namespace RandomOre;

use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\Listener;
use pocketmine\block\Block;
use pocketmine\block\Fence;
use pocketmine\block\Water;

class Generate extends PluginBase implements Listener{
    
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }

        public function onBlockSet(BlockUpdateEvent $event){
		$level = $event->getBlock()->getLevel()->getFolderName();
        if ($level === 'sb') {
	    $ores = ['Coal','Iron','Gold','Lapis','Redstone','Emerald','Diamond','Quartz','CoalBlock','IronBlock','GoldBlock','LapisBlock','RedstoneBlock','EmeraldBlock','DiamondBlock'];
		$this->oreList = $ores;
        $block = $event->getBlock();
        $water = false;
        $fence = false;
        for ($target = 2; $target <= 5; $target++) {
            $nearBlock = $block->getSide($target);
            if ($nearBlock instanceof Water) {
                $water = true;
            }elseif ($nearBlock instanceof Fence) {
                $fence = true;
            }
            if ($water && $fence) {
                $rand = array_rand($this->oreList,1);
                switch($this->oreList[$rand]){
                    case 'Coal';
                        $newBlock = Block::get(Block::COAL_ORE);
                        break;
                    case 'Iron';
                        $newBlock = Block::get(Block::IRON_ORE);
                        break;
                    case 'Gold';
                        $newBlock = Block::get(Block::GOLD_ORE);
                        break;
                    case 'Lapis';
                        $newBlock = Block::get(Block::LAPIS_ORE);
                        break;
                    case 'Redstone';
                        $newBlock = Block::get(Block::REDSTONE_ORE);
                        break;
                    case 'Emerald';
                        $newBlock = Block::get(Block::EMERALD_ORE);
                        break;
					case 'Diamond';
                        $newBlock = Block::get(Block::DIAMOND_ORE);
                        break;
                    case 'Quartz':
                        $newBlock = Block::get(Block::NETHER_QUARTZ_ORE);
                        break;
                    case 'CoalBlock';
                        $newBlock = Block::get(Block::COAL_BLOCK);
                        break;
                    case 'IronBlock';
                        $newBlock = Block::get(Block::IRON_BLOCK);
                        break;
                    case 'GoldBlock';
                        $newBlock = Block::get(Block::GOLD_BLOCK);
                        break;
                    case 'LapisBlock';
                        $newBlock = Block::get(Block::LAPIS_BLOCK);
						break;
                    case 'RedstoneBlock';
                        $newBlock = Block::get(Block::REDSTONE_BLOCK);
                        break;
                    case 'EmeraldBlock';
                        $newBlock = Block::get(Block::EMERALD_BLOCK);
                        break;
                    case 'DiamondBlock';
                        $newBlock = Block::get(Block::DIAMOND_BLOCK);
                        break;
                    default:
                        $newBlock = Block::get(Block::STONE);
                }
                $block->getLevel()->setBlock($block, $newBlock, false, false);
                return;
			}
		}
		}
		}
}
