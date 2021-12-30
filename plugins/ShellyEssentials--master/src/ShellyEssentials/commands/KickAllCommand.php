<?php

declare(strict_types=1);

namespace ShellyEssentials\commands;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use ShellyEssentials\API;
use ShellyEssentials\Main;

class KickAllCommand extends BaseCommand{

	public function __construct(Main $main){
		parent::__construct($main, "kickall", "Kick all players online", "/kickall", ["kickall"]);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$sender->hasPermission("kickall.command")){
			$sender->sendMessage(self::NO_PERMISSION);
			return false;
		}
		foreach(API::getMainInstance()->getServer()->getOnlinePlayers() as $player) $player->kick(TextFormat::RED . "You have been kicked since /kickall was ran");
		return true;
	}
}