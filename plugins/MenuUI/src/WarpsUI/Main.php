<?php

namespace WarpsUI;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener{
	
	public function onEnable(){
		$this->getServer()->getLogger()->Info("§bMenuUI - Enabled!");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		$player = $sender->getPlayer();
		switch($cmd->getName()){
			case "menu":
			$this->mainForm($player);
		}
		return true;
	}
		public function mainForm($player){
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(function (Player $player, $data){
            $result = $data;
            if ($result == null) {
            }
            switch($result) {
              case 0:
			     break;
			  case 1:
                    $command = "warpui";
					$this->getServer()->getCommandMap()->dispatch($player, $command);
                break;
              case 2:
                    $command = "shop";
					$this->getServer()->getCommandMap()->dispatch($player, $command);
                break;
              case 3:
                    $command = "muado";
					$this->getServer()->getCommandMap()->dispatch($player, $command);
				break;
              case 4:
                    $command = "muapoint";
					$this->getServer()->getCommandMap()->dispatch($player, $command);
				break;
			  case 5:
                    $command = "muarank";
					$this->getServer()->getCommandMap()->dispatch($player, $command);
              case 6:
                    $command = "buyec";
					$this->getServer()->getCommandMap()->dispatch($player, $command);
				break;
			  case 7:
                    $command = "buyce";
				    $this->getServer()->getCommandMap()->dispatch($player, $command);
				break;
				case 8:
                    $command = "choden";
				    $this->getServer()->getCommandMap()->dispatch($player, $command);
				break;
				case 9:
                    $command = "kit";
				    $this->getServer()->getCommandMap()->dispatch($player, $command);
				break;
				case 10:
                    $command = "nganhang";
				    $this->getServer()->getCommandMap()->dispatch($player, $command);
				break;
				case 11:
                    $command = "detu";
				    $this->getServer()->getCommandMap()->dispatch($player, $command);
				break;
				case 12:
                    $command = "buypet";
				    $this->getServer()->getCommandMap()->dispatch($player, $command);
				break;
				case 13:
                    $command = "napthe";
				    $this->getServer()->getCommandMap()->dispatch($player, $command);
				break;
				break;
            }
			});
			$form->setTitle("§l§6♦§d Menu System §6♦");
			//$form->setContent("§l§b●§6 Hệ Thống §dMenu§6 Của Máy Chủ:");
			$form->addButton("§l§3● §cThoát §3●", 1, 'http://minefore.tk/png/exit.png');
			$form->addButton("§l§3● §cKhu Vực §3●", 1, 'http://minefore.tk/png/area.png');
			$form->addButton("§l§3● §cCửa Hàng §3●", 1, 'http://minefore.tk/png/shop.png');
			$form->addButton("§l§3● §cMua Đồ UI §3●", 1, 'http://minefore.tk/png/shopui.png');
			$form->addButton("§l§3● §cMua Point §3●", 1, 'http://minefore.tk/png/shoppoint.png');
			$form->addButton("§l§3● §cMua Rank §3●", 1, 'http://minefore.tk/png/shoprank.png');
            $form->addButton("§l§3● §cMua Enchant §3●", 1, 'http://minefore.tk/png/ec.png');
			$form->addButton("§l§3● §cMua CustomEnchant §3●", 1, 'http://minefore.tk/png/ce.png');
			$form->addButton("§l§3● §cChợ Đen §3●", 1, 'http://minefore.tk/png/store.png');
			$form->addButton("§l§3● §cDanh Sách Kit §3●", 1, 'http://minefore.tk/png/list.png');
			$form->addButton("§l§3● §cNgân Hàng §3●", 1, 'http://minefore.tk/png/bank.png');
			$form->addButton("§l§3● §cĐệ Tử §3●", 1, 'http://minefore.tk/png/miner.png');
			$form->addButton("§l§3● §cMua Pet §3●", 1, 'http://minefore.tk/png/petui.png');
			$form->addButton("§l§3● §cNạp Thẻ §3●", 1, 'http://minefore.tk/png/napthe.png');
			$form->sendToPlayer($player);
		}
}