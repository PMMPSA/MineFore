<?php

declare(strict_types=1);

namespace ShellyEssentials\commands;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use ShellyEssentials\API;
use ShellyEssentials\Main;

class GamemodeCreativeCommand extends BaseCommand{

	public function __construct(Main $main){
		parent::__construct($main, "gmc", "Gamemode creative command", "/gmc <player>", ["gmc"]);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$sender instanceof Player){
			$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6] "   . TextFormat::RED . "Use this command in-game");
			return false;
		}
		if(!$sender->hasPermission("gmc.command")){
			$sender->sendMessage(self::NO_PERMISSION);
			return false;
		}
		if(empty($args[0])){
			$sender->setGamemode(Player::CREATIVE);
			$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6] "   . TextFormat::GREEN . "You have set your gamemode to creative");
			return false;
		}
		if(API::getMainInstance()->getServer()->getPlayer($args[0])){
			$player = API::getMainInstance()->getServer()->getPlayer($args[0]);
			$player->setGamemode(Player::CREATIVE);
			$player->sendMessage("§l§6[§bSky§aBlock(Bin)§6] "   . TextFormat::GREEN . "Your gamemode has been set to creative");
			$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6] "   . TextFormat::GREEN . "You have set " . $player->getName() . "'s gamemode to creative");
		}
		return true;
	}
}