<?php

namespace MuaDoUI;

use pocketmine\{Player, Server};
use pocketmine\plugin\PluginBase;
use pocketmine\utils\{TextFormat};
use pocketmine\item\Item;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};
use pocketmine\level\Level;
use pocketmine\event\Listener;
use pocketmine\command\{Command, CommandSender, CommandExecutor, ConsoleCommandSender};
use jojoe77777\FormAPI;
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener{

	public $eco;
	
	public function onEnable(){
		$this->getLogger()->info("MuaDoUI Load....");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$ce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
		$this->eco =  $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		$player = $sender->getPlayer();
		switch ($cmd->getName()){
			case "muado":
				$this->mainForm($player);
			break;
		}
		return true;
    }
    
	public function mainForm($player){

		$form = $this->formapi->createSimpleForm(function (Player $player, $result){

			if($result === null){
				return false;
			}
			switch($result){
				case 0:
				break;
				case 1:
					$this->muadoForm($player);
				break;
				default:
				break;
				
		}
		});
		$form->setTitle("§l§6♦§d Mua Đồ §6♦");
	    $form->setContent("§l§3●§e Những món đồ quý sẽ được bạn tại đây!");
		$form->addButton("§l§3● §cThoát §3●", 1, 'http://minefore.tk/png/exit.png');
		$form->addButton("§l§3● §aDanh Sách §3●", 1, 'http://minefore.tk/png/list.png');
		$form->sendToPlayer($player);
		
	}
	
	public function muadoForm($sender){

		$form = $this->formapi->createSimpleForm(function (Player $player,$result){

			if($result === null){
				return false;
			}
			switch($result){
				case 0:

				break;
				case 1:
					if($this->eco->myMoney($player) >= 20000000){
						$item = Item::get(276, 0, 1);
						$ce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
						$ce->addEnchantment($item, ["POISON", "DEATHBRINGER", "WITHER", "BLESSED"], [2, 2, 2, 2], true);
						$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9),30));
						$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17),30));
						$item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(10),4));
						$item->setCustomName("§l§6●▬▬ §d【§6Kiếm §cNoel§d】 ▬▬§6●§r");
						$item->setLore(array("§l§3● §eVật phẩm được tinh luyện vào §bnoel\n§l§3● §eVật phẩm của: §2".$player->getName()."\n§l§3● §eTrị giá:§c 20.000.000 Xu\n§l§3● §6Độ hiếm:§d ★"));	
						$player->getInventory()->addItem($item);
						$this->eco->reduceMoney($player, 5000000);
						$player->addTitle("§l§6♦§a Đã Mua Thành Công §6♦");
					} else{
						$player->sendMessage("§l§6[§bMine§aFore§6]§e Bạn không đủ tiền để mua vật phẩm!");
					}
				break;
				case 2:
					if($this->eco->myMoney($player) >= 40000000){

						$item2 = Item::get(276, 0, 1);
						$ce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
						$ce->addEnchantment($item2, ["POISON", "WITHER", "LIFESTEAL", "DEATHBRINGER", "BLESSED", "CHARGE"], [2, 2, 2, 2, 2, 2], true);
						$item2->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9),60));
						$item2->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17),60));
						$item2->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(10),2));
						$item2->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(13),2));
						$item2->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(12),2));
						$item2->setCustomName("§l§6●▬▬ §d【§6Kiếm §cBóng Tối§d】 ▬▬§6●§r");
						$item2->setLore(array("§l§3● §eVật phẩm được tinh luyện bởi§c quỷ satan\n§l§3● §eVật phẩm của: §2".$player->getName()."\n§l§3● §eTrị giá:§c 40.000.000 Xu\n§l§3● §6Độ hiếm:§d ★★"));
						$player->getInventory()->addItem($item2);
						$this->eco->reduceMoney($player, 10000000);
						$player->addTitle("§l§6♦§a Đã Mua Thành Công §6♦");
					} else{
						$player->sendMessage("§l§6[§bMine§aFore§6]§e Bạn không đủ tiền để mua vật phẩm!");
					}
				break;
				case 5:
					if($this->eco->myMoney($player) >= 40000000){

						$item3 = Item::get(278, 0, 1);
						$ce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
						$ce->addEnchantment($item3, ["ENERGIZING", "QUICKENING"], [3, 3], true);
						$item3->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15),200));
						$item3->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17),200));
						$item3->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(18),200));
						$item3->setCustomName("§l§6●▬▬ §d【§6Cúp §cTà Thần§d】 ▬▬§6●§r");
						$item3->setLore(array("§l§3● §eVật phẩm được tinh luyện bởi§c tà thần\n§l§3● §eVật phẩm của: §2".$player->getName()."\n§l§3● §eTrị giá:§c 40.000.000 Xu\n§l§3● §6Độ hiếm:§d ★★★★"));
						$player->getInventory()->addItem($item3);
						$this->eco->reduceMoney($player, 40000000);
						$player->addTitle("§l§6♦§a Đã Mua Thành Công §6♦");
					} else{
						$player->sendMessage("§l§6[§bMine§aFore§6]§e Bạn không đủ tiền để mua vật phẩm!");
					}
				break;
				case 6:
					if($this->eco->myMoney($player) >= 100000000){
						$item4 = Item::get(278, 0, 1);
						$ce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
						$ce->addEnchantment($item4, ["HASTE"], [5], true);
						$item4->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15),500));
						$item4->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17),500));
						$item4->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(18),500));
						$item4->setCustomName("§l§6●▬▬ §d【§6Cúp §bMine§aFore§d】 ▬▬§6●§r");
						$item4->setLore(array("§l§3● §eVật phẩm được tinh luyện bởi§b Mine§aFore\n§l§3● §eVật phẩm của: §2".$player->getName()."\n§l§3● §eTrị giá:§c 100.000.000 Xu\n§l§3● §6Độ hiếm:§b Cực Hiếm!"));
						$player->getInventory()->addItem($item4);
						$this->eco->reduceMoney($player, 100000000);
						$player->addTitle("§l§6♦§a Đã Mua Thành Công §6♦");
					} else{
						$player->sendMessage("§l§6[§bMine§aFore§6]§e Bạn không đủ tiền để mua vật phẩm!");
					}
				break;
				case 4:
					if($this->eco->myMoney($player) >= 20000000){
						$item5 = Item::get(278, 0, 1);
						$ce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
						$ce->addEnchantment($item5, ["ENERGIZING"], [1], true);
						$item5->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(15),100));
						$item5->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17),100));
						$item5->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(18),100));
						$item5->setCustomName("§l§6●▬▬ §d【§6Cúp §cRohan§d】 ▬▬§6●§r");
						$item5->setLore(array("§l§3● §eVật phẩm được tinh luyện tại§c Rohan\n§l§3● §eVật phẩm của: §2".$player->getName()."\n§l§3● §eTrị giá:§c 20.000.000 Xu\n§l§3● §6Độ hiếm:§d ★★★"));
						$player->getInventory()->addItem($item5);
						$this->eco->reduceMoney($player, 20000000);
						$player->addTitle("§l§6♦§a Đã Mua Thành Công §6♦");
					} else{
						$player->sendMessage("§l§6[§bMine§aFore§6]§e Bạn không đủ tiền để mua vật phẩm!");
					}
				break;
				case 3:
					if($this->eco->myMoney($player) >= 100000000){

						$item6 = Item::get(276, 0, 1);
						$ce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
						$ce->addEnchantment($item6, ["POISON", "WITHER", "LIFESTEAL", "CRIPPLINGSTRIKE", "BACKSTAB", "BLIND", "VAMPIRE", "DEATHBRINGER", "BLESSED", "CHARGE"], [3, 3, 3, 3, 3, 3, 3, 3, 3, 3], true);
						$item6->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(9),100));
						$item6->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17),500));
						$item6->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(10),100));
						$item6->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(13),5));
						$item6->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(12),5));
						$item6->setCustomName("§l§6●▬▬ §d【§6Kiếm §bMine§aFore§d】 ▬▬§6●§r");
						$item6->setLore(array("§l§3● §eVật phẩm được tinh luyện bởi§b Mine§aFore\n§l§3● §eVật phẩm của: §2".$player->getName()."\n§l§3● §eTrị giá:§c 100.000.000 Xu\n§l§3● §6Độ hiếm:§b Cực Hiếm!"));
						$player->getInventory()->addItem($item6);
						$this->eco->reduceMoney($player, 100000000);
						$player->addTitle("§l§6♦§a Đã Mua Thành Công §6♦");
					} else{
						$player->sendMessage("§l§6[§bMine§aFore§6]§e Bạn không đủ tiền để mua vật phẩm!");
					}
				break;
				case 7:
					if($this->eco->myMoney($player) >= 100000000){

						$item7 = Item::get(310, 0, 1);
						$ce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
						$ce->addEnchantment($item7, ["BERSERKER", "DRUNK", "FROZEN", "HEAVY", "CURSED", "SHIELDED", "TANK", "ENDERSHIFT", "ENLIGHTED", "HARDENED"], [3, 3, 3, 3, 3, 3, 3, 3, 3, 3], true);
						$item7->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0),100));
						$item7->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17),500));
						$item7->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(5),10));
						$item7->setCustomName("§l§6●▬▬ §d【§6Nón §bMine§aFore§d】 ▬▬§6●§r");
						$item7->setLore(array("§l§3● §eVật phẩm được tinh luyện bởi§b Mine§aFore\n§l§3● §eVật phẩm của: §2".$player->getName()."\n§l§3● §eTrị giá:§c 100.000.000 Xu\n§l§3● §6Độ hiếm:§b Cực Hiếm!"));
						$player->getInventory()->addItem($item7);
						$this->eco->reduceMoney($player, 100000000);
						$player->addTitle("§l§6♦§a Đã Mua Thành Công §6♦");
					} else{
						$player->sendMessage("§l§6[§bMine§aFore§6]§e Bạn không đủ tiền để mua vật phẩm!");
					}
				break;
				case 8:
					if($this->eco->myMoney($player) >= 100000000){

						$item8 = Item::get(311, 0, 1);
						$ce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
						$ce->addEnchantment($item8, ["BERSERKER", "DRUNK", "FROZEN", "HEAVY", "CURSED", "SHIELDED", "TANK", "ENDERSHIFT", "ENLIGHTED", "HARDENED"], [3, 3, 3, 3, 3, 3, 3, 3, 3, 3], true);
						$item8->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0),100));
						$item8->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17),500));
						$item8->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(5),10));
						$item8->setCustomName("§l§6●▬▬ §d【§6Aó §bMine§aFore§d】 ▬▬§6●§r");
						$item8->setLore(array("§l§3● §eVật phẩm được tinh luyện bởi§b Mine§aFore\n§l§3● §eVật phẩm của: §2".$player->getName()."\n§l§3● §eTrị giá:§c 100.000.000 Xu\n§l§3● §6Độ hiếm:§b Cực Hiếm!"));
						$player->getInventory()->addItem($item8);
						$this->eco->reduceMoney($player, 100000000);
						$player->addTitle("§l§6♦§a Đã Mua Thành Công §6♦");
					} else{
						$player->sendMessage("§l§6[§bMine§aFore§6]§e Bạn không đủ tiền để mua vật phẩm!");
					}
				break;
				case 9:
					if($this->eco->myMoney($player) >= 100000000){

						$item9 = Item::get(312, 0, 1);
						$ce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
						$ce->addEnchantment($item9, ["BERSERKER", "DRUNK", "FROZEN", "HEAVY", "CURSED", "SHIELDED", "TANK", "ENDERSHIFT", "ENLIGHTED", "HARDENED"], [3, 3, 3, 3, 3, 3, 3, 3, 3, 3], true);
						$item9->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0),100));
						$item9->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17),500));
						$item9->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(5),10));
						$item9->setCustomName("§l§6●▬▬ §d【§6Quần §bMine§aFore§d】 ▬▬§6●§r");
						$item9->setLore(array("§l§3● §eVật phẩm được tinh luyện bởi§b Mine§aFore\n§l§3● §eVật phẩm của: §2".$player->getName()."\n§l§3● §eTrị giá:§c 100.000.000 Xu\n§l§3● §6Độ hiếm:§b Cực Hiếm!"));
						$player->getInventory()->addItem($item9);
						$this->eco->reduceMoney($player, 100000000);
						$player->addTitle("§l§6♦§a Đã Mua Thành Công §6♦");
					} else{
						$player->sendMessage("§l§6[§bMine§aFore§6]§e Bạn không đủ tiền để mua vật phẩm!");
					}
				break;
				case 10:
					if($this->eco->myMoney($player) >= 100000000){

						$item10 = Item::get(313, 0, 1);
						$ce = $this->getServer()->getPluginManager()->getPlugin("PiggyCustomEnchants");
						$ce->addEnchantment($item10, ["BERSERKER", "DRUNK", "FROZEN", "HEAVY", "CURSED", "SHIELDED", "TANK", "ENDERSHIFT", "ENLIGHTED", "HARDENED"], [3, 3, 3, 3, 3, 3, 3, 3, 3, 3], true);
						$item10->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(0),100));
						$item10->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(17),500));
						$item10->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(5),10));
						$item10->setCustomName("§l§6●▬▬ §d【§6Giày §bMine§aFore§d】 ▬▬§6●§r");
						$item10->setLore(array("§l§3● §eVật phẩm được tinh luyện bởi§b Mine§aFore\n§l§3● §eVật phẩm của: §2".$player->getName()."\n§l§3● §eTrị giá:§c 100.000.000 Xu\n§l§3● §6Độ hiếm:§b Cực Hiếm!"));
						$player->getInventory()->addItem($item10);
						$this->eco->reduceMoney($player, 100000000);
						$player->addTitle("§l§6♦§a Đã Mua Thành Công §6♦");
					} else{
						$player->sendMessage("§l§6[§bMine§aFore§6]§e Bạn không đủ tiền để mua vật phẩm!");
					}
				break;
				default:
				break;				
			}
		 });

		 $form->setTitle("§l§6♦§d Mua Đồ §6♦");
		 $form->setContent("§l§3●§e Những món đồ quý sẽ được bạn tại đây!");
		 $form->addButton("§l§3● §cThoát §3●", 1, 'http://minefore.tk/png/exit.png');	
		 $form->addButton("§l§3●§c Kiếm Noel §3●\n§d【§620.000.000 Xu§d】§r", 1, 'http://minefore.tk/png/noelsword.png');	
		 $form->addButton("§l§3●§c Kiếm Bóng Tối §3●\n§d【§640.000.000 Xu§d】§r", 1, 'http://minefore.tk/png/blacksword.png');	
		 $form->addButton("§l§3●§c Kiếm MineFore §3●\n§d【§6100.000.000 Xu§d】§r", 1, 'http://minefore.tk/png/mfsword.png');	
		 $form->addButton("§l§3●§c Cúp Rohan §3●\n§d【§620.000.000 Xu§d】§r", 1, 'http://minefore.tk/png/rohanpick.png');	
		 $form->addButton("§l§3●§c Cúp Tà Thần §3●\n§d【§640.000.000 Xu§d】§r", 1, 'http://minefore.tk/png/evilpick.png');	
		 $form->addButton("§l§3●§c Cúp MineFore §3●\n§d【§6100.000.000 Xu§d】§r", 1, 'http://minefore.tk/png/mfpick.png');	
		 $form->addButton("§l§3●§c Nón MineFore §3●\n§d【§6100.000.000 Xu§d】§r", 1, 'http://minefore.tk/png/helmetmf.png');	
		 $form->addButton("§l§3●§c Aó MineFore §3●\n§d【§6100.000.000 Xu§d】§r", 1, 'http://minefore.tk/png/kit.png');	
		 $form->addButton("§l§3●§c Quần MineFore §3●\n§d【§6100.000.000 Xu§d】§r", 1, 'http://minefore.tk/png/pantsmf.png');	
		 $form->addButton("§l§3●§c Giày MineFore §3●\n§d【§6100.000.000 Xu§d】§r", 1, 'http://minefore.tk/png/shoemf.png');	
		 $form->sendToPlayer($sender);
 
	}
}