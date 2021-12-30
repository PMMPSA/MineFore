<?php

namespace DOLNapTheUI;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\Command;
use pocketmine\Player;
use pocketmine\event\Listener;
use joejoe77777\FormAPI;


use DOLNapTheUI\SortTask;
use DOLNapTheUI\CheckTheTask;
date_default_timezone_set('Asia/Ho_Chi_Minh');
set_time_limit(0);

class Main extends PluginBase implements Listener {

	public static $instance;
	
	public $eco, $point, $purePerms;
	
	private $formapi;
	
	private $try = 4;
	public function onEnable(){

		if(!is_dir($this->getDataFolder())) {
		  mkdir($this->getDataFolder());
		}
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->notice("\n\nNapTheUI...\n\n");
		$this->point = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$this->purePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
		$this->getLogger()->info(TextFormat::GREEN."§aPlugin made in VietNam by Shin on MCPEVN server ");
		$this->data = new Config($this->getDataFolder() ."tong_card_mem_nap.yml", Config::YAML, [
		]);
		$this->coupon = new Config($this->getDataFolder() ."coupon.yml", Config::YAML, [
		]);
		$this->cfg = new Config($this->getDataFolder() ."cai_dat.yml", Config::YAML, [
		"APIkey" => "APIkey",
		"khuyen_mai" => "1",
		"ty_gia_point" => "1",
		"ty_gia_xu" => "1",
		"pass_give_cp" => "shindzvc1134"
		]);
		$this->getScheduler()->scheduleRepeatingTask(new CheckTheTask($this), 20 * 20);
		//$this->prefix = "§l§6♦"." §aCách Dùng: §d/napthe [Mã] [Seri] [mobi|viettel|vina|gate|vtc] [10000|20000|50000|100000|200000|500000]\n§l§6♦ §aVí Dụ:§b /napthe 29829481938 18928394812 viettel 50000\n§l§6♦§a Hiện Đang Có Khuyến Mãi §cX".(($this->cfg->get("khuyen_mai") > 1) ? $this->cfg->get("khuyen_mai") : '';
		$duongdan = $this->getDataFolder().'/card_dung.log';
		if(file_exists($duongdan)){
		}else{
		$fh = fopen($this->getDataFolder().'\card_dung.log', "a") ;
		fwrite($fh,'| Tai khoan                  |     Loai the    |  seri            |    mã thẻ      |  Menh gia     |   so point nhan duoc   |       Thoi gian            |');
		fwrite($fh,"\r\n");
		fclose($fh);
		}
		$duongdan = $this->getDataFolder().'/card_delay.log';
		if(file_exists($duongdan)){
		}else{
			$fh = fopen($this->getDataFolder().'\card_delay.log', "a") ;
			fwrite($fh,'| Tai khoan                  |     Loai the    |  seri            |    mã thẻ      | Mã lỗi                                                                |  Coin |         Thoi gian                 |');
			fwrite($fh,"\r\n");
			fclose($fh);
		}	
		self::$instance = $this;
	}
	
	public static function getInstance(){
		return self::$instance;
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
		switch($command->getName()){
			case "napthe":
			if(!($sender instanceof Player)){
				$sender->sendMessage("Hãy sử dụng Command trong Game");
				return true;
			}
				$this->mainForm($sender);
			break;
			
			case "givecp":
				if($sender->isOp()){
					if(isset($args[0]) && isset($args[1])&& isset($args[2])) {
						if($args[2]!==$this->cfg->get("pass_give_cp")){
							$this->try--;
							if($this->try <=0){$sender->sendMessage("You don't has time to you this command");return true;}
							$sender->sendMessage("§cThe password you typed in the command is incorrect !. Retype the Command");
							$sender->sendMessage("you has $this->try time(s) to use this command");
							return true;
						}									
						$target = $this->purePerms->getPlayer(strtolower($args[0])) !== null ? strtolower($this->purePerms->getPlayer(strtolower($args[0]))->getName()) : '';
						if($target =='') {
							$sender->sendMessage(" §cPlayer §e$target §cdoes not found in our database!");
							return true;
						}
						$amount = round(abs($args[1]), 2);
						$this->addCP($target, $amount, $sender->getName() . "đưa cho $target tiền");
						$ppoint = $this->getCP($target);
						$sender->sendMessage("§a Transaction success ! Sent §f$amount §ato  §e$target, §aPoint 's §e$target: §f$ppoint CP(s)");
						return true;
						
						
					} else {
						$sender->sendMessage("§e/givecp <target> <amount> <password>");
						return true;
					}
				}else{$sender->sendMessage("You don't have permisson to use this command");return true;}
			break;			


		}
		return true;
	}
	
