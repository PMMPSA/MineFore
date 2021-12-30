<?php


namespace DOLNapTheUI;


use DOLNapTheUI\Main as DOLNapTheUI;
use pocketmine\form\CustomForm;
use pocketmine\form\element\Input;
use pocketmine\form\element\Label;
use pocketmine\form\Form;
use pocketmine\Player;

class TopForm extends CustomForm
{
    private $closeForm;

    public function __construct(Player $player, int $currentPage, int $maxPage, array $content, Form $closeForm = null)
    {
		$vnd = DOLNapTheUI::getInstance()->getVND($player);
		$vndk = ($vnd/1000) ."k"; 
        $elements = [
            new Label("§c§l● §eSố tiền bạn đã nạp:§3 " .  $vndk),
            new Label("§c§l● §bTrang§a $currentPage/$maxPage"),
        ];
        foreach ($content as $string) {
            $elements[] = new Label($string);
        }
        $elements[] = new Input("§l§aĐến trang");
        parent::__construct("§l§c●§6 Xếp Hạng Nạp Thẻ§r", $elements);
        $this->closeForm = $closeForm;
    }

    public function onSubmit(Player $player): ?Form
    {
        /** @var Input $pageInput */
        $elements = $this->getAllElements();
        $pageInput = $elements[count($elements) - 1];
        $page = (int)$pageInput->getValue();
		$data = DOLNapTheUI::getInstance()->data->getAll();
        DOLNapTheUI::sendTop($player, $data, $page, $this->closeForm);
        return null;
    }

    public function onClose(Player $player): ?Form
    {
        if ($this->closeForm instanceof Form) $player->sendForm($this->closeForm);
        return null;
    }
}