<?php

/*
 *  ___            __  __
 * |_ _|_ ____   _|  \/  | ___ _ __  _   _
 *  | || '_ \ \ / / |\/| |/ _ \ '_ \| | | |
 *  | || | | \ V /| |  | |  __/ | | | |_| |
 * |___|_| |_|\_/ |_|  |_|\___|_| |_|\__,_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Muqsit
 * @link http://github.com/Muqsit
 *
*/

namespace muqsit\invmenu\inventories;

use muqsit\invmenu\InvMenu;

use pocketmine\block\Block;
use pocketmine\inventory\ContainerInventory;
use pocketmine\math\Vector3;
use pocketmine\nbt\NetworkLittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\Player;

abstract class BaseFakeInventory extends ContainerInventory {

    const SEND_BLOCKS_FAKE = 0;
    const SEND_BLOCKS_REAL = 1;

    const FAKE_BLOCK_ID = 0;
    const FAKE_BLOCK_DATA = 0;
    const FAKE_TILE_ID = "";

    const INVENTORY_HEIGHT = 3;

    /** @var Vector3[] */
    protected $holders = [];

    /** @var InvMenu */
    protected $menu;

    /** @var BigEndianNBTStream|null */
    protected static $nbtWriter;

    public function __construct(InvMenu $menu)
    {
        $this->menu = $menu;
        parent::__construct(new Vector3());
    }

    public function getMenu() : InvMenu
    {
        return $this->menu;
    }

    public function onOpen(Player $player) : void
    {	
        if (!isset($this->holders[$id = $player->getId()])) {
            $this->holders[$id] = $this->holder = $player->floor()->add(0, static::INVENTORY_HEIGHT, 0);
            $this->sendBlocks($player, self::SEND_BLOCKS_FAKE);

            $this->sendFakeTile($player);
            parent::onOpen($player);
        }
    }

    public function onClose(Player $player) : void
    {
        if (isset($this->holders[$id = $player->getId()])) {
            parent::onClose($player);

            $this->sendBlocks($player, self::SEND_BLOCKS_REAL);
            $this->menu->onInventoryClose($player);

            unset($this->holders[$id]);
        }
    }

    protected function sendFakeTile(Player $player) : void
    {
        $holder = $this->holders[$player->getId()];

        $pk = new BlockActorDataPacket();
        $pk->x = $holder->x;
        $pk->y = $holder->y;
        $pk->z = $holder->z;

        $tag = new CompoundTag();
        $tag->setString("id", static::FAKE_TILE_ID);

        $customName = $this->menu->getName();
        if ($customName !== null) {
            $tag->setString("CustomName", $customName);
        }

        $pk->namedtag = (self::$nbtWriter ?? (self::$nbtWriter = new NetworkLittleEndianNBTStream()))->write($tag);
        $player->dataPacket($pk);
    }

    protected function sendBlocks(Player $player, int $type) : void
    {
        switch ($type) {
            case self::SEND_BLOCKS_FAKE:
                $player->getLevel()->sendBlocks([$player], $this->getFakeBlocks($this->holders[$player->getId()]));
                return;
            case self::SEND_BLOCKS_REAL:
                $player->getLevel()->sendBlocks([$player], $this->getRealBlocks($player, $this->holders[$player->getId()]));
                return;
        }

        throw new \Error("Unhandled type $type provided.");
    }

    protected function getFakeBlocks(Vector3 $holder) : array
    {
        return [
            Block::get(static::FAKE_BLOCK_ID, static::FAKE_BLOCK_DATA)->setComponents($holder->x, $holder->y, $holder->z)
        ];
    }

    protected function getRealBlocks(Player $player, Vector3 $holder) : array
    {
        return [
            $player->getLevel()->getBlockAt($holder->x, $holder->y, $holder->z)
        ];
    }
}