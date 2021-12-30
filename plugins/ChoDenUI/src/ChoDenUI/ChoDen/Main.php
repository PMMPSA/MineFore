<?php

/* -----[MuaZCoinUI]-----
* Update Screen UI System.
* Version: 2.0
* Editor: BlackPMFury
* This Test Plugin.
*/

namespace ChoDenUI\ChoDen;

use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command, CommandSender, CommandExecutor, ConsoleCommandSender};
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\{Player, Server};
use jojoe7777\FormAPI;

class Main extends PluginBase{
	
	public function onEnable(){
		$this->getServer()->getLogger()->info(" §l§aEnable ChoDenUI System....");
		$this->point = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
	}
	
	public function onLoad(): void{
		$this->getServer()->getLogger()->notice("Loading Data.....");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		switch($cmd->getName()){
			case "choden":
			if(!($sender instanceof Player)){
				$this->getLogger()->notice("Please Dont Use that command in here.");
				return true;
			}
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(Function (Player $sender, $data){
				
				$result = $data;
				if ($result == null){
				}
				switch ($result) {
					case 0:
					$sender->sendMessage("§c");
					break;
					case 1:
					$this->eternity($sender);
					break;
					case 2:
					$this->autofix($sender);
					break;
					case 3:
					$this->weapons($sender);
					break;
					case 4:
					$this->customenchant($sender);
					break;
				}
			});
			
			$form->setTitle("§l§6♦ §dChợ Đen §l§6♦");
			$form->addButton("§l§3● §cThoát §3●", 1, 'http://minefore.tk/png/exit.png');	
			$form->addButton("§l§3● §2Eternity §3●\n§d【§62000 Point§d】§r", 1, 'http://minefore.tk/png/eternity.png');	
			$form->addButton("§l§3● §2Auto Fix §3●\n§d【§6500 Point§d】§r", 1, 'http://minefore.tk/png/repair.png');
			$form->addButton("§l§3● §2Key Weapons §3●\n§d【§610 Point§d】§r", 1, 'http://minefore.tk/png/key.png');
			//$form->addButton("§l§3● §2Key Customenchant §3●\n§d【§66 Point§d】§r", 1, 'http://minefore.tk/png/key.png');
			$form->sendToPlayer($sender);
			break;
		}
		return true;
	}
	
	public function eternity($sender){
			//$point = $this->point->myMoney($sender);
			if($sender->hasPermission('eternity.mf')){
			   $sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6]§c Bạn đã mua tính năng này rồi!");
			   return false;
			}
			if($this->point->myMoney($sender) >= 2000){
				$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6]§a Bạn đã mua§a thành công tính năng §c/eternity");
				$this->point->reduceMoney($sender, 2000);
				$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm " .  $sender->getName() . " eternity.mf");
			}else{
				$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6] §eBạn cần §c2000 point§e để mua tính năng này!");
				return true;
			}
	}
	
	public function autofix($sender){
			//$point = $this->point->myMoney($sender);
			if($sender->hasPermission('autofix.mf')){
			   $sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6]§c Bạn đã mua tính năng này rồi!");
			   return false;
			}
			if($this->point->myMoney($sender) >= 500){
				$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6]§a Bạn đã mua§a thành công tính năng §cautofix");
				$this->point->reduceMoney($sender, 500);
				$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm " .  $sender->getName() . " autofix.mf");
			}else{
				$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6] §eBạn cần §c500 point§e để mua tính năng này!");
				return true;
			}
	}

    public function weapons($sender){
			//$point = $this->point->myMoney($sender);
			if($this->point->myMoney($sender) >= 10){
				$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6]§a Bạn đã mua§a thành công key §cweapons");
				$this->point->reduceMoney($sender, 10);
				$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "key weapons " .  $sender->getName() . " 1");
			}else{
				$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6] §eBạn cần §c10 point§e để mua tính năng này!");
				return true;
			}
	}
	
	/*public function customenchant($sender){
			//$point = $this->point->myMoney($sender);
			if($this->point->myMoney($sender) >= 6){
				$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6]§a Bạn đã mua§a thành công key §ccustomenchant");
				$this->point->reduceMoney($sender, 6);
				$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "key customenchant " .  $sender->getName() . " 1");
			}else{
				$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6] §eBạn cần §c6 point§e để mua tính năng này!");
				return true;
			}
	}*/
}