	private function mainForm(Player $player){
		$form = $this->formapi->createSimpleForm(function (Player $sender, $data){

		$result = $data;
		
		if ($result == null) {
			return false;
		}
		switch ($result) {

			case 0:
			
			break;	
			
			case 1:
				//$this->msgForm($sender, $this->mainForm($sender), ($this->cfg->get("khuyen_mai") > 1) ? "§l§6♦§a Hiện Đang Có Khuyến Mãi §cX".$this->cfg->get("khuyen_mai") : "§l§6♦§a Hiện tại không có khuyến mãi nào đang diễn ra !");
				$this->sendTop($sender->getName(), $this->data->getAll());
			break;	
			
			case 2:
				$this->HistoryForm($sender);
			break;
				
			case 3:
				$this->napTheForm($sender);
			break;

			case 4:
				$this->payCPForm($sender);	
			break;
			
			case 5:
				$this->doiPoint($sender);
			break;
			
			case 6:
				$this->doiXu($sender);
			break;	
			
			case 7:
				$this->getServer()->dispatchCommand($sender, "buypet");    
			break;
			
			case 8:
				$this->chooseRankForm($sender);
			break;
			
			case 9:
				$this->muaKit($sender);
			break;	
			
			case 10:

			break;	
			
			
		}
		});
		
		$form->setTitle("§l§6♦ §cNạp Thẻ§b Mine§aFore §6♦");
		$form->setContent("§l§3● §aSố Coupon của bạn: §e". $this->getCP($player).(($this->cfg->get("khuyen_mai") > 1) ? "\n§l§3●§a Hiện Đang Có Khuyến Mãi §cX".$this->cfg->get("khuyen_mai") : ""));
		$form->addButton("§l§3● §dThoát §3●", 1, 'http://minefore.tk/png/exit.png');			
		$form->addButton("§l§3● §dTop Nạp Thẻ §3●", 1, 'http://minefore.tk/png/rich.png');			
		$form->addButton("§l§3● §dLịch Sử Nạp Thẻ §3●", 1, 'http://minefore.tk/png/history.png');					
		$form->addButton("§l§3● §dNạp Thẻ §3●", 1, 'http://minefore.tk/png/napthe.png');
		$form->addButton("§l§3● §dChuyển CP §3●", 1, 'http://minefore.tk/png/pay.png');			
		$form->addButton("§l§3● §dĐổi Point §3●",1 , 'http://minefore.tk/png/point.png');	
		$form->addButton("§l§3● §dĐổi Xu §3●",1 , 'http://minefore.tk/png/xu.png');	
		$form->addButton("§l§3● §dMua Pet §3●",1 , 'http://minefore.tk/png/petui.png');	
		$form->addButton("§l§3● §dMua Rank §3●",1 , 'http://minefore.tk/png/rank.png');	
		//$form->addButton("§l§3● §2Mua Kit §3●",1 , 'http://minefore.tk/png/kit.png');	

		$form->sendToPlayer($player);		
	}
	
	private function payCPForm(Player $player){
		$form = $this->formapi->createCustomForm(function (Player $sender, ?array $data){
			if($data === NULL) return false;
			
			$name = $data[0];
			$cp = $data[1];
			$target = $this->getServer()->getPlayer($this->onl[$name]);
			
			$this->msgForm($sender, $this->payCPSucessForm($sender, $target, $cp) , "§l§3●§c Bạn có chắc muốn đưa §d$cp Coupon§c cho§d $name §ckhông?" );
			
		});
		$form->setTitle("§l§6♦ §cChuyển§b Coupon §6♦");
		$this->onl = array();
		foreach($this->getServer()->getOnlinePlayers() as $p){
			if($p->getName() !== $player->getName()){
				$this->onl[] = $p->getName();
			}
		}
		if(count($this->onl) <= 0){
			$this->msgForm($player, false, "§l§3● §cHiện Tại Không có ai Online, Không thể chuyển CP");
			return;
		}
		$form->addDropDown("§l§3●§a Chọn Player:", $this->onl);
		$form->addSlider("§l§3●§a Số CP muốn chuyển:", 0, $this->getCP($player), 1, 0);	
		$form->sendToPlayer($player);	
	}

