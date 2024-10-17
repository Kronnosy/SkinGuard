<?php

/***
 *     .oooooo..o oooo         o8o                .oooooo.                                         .o8
 *    d8P'    `Y8 `888         `"'               d8P'  `Y8b                                       "888
 *    Y88bo.       888  oooo  oooo  ooo. .oo.   888           oooo  oooo   .oooo.   oooo d8b  .oooo888
 *     `"Y8888o.   888 .8P'   `888  `888P"Y88b  888           `888  `888  `P  )88b  `888""8P d88' `888
 *         `"Y88b  888888.     888   888   888  888     ooooo  888   888   .oP"888   888     888   888
 *    oo     .d8P  888 `88b.   888   888   888  `88.    .88'   888   888  d8(  888   888     888   888
 *    8""88888P'  o888o o888o o888o o888o o888o  `Y8bood8P'    `V88V"V8P' `Y888""8o d888b    `Y8bod88P"
 *
 *
 *    This plugin is open source, allowing you to modify and duplicate it as you wish.
 *    Feel free to customize it according to your needs and contribute to its development.
 *    Your feedback and improvements are always welcome!
 *
 *    @name SkinGuard
 *    @author Kronnosy
 *    @version 1.0.0
 */

namespace Kronnosy\SkinGuard;

use pocketmine\
{
    entity\Skin,
    event\Listener,
    plugin\PluginBase as Base,
    event\player\PlayerLoginEvent,
    event\player\PlayerChangeSkinEvent
};

class SkinGuard extends Base implements Listener
{

    /**
     * @return void
     */
    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(
            $this,
            $this
        );

        parent::onEnable();
    }

    /**
     * @param PlayerLoginEvent $event
     * @return void
     */
    public function onPlayerLoginEvent(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();
        $skin = $player->getSkin();

        if ($this->isSkinInvalid($skin)) {
            $player->kick(
                "SkinGuard: Your skin is invalid!"
            );
        }
    }

    /**
     * @param PlayerChangeSkinEvent $event
     * @return void
     */
    public function onPlayerChangeSkinEvent(PlayerChangeSkinEvent $event): void
    {
        $event->cancel();
    }

    /**
     * @param Skin $skin
     * @return bool
     */
    private function isSkinInvalid(Skin $skin): bool
    {
        $skinData = $skin->getSkinData();
        $skinSize = strlen($skinData);

        $minSize = 32 * 32 * 4;
        $maxSize = 64 * 64 * 4;

        $isInvisible = true;
        for ($i = 0; $i < $skinSize; $i += 4) {
            if (ord($skinData[$i + 3]) > 0) {
                $isInvisible = false;
                break;
            }
        }

        return $skinSize < $minSize || $skinSize > $maxSize || $isInvisible;
    }

}
