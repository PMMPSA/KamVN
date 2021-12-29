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
        $this->registerSubCommand(new BanSubCommand($this->plugin, "ban", "Cấm 1 thành viên trong clan"));
        $this->registerSubCommand(new ChatSubCommand($this->plugin, ChatTypes::FACTION, "chat", "Bật chat quân đoàn", ["c"]));
        $this->registerSubCommand(new CreateSubCommand($this->plugin, "create", "Tạo 1 clan"));
        $this->registerSubCommand(new DemoteSubCommand($this->plugin, "demote", "Hạ chức thành viên trong clan"));
        if ($this->plugin->isFactionBankEnabled()) $this->registerSubCommand(new DepositSubCommand($this->plugin, "deposit", "Gửi tiền vào ngân hàng clan"));
        $this->registerSubCommand(new DisbandSubCommand($this->plugin, "disband", "Giải tán clan của bạn"));
        $this->registerSubCommand(new InviteSubCommand($this->plugin, "invite", "Mời thành viên vào quân đoàn của bạn"));
        $this->registerSubCommand(new JoinSubCommand($this->plugin, "join", "Tham gia 1 quân đoàn"));
        $this->registerSubCommand(new KickSubCommand($this->plugin, "kick", "Kick thành viên khỏi quân đoàn"));
        $this->registerSubCommand(new LeaderSubCommand($this->plugin, "leader", "Chuyển quyền thủ lĩnh cho thành viên"));
        $this->registerSubCommand(new LeaveSubCommand($this->plugin, "leave", "Thoát quân đoàn"));
        if ($this->plugin->isFactionBankEnabled()) $this->registerSubCommand(new MoneySubCommand($this->plugin, "money", "Xem tiền trong ngân hàng clan"));
        $this->registerSubCommand(new NameSubCommand($this->plugin, "name", "Đổi tên quân đoàn"));
        $this->registerSubCommand(new PromoteSubCommand($this->plugin, "promote", "Thăng chức thành viên quân đoàn"));
        if ($this->plugin->isFactionBankEnabled()) $this->registerSubCommand(new WithdrawSubCommand($this->plugin, "withdraw", "Rút tiền khỏi ngân hàng clan"));
    }
}