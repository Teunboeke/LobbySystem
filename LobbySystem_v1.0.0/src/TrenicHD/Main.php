<?php
                                                                                                                 
namespace TrenicHD;

use pocketmine\block\Sandstone;
use pocketmine\entity\Effect;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\level\Position;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\ClickSound;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\level\sound\GhastShootSound;
use pocketmine\level\sound\PopSound;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacketV2;
use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use jojoe77777\FormAPI;
use pocketmine\entity\EffectInstance;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\level\sound\AnvilUseSound;
use TrenicHD\tasks\ScoreboardTask;
use TrenicHD\utils\Scoreboard;

class Main extends PluginBase implements Listener {

    public $prefix = "BoekeCraft"; //*<----- Your server name here!//*

    public function onLoad(): void
    {
        $this->getLogger()->warning("§bLobbySystem will be loaded...");
        $this->getLogger()->warning("§r§bScoreboard will be loaded....");
        $this->getScheduler()->scheduleRepeatingTask(new ScoreboardTask($this), 20);
        $this->getLogger()->warning("§b§lLoading config...");
        $this->getConfig();
        $this->saveResource("config.yml");
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);

        $config->save();
    }

    public function onEnable(): void
    {

        $this->getLogger()->warning("§r§aScoreboard will be loaded.");
        $this->getConfig();
        $this->saveResource("config.yml");
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        if (empty($config->get("Prefix"))) {
            $config->set("#", "Config by Teunboeke");
            $config->set("Prefix" , "§6[BoekeCraft]");
            $config->set("1", "#Settings 2 = 1 Herz/Hungerbalken");
            $config->set("heal", 20);
            $config->set("food", 20);
            $config->set("2", "#level mind. 0 - 24791");
            $config->set("level", 2020);
            $config->set("#13", "#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷");
            $config->set("#14", "#̷#̷#̷#̷#̷#̷#̷#Server Settings#̷#̷#̷#̷#̷#̷#̷##̷#̷#̷#̷");
            $config->set("info4", "#################################");
            $config->set("info5", "Do you have waterdog activated? -> transfer");
            $config->set("info6", "#################################");
            $config->set("server1", "transferserver 167.86.113.158 2367");
            $config->set("server2", "transferserver ");
            $config->set("server3", "transferserver ");
            $config->set("server4", "transferserver ");
            $config->set("#15", "#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷");
            $config->set("#16", "#̷#̷#̷#̷#̷#̷#̷#Server Name#̷#̷#̷#̷#̷#̷#̷##̷#̷#̷#̷");
            $config->set("name1", "§aSkyBlock");
            $config->set("name2", "§cComingSoon");
            $config->set("name3", "§cComingSoon");
            $config->set("name4", "§cComingSoon");
            $config->set("#17", "#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷");
            $config->set("#18", "#̷#̷#̷#̷#̷#̷#̷#Lobbys#̷#̷#̷#̷#̷#̷#̷##̷#̷#̷#̷");
            $config->set("info1", "#################################");
            $config->set("info2", "Do you have waterdog activated? -> transfer");
            $config->set("info3", "#################################");
            $config->set("lobby1", "transferserver ");
            $config->set("lobby2", "transferserver ");
            $config->set("#19", "#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷");
            $config->set("#20", "#̷#̷#̷#̷#̷#̷#̷#Lobby name#̷#̷#̷#̷#̷#̷#̷##̷#̷#̷#̷");
            $config->set("lobbyname", "lobby-1");
            $config->set("lobbyname2", "lobby-2");
            $config->set("#21", "#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷");
            $config->set("#22", "WARNING plugin is using v1");
            $config->set("#23", "update will come soon!");
            $config->set("#24", "Any problems fill free to dm me on discord Teunboeke#3019");
            $config->set("#26", "#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷#̷");



        }
        $this->getLogger()->warning("§a§lConfig will be loaded!");
        $this->getLogger()->info(TextFormat::GOLD . "LobbySystem by Teunboeke & TrenicHD");
        
        $config->save();

        $this->saveResource("config.yml");
        @mkdir($this->getDataFolder());
        $this->prefix = $config->get("Prefix");
        $this->getServer()->getPluginManager()->registerEvents($this , $this);

    }

    public function onDisable()
    {
        $this->getConfig();
        $this->getLogger()->warning(TextFormat::AQUA . "Config will be disabled.");
        $this->saveResource("config.yml");
        $this->getLogger()->warning(TextFormat::AQUA . "Config will be disabled.");
        $this->getLogger()->warning("§r§bScoreboard will be disabled...");
        $this->getLogger()->warning("§r§bScoreboard will be disabled...");
        $this->getLogger()->info(TextFormat::GOLD . "LobbySystem will be disabled....");
        $this->getLogger()->error(TextFormat::DARK_RED . "LobbyCore will be disabled");                                                                                                                   
        $this->getLogger()->error("Plugin Disabled!");
    }

    public function scoreboard() : void
    {
        foreach ($this->getServer()->getOnlinePlayers() as $online)
        {
            $config = new Config($this->getDataFolder() . $online->getName() . ".yml", Config::YAML);
            Scoreboard::removeScoreboard($online, "BoekeCraft");
            Scoreboard::createScoreboard($online, "$this->prefix", "BoekeCraft");
            Scoreboard::setScoreboardEntry($online, 1, "   ", "BoekeCraft");
            Scoreboard::setScoreboardEntry($online, 2, "§r§fPlayers:", "BoekeCraft");
            Scoreboard::setScoreboardEntry($online, 3, "§r   " . $online->getNameTag(), "BoekeCraft");
        }
    }



    public function onDrop(PlayerDropItemEvent $event)
    {
        $event->setCancelled(true);
        $event->getPlayer()->sendMessage(TextFormat::RED . "$this->prefix You can't do that here... :c");
    }

    public function onJoinPlayer(PlayerJoinEvent $event){
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $player = $event->getPlayer();
        $name = $event->getPlayer()->getName();
        $event->setJoinMessage("$this->prefix §6[§r+§6] $name");
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->setItem(0, Item::get(369)->setCustomName("§r§6Show §2Players"));
        $player->getInventory()->setItem(2, Item::get(341)->setCustomName("§r§aLobby"));
        $player->getInventory()->setItem(4, Item::get(345)->setCustomName("§r§6Compass"));
        $player->getInventory()->setItem(8, Item::get(130)->setCustomName("§r§5Features"));
        $player->getInventory()->setItem(6, Item::get(399)->setCustomName("§r§aInfo"));
        $player->getInventory()->setItem(1, Item::get(160)->setCustomName(" "));
        $player->getInventory()->setItem(3, Item::get(160)->setCustomName(" "));
        $player->getInventory()->setItem(5, Item::get(160)->setCustomName(" "));
        $player->getInventory()->setItem(7, Item::get(160)->setCustomName(" "));
        $player->setXpLevel($config->get("level"));
        $player->setFood($config->get("food"));
        $player->setMaxHealth($config->get("heal"));
        $player->setHealth($config->get("heal"));
        $player->getlevel()->addSound(new AnvilUseSound($player));

    }


    public function onPlayerQuit(PlayerQuitEvent $event){
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $name = $event->getPlayer()->getName();
        $event->setQuitMessage("$this->prefix §6[§r-§6] $name");
        $player = $event->getPlayer();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->clearAll();
        $player->setXpLevel(0);
        $player->sendMessage($config->get("Prefix"));
    }

    public function Hunger(PlayerExhaustEvent $event){

        $event->setCancelled(true);
    }

    public function onFallDamage(EntityDamageEvent $event){

        $event->setCancelled(true);

    }

    public function onPlayerDamage(EntityDamageEvent $event){

        $event->setCancelled(true);
   
    }
                   
    public function onInteract(PlayerInteractEvent $ev)
    {
        $player = $ev->getPlayer();
            $item = $ev->getItem();
            if ($item->getCustomName() == "§r§6Compass") {
                $this->Compass($player);
            } elseif ($item->getCustomName() == "§r§aInfo") {
                $this->Info($player);
            }elseif ($item->getCustomName() == "§r§6Show §2Players"){
                $this->SpielerAnzeigen($player);
            }elseif ($item->getCustomName() == "§r§aLobby"){
                $this->Lobby($player);
            }elseif ($item->getCustomName() == "§r§6Players §cinvisbel"){
                $this->SpielerAnzeigen($player);
            }elseif ($item->getCustomName() == "§r§5Features"){
                $this->Future($player);
            }elseif ($item->getCustomName() == " "){
                $this->sound($player);
            }elseif ($item->getCustomName() == "§6Show §2Players") {
                $this->SpielerAnzeigen2($player);
            }elseif ($item->getCustomName() == "§6Players §cinvisbel") {
                $this->SpielerAnzeigen2($player);
            }
        }

    public function Server1($player){
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $this->getServer()->dispatchCommand($player,($config->get("server1")));//*<----- Your server ip here!//*
    }

    public function Server2($player){
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $this->getServer()->dispatchCommand($player,($config->get("server2")));//*<----- Your server ip here!//*
    }

    public function Server3($player){
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $this->getServer()->dispatchCommand($player,($config->get("server3"))); //*<----- Your server ip here!//*
    }

    public function Server4($player){
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $this->getServer()->dispatchCommand($player,($config->get("server4"))); //*<----- Your server ip here!//*
    }

    public function Compass($player)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->Server1($player);
                    break;
            }
            switch ($result){
                case 1:
                    $this->Server2($player);
                    break;
            }
            switch ($result){
                case 2:
                    $this->Server3($player);
                    break;
            }
            switch ($result){
                case 3:
                    $this->Server4($player);
                    break;
            }
        });
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $form->setTitle("§c---Server---");
        $form->addButton($config->get("name1"));
        $form->addButton($config->get("name2"));
        $form->addButton($config->get("name3"));
        $form->addButton($config->get("name4"));
        $form->sendToPlayer($player);
        return true;
    }

    public function Info($player)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    break;
            }
        });
        $form->setTitle("§b------Info-------");
        $form->setContent("§aWelcome to BoekeCraft");
        $form->addButton("§cExit");
        $form->sendToPlayer($player);
        return true;
    }

    public function Lobby1($player){
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);


        $this->getServer()->dispatchCommand($player,($config->get("lobby1"))); //*<------- Your server ip here//*


    }

    public function Lobby2($player){
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);


        $this->getServer()->dispatchCommand($player,($config->get("lobby2"))); //*<----- Your server ip here!//*


    }

    public function Future($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    if ($player->hasPermission("fly.use")){
                        $this->Fly($player);
                    }else{
                        $player->sendMessage($this->prefix . "§cYou don't have permission for this!");
                    }
                    break;
            }
            switch ($result){
                case 1:
                    if ($player->hasPermission("size.use")) {
                        $this->Size($player);
                    }else{
                        $player->sendMessage($this->prefix . "§cYou don't have permission for this!");
                    }
                    break;
            }
            switch ($result){
                case 2:
                    if ($player->hasPermission("inv.use")){
                        $this->inv($player);
                    }else{
                        $player->sendMessage($this->prefix . "§cYou don't have permission for this!");
                    }
                    break;
            }
            switch ($result){
                case 3:
                    if ($player->hasPermission("speed.use")){
                        $this->Speed($player);
                    }else{
                        $player->sendMessage($this->prefix . "§cYou don't have permission for this!");
                    }
                    break;
            }
            switch ($result){
                case 4:
                    if ($player->hasPermission("time.use")){
                        $this->Time($player);
                    }else{
                        $player->sendMessage($this->prefix . "§cYou don't have permission for this!");
                    }
                    break;
            }
            switch ($result){
                case 5:
                    break;
            }
        });
        $form->setTitle("§r§5Features");
        $form->addButton("§6Fly");
        $form->addButton("§2Size");
        $form->addButton("Inventory");
        $form->addButton("§3Speed");
        $form->addButton("§5Time");
        $form->addButton("§cExit");
        $form->sendToPlayer($player);
        return true;

    }

    public function Size($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->Groß($player);
                    break;
            }
            switch ($result){
                case 1:
                    $this->Normal($player);
                    break;
            }
            switch ($result){
                case 2:
                    $this->Klein($player);
            }
            switch ($result){
                case 3:
                    break;
            }
        });
        $form->setTitle("§8SizeUI Menu");
        $form->addButton("§6Big");
        $form->addButton("§4Normal");
        $form->addButton("§0Little");
        $form->addButton("§cExit");
        $form->sendToPlayer($player);
        return true;

    }

    public function Klein($player){

        $player->setScale(0.5);
        $player->sendMessage($this->prefix . "§aSize set to big");

    }

    public function Normal($player){
        $player->setScale(1);
        $player->sendMessage($this->prefix . "§aSize set to Default");
    }

    public function Groß($player){
        $player->setScale(2);
        $player->sendMessage($this->prefix . "§aSize set to Big");
    }


    public function Fly($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->true($player);
                    break;
            }
            switch ($result){
                case 1:
                    $this->false($player);
                    break;
            }
            switch ($result){
                case 2:
                    break;
            }
        });
        $form->setTitle("Fly Menu");
        $form->addButton("§aActivate");
        $form->addButton("§cDeactivate");
        $form->addButton("§cBack");
        $form->sendToPlayer($player);
        return true;

    }

    public function true($player){
        $player->setAllowFlight(true);
        $player->getlevel()->addSound(new GhastShootSound($player));
        $player->sendMessage($this->prefix . "§aFly Activated!");
    }
    public function false($player){
        $player->setAllowFlight(false);
        $player->getlevel()->addSound(new GhastShootSound($player));
        $player->sendMessage($this->prefix . "§cFly Deactivated!");
    }

    public function Time($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->Tag($player);
                    break;
            }
            switch ($result){
                case 1:
                    $this->Nacht($player);
                    break;
            }
            switch ($result){
                case 2:
                    break;
            }
        });
        $form->setTitle("Time Menu");
        $form->addButton("§aDay");
        $form->addButton("§0Night");
        $form->addButton("§cBack");
        $form->sendToPlayer($player);
        return true;

    }

    public function Nacht($player){
        $player->getLevel()->setTime(18000);
        $player->getlevel()->addSound(new PopSound($player));
        $player->sendMessage($this->prefix . "§aTime set to night!");
    }
    public function Tag($player){
        $player->getLevel()->setTime(8000);
        $player->getlevel()->addSound(new PopSound($player));
        $player->sendMessage($this->prefix . "§aTime set to day!");
    }

    public function Speed($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->ja($player);
                    break;
            }
            switch ($result){
                case 1:
                    $this->nein($player);
                    break;
            }
            switch ($result){
                case 2:
                    break;
            }
        });
        $form->setTitle("Speed Menu");
        $form->addButton("§aActivate");
        $form->addButton("§cDeactivate");
        $form->addButton("§cBack");
        $form->sendToPlayer($player);
        return true;

    }

    public function ja($player){
        $effect = new EffectInstance(Effect::getEffect(1), 999999999, 3, false);
        $player->addEffect($effect);
        $player->getlevel()->addSound(new PopSound($player));
        $player->sendMessage($this->prefix . "§aSpeed Activated!");
    }
    public function nein($player){
        $player->removeAllEffects();
        $player->getlevel()->addSound(new PopSound($player));
        $player->sendMessage($this->prefix . "§cSpeed Deactivated!");
    }

    public function sound($player){
        $player->getlevel()->addSound(new AnvilFallSound($player));
    }

    public function Inv($player){

        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->invn($player);
                    break;
            }
            switch ($result){
                case 1:
                    $this->inve($player);
                    break;
            }
            switch ($result){
                case 2:
                    break;
            }
        });
        $form->setTitle("§5Change your inventory");
        $form->addButton("§aNormal");
        $form->addButton("§bEpic");
        $form->addButton("§cBack");
        $form->sendToPlayer($player);
        return true;

    }

    public function invn($player){
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->setItem(0, Item::get(369)->setCustomName("§6Show §2Players"));
        $player->getInventory()->setItem(2, Item::get(341)->setCustomName("§r§aLobby"));
        $player->getInventory()->setItem(4, Item::get(345)->setCustomName("§r§6Compass"));
        $player->getInventory()->setItem(8, Item::get(130)->setCustomName("§r§5Features"));
        $player->getInventory()->setItem(6, Item::get(399)->setCustomName("§r§aInfo"));
        $player->setXpLevel(0);
        $player->setFood($config->get("food"));
        $player->setMaxHealth($config->get("heal"));
        $player->getlevel()->addSound(new AnvilFallSound($player));
        $player->sendMessage($this->prefix . "§aInventar set to normal!");
    }
    public function inve($player){
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->setItem(0, Item::get(369)->setCustomName("§r§6Show §2Players"));
        $player->getInventory()->setItem(2, Item::get(341)->setCustomName("§r§aLobby"));
        $player->getInventory()->setItem(4, Item::get(345)->setCustomName("§r§6Compass"));
        $player->getInventory()->setItem(8, Item::get(130)->setCustomName("§r§5Features"));
        $player->getInventory()->setItem(6, Item::get(399)->setCustomName("§r§aInfo"));
        $player->getInventory()->setItem(1, Item::get(160)->setCustomName(" "));
        $player->getInventory()->setItem(3, Item::get(160)->setCustomName(" "));
        $player->getInventory()->setItem(5, Item::get(160)->setCustomName(" "));
        $player->getInventory()->setItem(7, Item::get(160)->setCustomName(" "));
        $player->setXpLevel($config->get("level"));
        $player->setFood($config->get("food"));
        $player->setMaxHealth($config->get("heal"));
        $player->getlevel()->addSound(new AnvilFallSound($player));
        $player->sendMessage($this->prefix . "§aInventory set to epic!");
    }

    public function Lobby($player)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->Lobby1($player);
                    break;
            }
            switch ($result){
                case 1:
                    $this->Lobby2($player);
                    break;
            }
            switch ($result){
                case 2:
                    break;
            }
        });
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $form->setTitle("§6------Lobbys-------");
        $form->setContent("§6Lobbys");
        $form->addButton($config->get("lobbyname"));
        $form->addButton($config->get("lobbyname2"));
        $form->sendToPlayer($player);
        return true;
    }

    public function SpielerAnzeigen($player)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->showPlayer($player);
                    break;
            }
            switch ($result) {
                case 1:
                    $this->hidePlayer($player);
                    break;
            }
            switch ($result){
                case 2:
                    break;
            }
        });
        $form->setTitle("§5----Player Menu------");
        $form->setContent("§aPlayer Menu");
        $form->addButton("§6Show §aPlayer");
        $form->addButton("§6Players §cinvisbel");
        $form->addButton("§cExit");
        $form->sendToPlayer($player);
        return true;
    }


    public function showPlayer($player){
        foreach($this->getServer()->getOnlinePlayers() as $players){
            $player->showPlayer($players);
        }
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->setItem(0, Item::get(369)->setCustomName("§r§6Show §2Players"));
        $player->getInventory()->setItem(2, Item::get(341)->setCustomName("§r§aLobby"));
        $player->getInventory()->setItem(4, Item::get(345)->setCustomName("§r§6Compass"));
        $player->getInventory()->setItem(8, Item::get(130)->setCustomName("§r§5Features"));
        $player->getInventory()->setItem(6, Item::get(399)->setCustomName("§r§aInfo"));
        $player->getInventory()->setItem(1, Item::get(160)->setCustomName(" "));
        $player->getInventory()->setItem(3, Item::get(160)->setCustomName(" "));
        $player->getInventory()->setItem(5, Item::get(160)->setCustomName(" "));
        $player->getInventory()->setItem(7, Item::get(160)->setCustomName(" "));
        $player->setXpLevel($config->get("level"));
        $player->setFood($config->get("food"));
        $player->setMaxHealth($config->get("heal"));
        $player->sendMessage($this->prefix . "§aYou see all players now");
    }

    public function hidePlayer($player){
        foreach($this->getServer()->getOnlinePlayers() as $players) {
            $player->hidePlayer($players);
        }
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->setItem(0, Item::get(280)->setCustomName("§r§6Players §cinvisbel"));
        $player->getInventory()->setItem(2, Item::get(341)->setCustomName("§r§aLobby"));
        $player->getInventory()->setItem(4, Item::get(345)->setCustomName("§r§6Compass"));
        $player->getInventory()->setItem(8, Item::get(130)->setCustomName("§r§5Features"));
        $player->getInventory()->setItem(6, Item::get(399)->setCustomName("§r§aInfo"));
        $player->getInventory()->setItem(1, Item::get(160)->setCustomName(" "));
        $player->getInventory()->setItem(3, Item::get(160)->setCustomName(" "));
        $player->getInventory()->setItem(5, Item::get(160)->setCustomName(" "));
        $player->getInventory()->setItem(7, Item::get(160)->setCustomName(" "));
        $player->setXpLevel($config->get("level"));
        $player->setFood($config->get("food"));
        $player->setMaxHealth($config->get("heal"));
        $player->sendMessage($this->prefix . "§aYou don't see players now");

    }

    public function SpielerAnzeigen2($player)
    {
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->showPlayer2($player);
                    break;
            }
            switch ($result) {
                case 1:
                    $this->hidePlayer2($player);
                    break;
            }
            switch ($result){
                case 2:
                    break;
            }
        });
        $form->setTitle("§5----Player Menu------");
        $form->setContent("§aPlayer Menu");
        $form->addButton("§6Show §aPlayers");
        $form->addButton("§6Players §cInvisbel");
        $form->addButton("§cExit");
        $form->sendToPlayer($player);
        return true;
    }


    public function showPlayer2($player){
        foreach($this->getServer()->getOnlinePlayers() as $players){
            $player->showPlayer($players);
        }
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->setItem(0, Item::get(369)->setCustomName("§6Show §2Players"));
        $player->getInventory()->setItem(2, Item::get(341)->setCustomName("§r§aLobby"));
        $player->getInventory()->setItem(4, Item::get(345)->setCustomName("§r§6Compass"));
        $player->getInventory()->setItem(8, Item::get(130)->setCustomName("§r§5Features"));
        $player->getInventory()->setItem(6, Item::get(399)->setCustomName("§r§aInfo"));
        $player->setXpLevel(0);
        $player->setFood($config->get("food"));
        $player->setMaxHealth($config->get("heal"));
        $player->getlevel()->addSound(new AnvilFallSound($player));
        $player->sendMessage($this->prefix . "§aYou see all players now");
    }

    public function hidePlayer2($player){
        foreach($this->getServer()->getOnlinePlayers() as $players) {
            $player->hidePlayer($players);
        }
        $config = new Config($this->getDataFolder() . "config.yml" , Config::YAML);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getInventory()->setItem(0, Item::get(280)->setCustomName("§6Players §cinvisbel"));
        $player->getInventory()->setItem(2, Item::get(341)->setCustomName("§r§aLobby"));
        $player->getInventory()->setItem(4, Item::get(345)->setCustomName("§r§6Compass"));
        $player->getInventory()->setItem(8, Item::get(130)->setCustomName("§r§5Features"));
        $player->getInventory()->setItem(6, Item::get(399)->setCustomName("§r§aInfo"));
        $player->setXpLevel(0);
        $player->setFood($config->get("food"));
        $player->setMaxHealth($config->get("heal"));
        $player->getlevel()->addSound(new AnvilFallSound($player));
        $player->sendMessage($this->prefix . "§aYou don't see players now");

    }



    public function onCommand(CommandSender $player, Command $cmd, string $label, array $args): bool
    {
        switch ($cmd->getName()) {
            case "lc":
                if ($player instanceof Player) {
                    if ($player->hasPermission("lc,use")) {
                        $player->sendMessage("-------------");
                        $player->sendMessage("Plugin by Teunboeke & TrenicHD");
                        $player->sendMessage("-------------");
                    }
                }
                break;
        }
        return true;

    }









}