	private function payCPSucessForm(Player $player,Player $target, $cp){
		if($cp == 0){
			$this->msgForm($player,$this->payCPForm($player) ,"§l§3●§e Số CP chuyển phải lớn hơn §b0.\n§l§3●§e Bạn có muốn quay về trang Chuyển CP không ?");
			return false;
		}
		if($this->getCP($player) >=$cp){
			$this->addCP($target, $cp, "Nhận $cp CP từ ". $player->getName());
			$this->reduceCP($player, $cp,"đưa $cp CP cho " . $target->getName());
			$this->msgForm($player,$this->mainForm($player) ,"§l§3● §aChuyển CP thành công, " . $target->getName() . "§c $cp CP.\n§l§3●§e Bạn có muốn quay về trang chủ không ?");
			$target->addTitle("§l§aBạn nhận được", "§l§e$cp CP §atừ §d" . $player->getName());
		}
		else $this->msgForm($player,$this->payCPForm($player) ,"§l§3●§e Bạn không đủ CP để chuyển.\n§l§3●§e Bạn có muốn quay về trang Chuyển CP không ?");		
	}
	
	private function chooseRankForm(Player $player){
		$form = $this->formapi->createSimpleForm(function (Player $sender, $data){

		$result = $data;
		
		if ($result == null) {
			return false;
		}
		switch ($result) {

			case 0:
			
			break;	
			
			case 1:
				$this->getServer()->dispatchCommand($sender, "muarank");
			break;	
			
			case 2:
				$this->doiRank($sender);
			break;
			
			
			
		}
		});
		
		$form->setTitle("§l§6♦ §cNạp Thẻ§b Mine§aFore §6♦");

		$form->addButton("§l§3● §cThoát §3●");		
		$form->addButton("§l§3● §dRank Point §3●");	
		$form->addButton("§l§3● §dAngelKid 500 Coupon§3●");	


		$form->sendToPlayer($player);		
	}
	
	private function doiPoint(Player $player){
		$form = $this->formapi->createCustomForm(function (Player $sender, ?array $data){
			if($data === NULL) return false;
			$cp = $data[0];
			$tygia = $this->cfg->get("ty_gia_point");
			$point  = $cp * $tygia;			
			$this->msgForm($sender, $this->doiPointSucess($sender, $cp, $point),"§l§3●§e Bạn có chắc muốn đổi§c $cp Coupon§e sang§d $point Point");
			
		});
		$form->setTitle("§l§6♦ §cĐổi§b Point §6♦");
		$form->addSlider("§l§3●§a Tỷ giá: §61 Coupon §3= §d2 Point\n§l§3● §aSố Coupon muốn đổi§6", 0, $this->getCP($player), 1, 0);
		//string $text, int $min, int $max, int $step = -1, int $default = -1, ?string $label = null
		$form->sendToPlayer($player);	
	}
	
	private function doiPointSucess(Player $player, $cp, $point){
		if($cp == 0){
			$this->msgForm($player,$this->mainForm($player) ,"§l§3●§e Số CP đổi phải lớn hơn §b0.\n§l§3●§e Bạn có muốn quay về trang chủ không ?");
			return false;
		}
		if($this->getCP($player) >=$cp){
			$this->point->addMoney($player->getName(), $point);
			$this->reduceCP($player, $cp,"Đổi $cp CP sang $point Point");
			$this->msgForm($player,$this->mainForm($player) ,"§l§3● §aĐổi Point thành công, bạn nhận được§c $point Point.\n§l§3●§e Bạn có muốn quay về trang chủ không ?");
			
		}
	}
	
	private function doiXu(Player $player){
		$form = $this->formapi->createCustomForm(function (Player $sender, ?array $data){
			if($data === NULL) return false;
			$cp = $data[0];
			$tygia = $this->cfg->get("ty_gia_xu");
			$xu  = $cp * $tygia;
			$this->msgForm($sender, $this->doiXuSucess($sender, $cp, $xu),"§l§3●§e Bạn có chắc muốn đổi§c $cp Coupon§e sang§d $xu Xu");
			
		});
		$form->setTitle("§l§6♦ §cĐổi§b Xu §6♦");
		$form->addSlider("§l§3●§a Tỷ giá: §61 Coupon §3= §e800.000 Xu\n§l§3● §aSố Coupon muốn đổi", 0, $this->getCP($player), 1, 0);
		//
		$form->sendToPlayer($player);	
	}
	
