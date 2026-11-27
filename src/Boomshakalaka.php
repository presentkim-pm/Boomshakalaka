<?php

/**
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  PresentKim (debe3721@gmail.com)
 * @link    https://github.com/PresentKim
 * @license https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 *
 * @noinspection PhpDocSignatureInspection
 * @noinspection SpellCheckingInspection
 * @noinspection PhpUnusedParameterInspection
 */

declare(strict_types=1);

namespace kim\present\test;

use Closure;
use pocketmine\block\Block;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\TNT;
use pocketmine\entity\Location;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\SpawnEgg;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

use function count;
use function is_dir;
use function rmdir;
use function scandir;

final class Boomshakalaka extends PluginBase implements Listener{
    protected function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        /**
         * This is a plugin that does not use data folders.
         * Delete the unnecessary data folder of this plugin for users.
         */
        $dataFolder = $this->getDataFolder();
        if(is_dir($dataFolder)){
            $entries = scandir($dataFolder);
            if($entries !== false && count($entries) <= 2){
                @rmdir($dataFolder);
            }
        }
    }

    /** @return (Closure(Player, Item) : void)|null the handler of the given item, or null if the item does nothing */
    private function getUseHandler(Item $item) : ?Closure{
        return match(true){
            $item instanceof ItemBlock && $item->getBlock() instanceof TNT => $this->useTnt(...),
            $item instanceof SpawnEgg => $this->useSpawnEgg(...),
            $item->getTypeId() === ItemTypeIds::DIAMOND_SWORD => $this->useDiamondSword(...),
            default => null
        };
    }

    /** Spawns a primed TNT on the ground along the player's line of sight */
    private function useTnt(Player $player, Item $_) : void{
        $i = 0;
        foreach($player->getLineOfSight(120) as $block){
            $block = $this->dropToGround($block);
            $fuse = (int) (10 + (++$i / 2));
            $entity = new PrimedTNT(
                Location::fromObject($block->getPosition(), $player->getWorld()),
                CompoundTag::create()->setShort("Fuse", $fuse)
            );
            $entity->spawnToAll();
        }
    }

    /** Uses the spawn egg on the ground along the player's line of sight */
    private function useSpawnEgg(Player $player, Item $item) : void{
        if(!$item instanceof SpawnEgg){
            return;
        }

        foreach($player->getLineOfSight(40) as $block){
            $block = $this->dropToGround($block);

            $returnedItems = [];
            (clone $item)->onInteractBlock($player, $block, $block, Facing::UP, new Vector3(0, 0, 0), $returnedItems);
        }
    }

    /** Despawns every entity of the world except the players */
    private function useDiamondSword(Player $player, Item $_) : void{
        foreach($player->getWorld()->getEntities() as $entity){
            if(!$entity instanceof Player){
                $entity->flagForDespawn();
            }
        }
    }

    /** Moves down from the given block until the block below it is not air */
    private function dropToGround(Block $block) : Block{
        $minY = $block->getPosition()->getWorld()->getMinY();
        while($block->getPosition()->y > $minY && $block->getSide(Facing::DOWN)->getTypeId() === BlockTypeIds::AIR){
            $block = $block->getSide(Facing::DOWN);
        }
        return $block;
    }

    public function onPlayerItemUse(PlayerItemUseEvent $event) : void{
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $handler = $this->getUseHandler($item);
        if($handler !== null){
            $event->cancel();
            $handler($player, $item);
        }
    }
}
