<?php

declare(strict_types=1);

namespace minefore\mf;

use pocketmine\plugin\PluginBase as AltayBase;
use pocketmine\command\{Command as CMD, CommandSender as CS};
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\event\Listener;
use minefore\mf\task\OffTask;
class Main extends AltayBase implements Listener{

	/** @var Config */
	public $cfg;

	/** @var Main */
    public static $api;
	public $con;
    public function onEnable(){
    	@mkdir($this->getDataFolder());
    	$this->getServer()->getPluginManager()->registerEvents(new Eventss($this), $this);
    	$this->cfg = new Config($this->getDataFolder()."data.yml", Config::YAML);	
		$this->cons = new Config($this->getDataFolder()."cons.yml", Config::YAML);
		$this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$this->getScheduler()->scheduleRepeatingTask(new OffTask($this), 20*60);		
    	self::$api = $this;
    }
    public function onCommand(CS $s, CMD $cmd, string $label, array $args):bool
    {	
		if($cmd->getName() == 'eternity'){

			if(!($s instanceof Player)){
				$s->sendMessage("Hãy sử dụng Command trong Game");
				return true;
			}
			if(!$s->hasPermission('eternity.mf')){
				return false;
			}
			$this->mainForm($s);
		}
		return true;
    }
    public static function getAPI():Main
    {
    	return self::$api;
    }
	
	private function mainForm(Player $player){
		$form = $this->formapi->createSimpleForm(function (Player $sender, $data){

		$result = $data;
		if ($result == null) {
		}
		switch ($result) {
			case 1:
               $this->cfg->set(strtolower($sender->getName()), "on");
               $this->cfg->save();
               $this->cons->set(strtolower($sender->getName()), 30);
               $this->cons->save();
               $this->msgForm($sender,false, "§l§6● §aĐã Bật trong vòng 30!");				
			break;
			
			case 2:
               $this->cfg->set(strtolower($sender->getName()), "off");
               $this->cfg->save();
				$all = $this->cons->getAll();
				unset($all[strtolower($sender->getName())]);
			   $this->cons->setAll($all);
               $this->cons->save();			   
               $this->msgForm($sender,false, "§l§6● §cĐã Tắt");				
			break;	
			
			case 3:
				
			break;
		}
		});
		$form->setTitle("§l§6♦ §cEternity§6 ♦");
		$form->addButton("§l§3● §4Thoát §3●");
		$form->addButton("§l§3● §2Bật §3●");			
		$form->addButton("§l§3● §3Tắt §3●");
		$form->sendToPlayer($player);		
	}	
	
	private function msgForm(Player $player, $nextform = false, $msg){
		if($nextform !== false){
			$form = $this->formapi->createSimpleForm(function (Player $p, $data) {
				if($data === NULL) return false;	
				
					switch($data){
						case 0:
							$nextform;						
						break;
						
						case 1:

						break;
					}
			
			});
		    $form->setTitle("§l§6♦ §cEternity§6 ♦");
			$form->setContent($msg);
			$form->addButton("§l§3● §6Tiếp tục §3●");
			$form->addButton("§l§3● §4Thoát §3●");		
			$form->sendToPlayer($player);	
			return true;
		}
		
		
        $form = $this->formapi->createSimpleForm(function (Player $p, $data) {

			if($data === NULL) return false;	
			
				switch($data){
					case 0:
					
					break;
					
					case 1:

					break;
					
				}
		
		});
		$form->setTitle("§l§6♦ §cEternity§6 ♦");
        $form->setContent($msg);
		$form->addButton("§l§3● §4Thoát §3●");		
        $form->sendToPlayer($player);			
	}	
}