	private function doiXuSucess(Player $player, $cp, $xu){
		if($cp == 0){
			$this->msgForm($player,$this->mainForm($player) ,"§l§3●§e Số CP đổi phải lớn hơn §b0.\n§l§3●§e Bạn có muốn quay về trang chủ không ?");
			return false;
		}
		$this->eco->addMoney($player,$xu);
		$this->reduceCP($player, $cp,"§l§3● §dĐổi§c $cp CP §esang§d $xu Xu");
		$this->msgForm($player,$this->mainForm($player) ,"§l§3● §dĐổi Xu thành công, bạn nhận được§d $xu Xu.\n§l§3●§e Bạn có muốn quay về trang chủ không ?");
	}
	
	private function doiRank(Player $player){
		$form = $this->formapi->createSimpleForm(function (Player $sender,$data){
			if($data === NULL) return false;
			
			switch($data){
				case 0:
					if($this->getCP($sender) >= 500){
						$this->msgForm($sender, $this->doiRankSucess($sender, 500),"§l§3●§e Bạn có chắc muốn mua §dAngelKid §ebằng §c500 Coupon§e không ?");
						return false;
					}
					$this->msgForm($sender, false,"§l§3●§e Bạn không đủ Coupon");
				break;
			}
			$cp = $data[0];
			$tygia = $this->cfg->get("ty_gia_point");
			$point  = $cp * $tygia;		
			$this->msgForm($sender, $this->doiPointSucess($sender, $cp, $point),"§l§3●§e Bạn có chắc muốn đổi§c $cp Coupon §esang§d $point Point");
			
		});
		$form->setTitle("§l§6♦ §cMua§b AngelKid §6♦");
		$form->setContent("§l§3● §dAngelKid §ecó quyền set time, kiteffect, vanish, nick, kickk và nhận được kit Angel");
		$form->addButton("§l§dAngelKid");
		$form->sendToPlayer($player);	
	}
	
	private function doiRankSucess(Player $player, $cp){
		$this->reduceCP($player, $cp,"mua AngelKid vs giá 500CP");
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup " .  $player->getName() . " AngelKid");
		$this->msgForm($player,$this->mainForm($player) ,"§l§3●§e Mua Rank §l§athành công!.\n§l§3●§e Bạn có muốn quay về trang chủ không ?");
	}
	
	private function HistoryForm(Player $player){
		
		$list = $this->getPendingList($player);
		
		$form = $this->formapi->createSimpleForm(function (Player $sender, $data){
			if ($data === null) {
				return false;
			}
			$list = $this->getPendingList($sender);
			$key = $list[$data];
			$pending = new Config($this->getDataFolder() . 'pending.yml', Config::YAML, []);
			$all = $pending->getAll();
			$ar = $all[$key];
			//var_dump($data);

			$m = isset($ar['msg']) ? "§clỗi: ".$ar['msg'] : "";
			if($ar['status'] == 1){
				$status = "§l§3● §aThành công";
			} else if($ar['status'] == 0){
				$status = "§l§3● §eĐang xữ lý";
			} else $status = "§l§3● §cThất bại";
			$mess =	" §l§eTrạng thái:  $status\n".
					" §l§eSeri: §a{$ar["serial"]}\n" .
					" §l§ePin: §a{$ar["code"]}\n" .
					" §l§eMạng: §a{$ar["type"]}\n" .
					" §l§eMệnh giá: §a{$ar["amount"]}\n".
					"$m";
			$this->msgForm($sender,$this->mainForm($sender) ,$mess);
		});
	
		$form->setTitle("§l§6♦ §cNạp Thẻ§b Mine§aFore §6♦");
		
		if(count($list) <= 0) {
			$this->msgForm($player,$this->mainForm($player) ,"§l§3●§cBạn chưa nạp thẻ");
			return;
		}
		$form->setContent("§l§3● §cSeri:");
		$pending = new Config($this->getDataFolder() . 'pending.yml', Config::YAML, []);
		$all = $pending->getAll();
		foreach($list as $key){		
			$form->addButton("§l§d".$all[$key]["serial"],1 , $this->getPNG($all[$key]["status"]));			
		}
		
		$form->sendToPlayer($player);		
	}
	
