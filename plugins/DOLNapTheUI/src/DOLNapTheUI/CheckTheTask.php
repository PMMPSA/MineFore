<?php

namespace DOLNapTheUI;

use DOLNapTheUI\Main as NAPTHE;
use pocketmine\Player;
use pocketmine\plugin\PluginException;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Config;

class CheckTheTask extends Task
{
    public function onRun(int $currentTick)
    {
		$path = "C:\\xampp\\htdocs\\api\\";
		@mkdir($path, 0777, true);

		
		$data = file_get_contents($path."data.txt");
		//var_Dump($data);
		if(!empty($data)){
			$plugin = NAPTHE::getInstance();
			$pending = new Config($plugin->getDataFolder() . 'pending.yml', Config::YAML, []);
			$temp=0;
			$rm= array();
			$all = $pending->getAll();
			
			$f = @fopen($path."data.txt","a+");
			while (!feof($f)) { // hàm feof sẽ trả về true nếu ở vị trí cuối cùng của file
				$line = fgets($f);				// đọc ra từng dòng trong file
				//var_dump($line);
				if(substr_count($line,"|") >= 1){
					$arrline = explode("|",$line);
					$transId = $arrline[0];
					$status = $arrline[1];
					$user = $pending->get($transId)["user"];
					$server = Server::getInstance();	
					
					if($pending->exists($transId)){
						if($status == 1){			
							$amount = $arrline[2];
							$amount = str_replace("\r\n","",$amount);
							$point = $amount/1000*$plugin->cfg->get("khuyen_mai");
							// Xu ly thong tin tai day
							
							//$all[$transId]["amount"] = $amount;

							$all[$transId]["status"] = 1;
							$plugin->addCP($user, $point, 'nạp thẻ'.$amount);
							$plugin->setLog($user, $all[$transId]["type"], $amount, $all[$transId]["serial"], $all[$transId]["code"],true);
							$server = Server::getInstance();
							foreach($server->getOnlinePlayers() as $player){
								if($player->getLowerCaseName() == $user){
									$player->sendMessage("§l§3● §aNạp thành công thẻ mệnh giá§c $amount VNĐ§a và nhận được§d $point CP");
								}
							}
							if($plugin->data->exists($user)) {
								$plugin->data->set($user, $plugin->data->get($user) + $amount);
								$plugin->data->save();
							}else{
								$plugin->data->set($user, $amount);
								$plugin->data->save();
							}							
						}
						if($status == 2){
							$msg = $arrline[2];
							$all[$transId]["status"] = 2;
							$all[$transId]["msg"] = $msg;
						}
						if($status == 3){
							$amount = $arrline[2];
							$amount = str_replace("\r\n","",$amount);
							$all[$transId]["status"] = 3;
							$all[$transId]["msg"] = "§l§3●§a Nạp thành công thẻ nhưng sai mệnh giá thẻ.Bạn sẽ không nhận được tiền\n§l§3●§a Mệnh giá thật sự:§c $amount\n§l§3●§a Mệnh giá bạn đã nhập:§d ".$all[$transId]["amount"];	
							foreach($server->getOnlinePlayers() as $player){
								if($player->getLowerCaseName() == $user){
									$player->sendMessage("§l§3●§a Nạp thành công thẻ nhưng sai mệnh giá thẻ.Bạn sẽ không nhận được tiền\n§l§3●§a Mệnh giá thật sự:§c $amount\n§l§3●§a Mệnh giá bạn đã nhập:§d ".$all[$transId]["amount"]);
									
								}
							}		
						}
						$rm[$temp]=$line;
						$temp++;
						$pending->setAll($all);
						$pending->save();
						
					}	
				}					
			}
			fclose($f);
			$data = file_get_contents($path."data.txt");
			foreach($rm as $key => $val){
				
				$data = str_replace($val, "", $data);
				file_put_contents($path."data.txt",$data);
			}			
		}	
		
    }
}