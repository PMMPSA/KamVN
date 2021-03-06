<?php

declare(strict_types=1);

namespace DaPigGuy\PiggyFactions\commands;

use DaPigGuy\PiggyFactions\libs\CortexPE\Commando\BaseCommand;
use DaPigGuy\PiggyFactions\libs\CortexPE\Commando\BaseSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\ChatSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\HelpSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\JoinSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\LeaveSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\management\BanSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\management\CreateSubCommand;;
use DaPigGuy\PiggyFactions\commands\subcommands\management\DisbandSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\management\InviteSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\management\KickSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\management\NameSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\management\UnbanSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\money\DepositSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\money\MoneySubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\money\WithdrawSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\PlayerSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\relations\AllySubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\roles\DemoteSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\roles\LeaderSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\roles\PromoteSubCommand;
use DaPigGuy\PiggyFactions\commands\subcommands\TopSubCommand;
use DaPigGuy\PiggyFactions\PiggyFactions;
use DaPigGuy\PiggyFactions\utils\ChatTypes;
use DaPigGuy\PiggyFactions\libs\jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class FactionCommand extends BaseCommand
{
    /** @var PiggyFactions */
    protected $plugin;

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if ($this->plugin->areFormsEnabled() && $sender instanceof Player) {
            $subcommands = array_filter($this->getSubCommands(), function (BaseSubCommand $subCommand, string $alias) use ($sender): bool {
                return $subCommand->getName() === $alias && $sender->hasPermission($subCommand->getPermission());
            }, ARRAY_FILTER_USE_BOTH);
            $form = new SimpleForm(function (Player $player, ?int $data) use ($subcommands): void {
                if ($data !== null) {
                    $subcommand = $subcommands[array_keys($subcommands)[$data]];
                    $subcommand->onRun($player, $subcommand->getName(), []);
                }
            });
            $form->setTitle($this->plugin->getLanguageManager()->getMessage($this->plugin->getLanguageManager()->getPlayerLanguage($sender), "forms.title"));
            foreach ($subcommands as $key => $subcommand) {
                $form->addButton(ucfirst($subcommand->getName()));
            }
            $sender->sendForm($form);
            return;
        }
        $this->sendUsage();
    }

    protected function prepare(): void
    {
        $this->setPermission("piggyfactions.command.faction.use");
        $this->registerSubCommand(new BanSubCommand($this->plugin, "ban", "C???m 1 th??nh vi??n trong clan"));
        $this->registerSubCommand(new ChatSubCommand($this->plugin, ChatTypes::FACTION, "chat", "B???t chat qu??n ??o??n", ["c"]));
        $this->registerSubCommand(new CreateSubCommand($this->plugin, "create", "T???o 1 clan"));
        $this->registerSubCommand(new DemoteSubCommand($this->plugin, "demote", "H??? ch???c th??nh vi??n trong clan"));
        if ($this->plugin->isFactionBankEnabled()) $this->registerSubCommand(new DepositSubCommand($this->plugin, "deposit", "G???i ti???n v??o ng??n h??ng clan"));
        $this->registerSubCommand(new DisbandSubCommand($this->plugin, "disband", "Gi???i t??n clan c???a b???n"));
        $this->registerSubCommand(new InviteSubCommand($this->plugin, "invite", "M???i th??nh vi??n v??o qu??n ??o??n c???a b???n"));
        $this->registerSubCommand(new JoinSubCommand($this->plugin, "join", "Tham gia 1 qu??n ??o??n"));
        $this->registerSubCommand(new KickSubCommand($this->plugin, "kick", "Kick th??nh vi??n kh???i qu??n ??o??n"));
        $this->registerSubCommand(new LeaderSubCommand($this->plugin, "leader", "Chuy???n quy???n th??? l??nh cho th??nh vi??n"));
        $this->registerSubCommand(new LeaveSubCommand($this->plugin, "leave", "Tho??t qu??n ??o??n"));
        if ($this->plugin->isFactionBankEnabled()) $this->registerSubCommand(new MoneySubCommand($this->plugin, "money", "Xem ti???n trong ng??n h??ng clan"));
        $this->registerSubCommand(new NameSubCommand($this->plugin, "name", "?????i t??n qu??n ??o??n"));
        $this->registerSubCommand(new PromoteSubCommand($this->plugin, "promote", "Th??ng ch???c th??nh vi??n qu??n ??o??n"));
        if ($this->plugin->isFactionBankEnabled()) $this->registerSubCommand(new WithdrawSubCommand($this->plugin, "withdraw", "R??t ti???n kh???i ng??n h??ng clan"));
    }
}