	private function getPNG($status){
		switch($status){
			case 0:
				$png = 'http://minefore.tk/png/pending.png';
			break;
			
			case 1:
				$png = 'http://minefore.tk/png/ok.png';
			break;
			
			default:
				$png = 'http://minefore.tk/png/cancel.png';
			break;
			
		}
		return $png;
	}
	
	private function getPendingList($player){
		$temp =0 ;
		$list = array();
		$pending = new Config($this->getDataFolder() . 'pending.yml', Config::YAML, []);
		$all = $pending->getAll();
		foreach($all as $key => $val){
			if(strtolower($val["user"]) == strtolower($player->getName())){
				$list[$temp] = $key;
				$temp++;
			}
		}
		
		return $list;
	}
	
	private function napTheForm(Player $player){
		$form = $this->formapi->createCustomForm(function (Player $sender, ?array $data){
			if($data === NULL) return false;

			$pin_field = $data[0];			
			$seri_field = $data[1];
			$mang = $this->getSuplier($data[2]);
			$card_value = $this->getCardValue($data[3]);
				if((strlen($seri_field) >= 9) && (strlen($seri_field) <= 15)){
					if((strlen($pin_field) >= 9) && (strlen($pin_field) <= 15)){

						$APIkey = $this->cfg->get('APIkey');
						$guithe = new NapTheNgay_API();
						$guithe->setInput($APIkey, $pin_field, $seri_field, $mang, $card_value);
						$guithe->cardCharging();						
						$result = $guithe->getResult();
						if($guithe->getStatus() !== 200){
							$this->msgForm($sender, false, "§l§3● §e Lỗi kết nối, vui lòng báo Admin để được giải quyết");
							return;
						}
						
							$transId = $result->transaction_id;
							$data = $guithe->setPending($sender->getName(), $transId);
							$all = $data;						
							$mes = "§l§3● §aGửi thẻ thành công, bạn hãy vào §eLịch Sữ §ađể kiểm tra trạng thái thẻ\n" .
								" §l§eMã số: §a$transId\n".
								" §l§eSeri: §a{$all[$transId]["serial"]}\n" .
								" §l§ePin: §a{$all[$transId]["code"]}\n" .
								" §l§eMạng: §a{$all[$transId]["type"]}\n" .
								" §l§eMệnh giá: §a{$all[$transId]["amount"]}\n";
							$this->msgForm($sender, $this->HistoryForm($sender),$mes);
							return true;
						
					}
				   $this->msgForm($sender, $this->napTheForm($sender), "§l§3● §eSai độ dài mã thẻ");
				   return true;									
				}	
			   $this->msgForm($sender, $this->napTheForm($sender), "§l§3● §eSai độ dài seri");
			   return true;		
			
		});
		$form->setTitle("§l§6♦ §cNạp Thẻ§b Mine§aFore §6♦");
		$form->addInput("§l§3●§a Tỷ giá nạp thẻ: §61000 VNĐ §3= §e1 Coupon\n§l§3● §aMã Thẻ:", "§l§eNhập Mã thẻ...");
		$form->addInput("§l§3●§a Seri:", "§l§eNhập seri thẻ...");
		$form->addDropDown("§l§3●§a Loại Thẻ:", array("§l§cZing", "§l§cVcoin", "§l§cViettel", "§l§cMobifone","§l§cVinaPhone"));
		$form->addStepSlider("§l§3● §aMệnh giá", ["§l§d10000", "§l§d20000", "§l§d50000","§l§d100000","§l§d200000","§l§d500000", "§l§d1000000"], 1);
		$form->addLabel("§l§3● §dChọn chính xác,sai mệnh giá sẽ không được nhận §cpoint");
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
			$form->setTitle("§l§6♦ §cNạp Thẻ§b Mine§aFore §6♦");
			$form->setContent($msg);
			$form->addButton("§l§3● §cTiếp tục §3●");
			$form->addButton("§l§3● §cThoát §3●");		
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
		$form->setTitle("§l§6♦ §cNạp Thẻ§b Mine§aFore §6♦");
        $form->setContent($msg);
		$form->addButton("§l§3● §cThoát §3●");		
        $form->sendToPlayer($player);			
	}
		
	private function getSuplier($args){
        switch (strtolower($args)) {
			
			case "0":
				$suplier = "Zing"; 
			break;

			case "1":
				$suplier = "Vcoin"; 		
			break;
			
            case "2":
                $suplier = "Viettel"; 
			break;
			
            case "3":
                $suplier = "Mobifone"; 
            break;
						
			case "4":
            default:
                $suplier = "Vinaphone"; 
            break;
        }
        return $suplier;
    }

