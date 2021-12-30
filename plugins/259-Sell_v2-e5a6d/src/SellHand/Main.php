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
						$sender->sendMessage(TF::RED . TF::BOLD ."Lỗi: ". TF::RESET . TF::DARK_RED ."Hãy chuyển sang chế độ sinh tồn.");
						return false;
					}

					/* Sell Hand */
					if(isset($args[0]) && strtolower($args[0]) == "hand"){
						if(!$sender->hasPermission("sell.hand")){
							$error_handPermission = $this->messages->get("error-nopermission-sellHand");
							$sender->sendMessage(TF::RED . TF::BOLD . "Lỗi: " . TF::RESET . TF::RED . $error_handPermission);
							return false;
						}
						$item = $sender->getInventory()->getItemInHand();
						$itemId = $item->getId();
						/* Check if the player is holding a block */
						if($item->getId() === 0){
							$sender->sendMessage("§l§6[§b+§6] §eBạn không giữ bất kì vật phẩm nào nào.");
							return false;
						}
						/* Recheck if the item the player is holding is a block */
						if($this->sell->get($itemId) == null){
							$sender->sendMessage("§l§6[§b+§6] §cVật phẩm này không thể bán.");
							return false;
						}
						/* Sell the item in the player's hand */
						$price = $this->sell->get($item->getId()) * $item->getCount();

						EconomyAPI::getInstance()->addMoney($sender, $price);

						$sender->getInventory()->removeItem($item);
					//	$sender->sendMessage(TF::GREEN . TF::BOLD . "§l§6[§b+§6] §eCấp chuyển sinh $re");						
						//$sender->sendMessage(TF::GREEN . TF::BOLD . "§l§6[§b+§6] §e$" . $price ."§dX $re". " §ađã được thêm vào tài khoản của bạn.");
						$sender->sendMessage(TF::GREEN . "§l§6[§b+§6] §aBán cho §e$ . $price  §e(" . $item->getCount() . " " . $item->getName() . " ở $" . $this->sell->get($itemId) . " mỗi).");
					
					/* Sell All */
					}elseif(isset($args[0]) && strtolower($args[0]) == "all"){
						$this->sellAll($sender, $this);
					}elseif(isset($args[0]) && strtolower($args[0]) == "about"){
						$sender->sendMessage("Máy chủ này sử dụng plugin, Bán tay, bởi Muqsit Rayyan.");
					}else{
						$sender->sendMessage("§l§6-=§aBán vật phẩm trực tuyến§6=-");
						$sender->sendMessage("§l§6[§b+§6] §b/sell hand §eBán mặt hàng đó trong tay bạn.");
						$sender->sendMessage("§l§6[§b+§6] §b/sell all §eBán mọi thứ có thể có trong túi đồ.");
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
				//$sender->sendMessage(TF::GREEN . TF::BOLD . "§l§6[§b+§6] §eCấp chuyển sinh $re");
				$sender->sendMessage("§l§6[§b+§6] §aĐã bán được§b $price §bxu §e(" . $item->getCount() . " " . $item->getName() . " giá $" . $plugin->sell->get($item->getId()) . " mỗi cái)");
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
				//$sender->sendMessage("§l§6[§b+§6] §dĐệ tử của bạn §ađã bán được§b $price §bxu §e(" . $item->getCount() . " " . $item->getName() . " giá $" . $plugin->sell->get($item->getId()) . " mỗi cái)");
				$inv->remove($item);
			}
		}		
	}
	
	public static function getInstance(){
		return self::$instance;
	}
}
