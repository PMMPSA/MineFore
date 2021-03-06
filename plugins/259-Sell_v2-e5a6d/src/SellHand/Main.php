<?php

/*
*   _____      _ _ 
*  / ____|    | | |
* | (___   ___| | |
*  \___ \ / _ \ | |
*  ____) |  __/ | |
* |_____/ \___|_|_|
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Lesser General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*/

namespace SellHand;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command, CommandSender};
use pocketmine\utils\{Config, TextFormat as TF};
use onebone\economyapi\EconomyAPI;
use ShinPickaxeLevel\Main as ShinPickaxeLevel;

class Main extends PluginBase implements Listener{
	public $sell;
	public $messages;
	private static $instance = null;
	
	public function onEnable(){
    $this->getLogger()->info(TF::GREEN.TF::BOLD."
   _____      _ _ 
  / ____|    | | |
 | (___   ___| | |
  \___ \ / _ \ | |
  ____) |  __/ | |
 |_____/ \___|_|_|
 Loaded Sell by Muqsit.
 		");
		$files = array("sell.yml", "messages.yml");
		foreach($files as $file){
			if(!file_exists($this->getDataFolder() . $file)) {
				@mkdir($this->getDataFolder());
				file_put_contents($this->getDataFolder() . $file, $this->getResource($file));
			}
		}
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->sell = new Config($this->getDataFolder() . "sell.yml", Config::YAML);
		$this->messages = new Config($this->getDataFolder() . "messages.yml", Config::YAML);
		$this->rebirths = $this->getServer()->getPluginManager()->getPlugin("Rebirth");
		self::$instance = $this;
	}

	public function onCommand(CommandSender $sender, Command $cmd,string $label, array $args) : bool{
		switch(strtolower($cmd->getName())){
			case "sell":
				/* Check if the player is permitted to use the command */
				if($sender->hasPermission("sell") || $sender->hasPermission("sell.hand") || $sender->hasPermission("sell.all")){
					/* Disallow non-survival mode abuse */
					if(!$sender->isSurvival()){
						$sender->sendMessage(TF::RED . TF::BOLD ."L???i: ". TF::RESET . TF::DARK_RED ."H??y chuy???n sang ch??? ????? sinh t???n.");
						return false;
					}

					/* Sell Hand */
					if(isset($args[0]) && strtolower($args[0]) == "hand"){
						if(!$sender->hasPermission("sell.hand")){
							$error_handPermission = $this->messages->get("error-nopermission-sellHand");
							$sender->sendMessage(TF::RED . TF::BOLD . "L???i: " . TF::RESET . TF::RED . $error_handPermission);
							return false;
						}
						$item = $sender->getInventory()->getItemInHand();
						$itemId = $item->getId();
						/* Check if the player is holding a block */
						if($item->getId() === 0){
							$sender->sendMessage("??l??6[??b+??6] ??eB???n kh??ng gi??? b???t k?? v???t ph???m n??o n??o.");
							return false;
						}
						/* Recheck if the item the player is holding is a block */
						if($this->sell->get($itemId) == null){
							$sender->sendMessage("??l??6[??b+??6] ??cV???t ph???m n??y kh??ng th??? b??n.");
							return false;
						}
						/* Sell the item in the player's hand */
						$price = $this->sell->get($item->getId()) * $item->getCount();

						EconomyAPI::getInstance()->addMoney($sender, $price);

						$sender->getInventory()->removeItem($item);
					//	$sender->sendMessage(TF::GREEN . TF::BOLD . "??l??6[??b+??6] ??eC???p chuy???n sinh $re");						
						//$sender->sendMessage(TF::GREEN . TF::BOLD . "??l??6[??b+??6] ??e$" . $price ."??dX $re". " ??a???? ???????c th??m v??o t??i kho???n c???a b???n.");
						$sender->sendMessage(TF::GREEN . "??l??6[??b+??6] ??aB??n cho ??e$ . $price  ??e(" . $item->getCount() . " " . $item->getName() . " ??? $" . $this->sell->get($itemId) . " m???i).");
					
					/* Sell All */
					}elseif(isset($args[0]) && strtolower($args[0]) == "all"){
						$this->sellAll($sender, $this);
					}elseif(isset($args[0]) && strtolower($args[0]) == "about"){
						$sender->sendMessage("M??y ch??? n??y s??? d???ng plugin, B??n tay, b???i Muqsit Rayyan.");
					}else{
						$sender->sendMessage("??l??6-=??aB??n v???t ph???m tr???c tuy???n??6=-");
						$sender->sendMessage("??l??6[??b+??6] ??b/sell hand ??eB??n m???t h??ng ???? trong tay b???n.");
						$sender->sendMessage("??l??6[??b+??6] ??b/sell all ??eB??n m???i th??? c?? th??? c?? trong t??i ?????.");
						return true;
					}
				}
			break;
		}
		return false;
	}
	
	public function sellAll($sender, $plugin){
		if(!$sender->hasPermission("sell.all")){
			$error_allPermission = $plugin->messages->get("error-nopermission-sellAll");
			$sender->sendMessage(TF::RED . TF::BOLD . "Error: " . TF::RESET . TF::RED . $error_allPermission);
			return false;
		}
		$items = $sender->getInventory()->getContents();
		foreach($items as $item){
			if($plugin->sell->get($item->getId()) !== null && $plugin->sell->get($item->getId()) > 0){
				$price = $plugin->sell->get($item->getId()) * $item->getCount();
				//$re = ShinPickaxeLevel::getInstance()->getReBirth($sender);

				EconomyAPI::getInstance()->addMoney($sender, $price);
				//$sender->sendMessage(TF::GREEN . TF::BOLD . "??l??6[??b+??6] ??eC???p chuy???n sinh $re");
				$sender->sendMessage("??l??6[??b+??6] ??a???? b??n ???????c??b $price ??bxu ??e(" . $item->getCount() . " " . $item->getName() . " gi?? $" . $plugin->sell->get($item->getId()) . " m???i c??i)");
				$sender->getInventory()->remove($item);
			}
		}		
	}
	
	public function sellAll2($inv, $sender, $plugin){
		if(!$sender->hasPermission("sell.all")){
			$error_allPermission = $plugin->messages->get("error-nopermission-sellAll");
			$sender->sendMessage(TF::RED . TF::BOLD . "Error: " . TF::RESET . TF::RED . $error_allPermission);
			return false;
		}
		$items = $inv->getContents();
		foreach($items as $item){
			if($plugin->sell->get($item->getId()) !== null && $plugin->sell->get($item->getId()) > 0){
				$price = $plugin->sell->get($item->getId()) * $item->getCount();
				EconomyAPI::getInstance()->addMoney($sender, $price);
				//$sender->sendMessage("??l??6[??b+??6] ??d????? t??? c???a b???n ??a???? b??n ???????c??b $price ??bxu ??e(" . $item->getCount() . " " . $item->getName() . " gi?? $" . $plugin->sell->get($item->getId()) . " m???i c??i)");
				$inv->remove($item);
			}
		}		
	}
	
	public static function getInstance(){
		return self::$instance;
	}
}