	private function getCardValue($args){
         $suplier = $args;
        switch (strtolower($suplier)) {
            case "0":
                $suplier = "10000";
            break;
			
            case "1":
                $suplier = "20000";
            break;
			
            case "2":
                $suplier = "50000";
            break;
			
			case "3":
                $suplier = "100000";
            break;		
			
			case "4":
                $suplier = "200000";
            break;
			
            case "5":
                $suplier = "500000";
            break;

            case "6":
                $suplier = "1000000";
            break;
        }
        return $suplier;
    }
	
	private function getCard($args){
		$args =$args/1000;
		$args = $args."k";
        return $args;
    }
	
	public function setLog($name, $mang, $menhgia, $seri, $pin,$k){		
		
		
		$card = $this->getCard($menhgia);
		$point = $menhgia/1000*$this->cfg->get("khuyen_mai");
		$ngay_nap = date("d-m-Y H:i:s");
		$length1 = 25 - strlen($name);
		$length2 = 10 - strlen($mang);
		$length3= 10 - strlen($card);
		$length4 = 30 - strlen($ngay_nap);
		$length5 = 12 - strlen($point);
		$length6 = 16 - strlen($seri);
		$length7 = 16 - strlen($pin);
		if($k){
		$kk = '\card_dung.log'	;
		}else{
		$kk = '\card_delay.log'	;
			$guithe = new NapTheNgay_API();
			$card = $guithe->getErrorMsg($menhgia);
			$length3 = 82 - strlen($card);
			$point = 0;
			$length5 = 0;
		}
		$fh = fopen($this->getDataFolder().$kk, "a") ;
		fwrite($fh,'-------------------------------------------------------------------------------------------------------------------------');
		fwrite($fh,"\r\n");
		fwrite($fh,"| ".$name.str_repeat(' ',$length1)."  |     ".$mang.str_repeat(' ',$length2)."  |".$seri.str_repeat(' ',$length6)."  |".$pin.str_repeat(' ',$length7)."|     ".$card.str_repeat(' ',$length3)."|      ".$point.str_repeat(' ',$length5)."|     ".$ngay_nap.str_repeat(' ',$length4)."|");
		fwrite($fh,"\r\n");
		fwrite($fh,'-------------------------------------------------------------------------------------------------------------------------');
		fwrite($fh,"\r\n");
		fclose($fh);
	}
	
    public static function sendTop($receiver, $data,  int $page = 1, Form $closeForm = null)
    {
        if ($receiver instanceof Player) $receiver = $receiver->getLowerCaseName();
        $server = Server::getInstance();
        $plugin = Main::getInstance();
        $banned = [];
        foreach ($server->getNameBans()->getEntries() as $entry) {
            if (isset($data[strtolower($entry->getName())])) {
                $banned[] = $entry->getName();
            }
        }
        $ops = [];
        foreach ($server->getOps()->getAll() as $op) {
            if (isset($data[strtolower($op)])) {
                $ops[] = $op;
            }
        }

        $task = new SortTask($receiver, $data, false, $page, $ops, $banned, $closeForm);
        $server->getAsyncPool()->submitTask($task);
    }	
/*

█▀▀ █▀▀█ █░░█ █▀▀█ █▀▀█ █▀▀▄   █▀▀█ █▀▀█ ░▀░
█░░ █░░█ █░░█ █░░█ █░░█ █░░█   █▄▄█ █░░█ ▀█▀
▀▀▀ ▀▀▀▀ ░▀▀▀ █▀▀▀ ▀▀▀▀ ▀░░▀   ▀░░▀ █▀▀▀ ▀▀▀

*/	
	public function getCP($player){
		$player = $this->fixName($player);
		return $this->coupon->exists($player) ? $this->coupon->get($player) : 0;
	}
	
	public function addCP($player, $cp, $reason){
		$player = $this->fixName($player);
		$this->coupon->set($player, $this->getCP($player) + $cp);
		$this->coupon->save();
		$fh = fopen($this->getDataFolder()."loggiaodich.log", "a");
		fwrite($fh,"$player đã nhận được $cp từ việc $reason. Số dư cuối: ". $this->getCP($player));
		fwrite($fh,"\r\n");
		fclose($fh);
	}

