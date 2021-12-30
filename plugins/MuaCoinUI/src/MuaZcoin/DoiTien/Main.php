<?php

/* -----[MuaZCoinUI]-----
* Update Screen UI System.
* Version: 2.0
* Editor: BlackPMFury
* This Test Plugin.
*/

namespace MuaZcoin\DoiTien;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\{Player, Server};
use jojoe7777\FormAPI;
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase{
	
	public function onEnable(){
		$this->getServer()->getLogger()->info(" §l§aEnable MuaZCoin System....");
		$this->point = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
		$this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
	}
	
	public function onLoad(): void{
		$this->getServer()->getLogger()->notice("Loading Data.....");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		switch($cmd->getName()){
			case "muapoint":
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
					$this->information($sender);
					break;
					case 2:
					$this->doiZCoin($sender);
					break;
				}
			});
			
			$form->setTitle("§l§6♦ §dMua Point §l§6♦");
			$form->addButton("§l§3● §cThoát §3●", 1, 'http://minefore.tk/png/exit.png');
			$form->addButton("§l§3● §5Thông Tin §3●", 1, 'http://minefore.tk/png/list.png');
			$form->addButton("§l§3● §2Mua Point §3●",1 , 'http://minefore.tk/png/point.png');
			$form->sendToPlayer($sender);
			break;
		}
		return true;
	}
	
	public function information($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(Function (Player $sender, $data){
		});
		$form->setTitle("§l§6♦§d Thông Tin §l§6♦");
		$form->addLabel("§l§3● §aWelcome To System §6Mua Point.");
		$form->addLabel("§l§3● §bNhập Số Point cần thiết vào Ô Input");
		$form->addLabel("§l§3● §d400k xu = 1 Point");
		$form->sendToPlayer($sender);
	}
	
	public function doiZCoin($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(Function (Player $sender, $data){
			if($data == null){
				return;
			}
			$data[0] >= 1;
			$tien = $this->money->myMoney($sender);
			if(!isset($data[0]) || !is_numeric($data[0])){
				$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6] §cSố xu cần phải là số!");
			    return false;
			}
			if($tien >= $data[0]*400000){
				$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6]§a Bạn đã mua§e " . $data[0] . "§a point");
				$this->money->reduceMoney($sender, $data[0]*400000);
				$this->point->addMoney($sender, $data[0]);
			}else{
				$sender->sendMessage("§l§6[§bSky§aBlock(Bin)§6] §cKhông Đủ Xu!");
				return true;
			}
		});
		$form->setTitle("§l§6♦ §dMua Point §6♦");
		$form->addInput("§l§aNhập Số Point Cần Mua:", "", 0);
		$form->sendToPlayer($sender);
	}
}