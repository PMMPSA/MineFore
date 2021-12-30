<?php

namespace PTK\AUTOINV;

use pocketmine\event\Listener;
use pocketmine\inventory\InventoryHolder;
use pocketmine\block\Block;
use SellHand\Main as SellHand;
use pocketmine\event\block\BlockBreakEvent;
use PTK\AUTOINV\Main;

class EventListener implements Listener {
        
        /** @var $plugin Main */
        private $plugin;
        
        /**
         * Construct a new event listener class
         * 
         * @param Main $plugin
         */
        public function __construct(Main $plugin) {
                $this->plugin = $plugin;
                $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
        }
        
        /**
         * Get the owning plugin
         * 
         * @return Main
         */
        public function getPlugin() {
                return $this->plugin;
        }
        
        /**
         * Handles autoinv block breaking
         * 
         * @param BlockBreakEvent $event
         * 
         * @return null
         * 
         * @priority HIGHEST
         */
        public function onBreak(BlockBreakEvent $event) {
                if($event->isCancelled()) {
                    return;
				}
                foreach($event->getDrops() as $drop) {
			    if(!$event->getPlayer()->getInventory()->canAddItem($drop)) {
				    //$this->alert($event->getPlayer());
				    SellHand::getInstance()->sellAll($event->getPlayer(),SellHand::getInstance());
					$event->setDrops([]);
			    }else{
				    $event->getPlayer()->getInventory()->addItem($drop);	
				    $event->setDrops([]);
				}
				}
		}
}