	public function reduceCP($player, $cp, $reason){
		$player = $this->fixName($player);
		if($this->getCP($player) >= $cp){
			$this->coupon->set($player, $this->getCP($player) - $cp);
			$this->coupon->save();
			$fh = fopen($this->getDataFolder()."loggiaodich.log", "a");
			fwrite($fh,"$player đã bị trừ $cp từ việc $reason. Số dư cuối: ". $this->getCP($player));
			fwrite($fh,"\r\n");
			fclose($fh);
			return true;
		}
		return false;
	}
	
	public function getVND($player){
		$player = $this->fixName($player);
		return $this->data->exists($player) ? $this->data->get($player) : 0;		
	}
	
	public function fixName($player){
		if($player instanceof Player){
			$player = $player->getName();
		}
		return strtolower($player);		
	}
//------------------------------------------------------------------------------------------------------
}

class NapTheNgay_API {
    
	private $input;
	private $status;
	private $result;
	
	public function getStatus(){
		return $this->status;
	}
	
	public function getResult(){
		return $this->result;
	}
	
	public function setInput($APIkey, $mathe, $seri, $mang, $menhgia){
		$this->input = array(
			'APIkey'=>trim($APIkey),
			'type'=>trim($mang),
			'menhgia'=> intval($menhgia),
			'mathe'=>trim($mathe),
			'SERI'=>trim($seri),
			'content'=>trim(time())
		);		
	}
	
	public function setPending($user, $transId){
		$pending = new Config(Main::getInstance()->getDataFolder() . 'pending.yml', Config::YAML, []);
		$all = $pending->getAll();
		
		$all[$transId]["user"] = $user;
		$all[$transId]["type"] = $this->input["type"];
		$all[$transId]["serial"] = $this->input["SERI"];
		$all[$transId]["code"] = $this->input["mathe"];
		$all[$transId]["amount"] = $this->input["menhgia"];
		$all[$transId]["status"] = 0;
		$pending->setAll($all);
		$pending->save();
		return $all;
	}
	
	public function cardCharging() {
		$arrayPost = $this->input;
		
       //$data = "https://thesieutoc.net/chargingws/v2?APIkey=".$arrayPost["APIkey"]."&mathe=".$arrayPost["mathe"]."&SERI=".$arrayPost["SERI"]."&type=".$arrayPost["type"]."&menhgia=".$arrayPost["menhgia"]."&content=".time()."";
	   $data = "https://doicard24h.net/api/card-auto.php?auto=true{$arrayPost["APIkey"]}&mathe={$arrayPost["mathe"]}&seri={$arrayPost["SERI"]}&type={$arrayPost["type"]}&menhgia={$arrayPost["menhgia"]}&content=".time();
	 // var_dump($data);
	    $curl = curl_init($data); 
	    curl_setopt($curl, CURLOPT_FAILONERROR, true); 
  	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
  	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, 1);		
		$data = curl_exec($curl);
		$this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$this->result = json_decode($data);
		//var_dump($this->status);		
		//var_dump($this->result);
    }
	
	public function getErrorMsg($code){
		$list = [
		"101" => "Dữ liệu DataSign không đúng.",
		"102" => "Mạng đang bảo trị hoặc sự cố",
		"103" => "Tài khoản không đúng hoặc đang bị khóa",
		"104" => "MerchantId không chính xác hoặc chưa kích hoạt",
		"105" => "Hệ thống quá tải",
		"106" => "Mệnh giá thẻ cào không được hỗ trợ, vui lòng liên hệ facebook.com/shin1134 để nạp",
		"107" => "Thẻ trễ hoặc hệ thống đang gặp sự cố",
		"108" => "Thông tin thẻ nạp không chính xác",
		"109" => "Nạp thẻ thành công nhưng sai mệnh giá nên không nhận được tiền",
		"110" => "Hệ thống quá tải",
		"111" => "Sai định dạng thẻ cào",
		"112" => "Nạp thẻ quá nhanh trong 1 phút",
		"113" => "Nạp sai liên tiếp quá nhiều lần. Tạm khóa",
		"114" => "Thẻ này đã nạp thành công vào hệ thống rồi.",	
		"0" => "Dữ liệu gửi lên không chính xác"
		];
		if(isset($list[$code])){
			return $list[$code];
		}
		return "lỗi không xác định";
	}
}

