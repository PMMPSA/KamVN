<?php
declare(strict_types = 1);

/**
 * @name CoinApiAddon
 * @version 1.0.0
 * @main JackMD\ScoreHud\Addons\CoinApiAddon
 * @depend CoinAPI
 */
namespace JackMD\ScoreHud\Addons
{
	use JackMD\ScoreHud\addon\AddonBase;
	use onebone\coinapi\CoinAPI;
	use pocketmine\Player;

	class CoinApiAddon extends AddonBase{

		/** @var CoinAPI */
		private $coinAPI;

		public function onEnable(): void{
			$this->coinAPI = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
		}

		/**
		 * @param Player $player
		 * @return array
		 */
		public function getProcessedTags(Player $player): array{
			return [
				"{coin}" => $this->coinAPI->myCoin($player)
			];
		}
	}
}