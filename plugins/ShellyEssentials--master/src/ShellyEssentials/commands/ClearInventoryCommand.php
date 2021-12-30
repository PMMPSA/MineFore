<?php

declare(strict_types=1);

namespace ShellyEssentials\commands;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use ShellyEssentials\API;
use ShellyEssentials\Main;

class ClearInventoryCommand extends BaseCommand{

	public function __construct(Main $main){
		parent::__construct($main, "clearinv", "Clear your inventory", "/clearinv <player>", ["clearinv", "ci"]);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$sender instanceof Player){
			$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6] "   . TextFormat::RED . "Use this command in-game");
			return false;
		}
		if(!$sender->hasPermission("clearinv.command")){
			$sender->sendMessage(self::NO_PERMISSION);
			return false;
		}
		if(empty($args[0])){
			$sender->getInventory()->clearAll();
			$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6] "   . TextFormat::GREEN . "You have cleared your inventory");
			return false;
		}
		if(API::getMainInstance()->getServer()->getPlayer($args[0])){
			$player = API::getMainInstance()->getServer()->getPlayer($args[0]);
			$player->getInventory()->clearAll();
			$player->sendMessage("§l§6[§bSky§aBlock(Bin)§6] "   . TextFormat::GREEN . "Your inventory has been cleared");
			$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6] "   . TextFormat::GREEN . "You have cleared " . $player->getName() . "'s inventory");
		}
		return true;
	}
}