<?php

namespace hachkingtohach1\Napthe;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\ConsoleCommandSender;
class NaptheTask extends AsyncTask
{
    private $merchant;
    private $telco;
    private $pin;
    private $seri;
    private $amount;
    private $playerName;
    public function __construct(array $merchant,string $telco,string $pin,string $seri,int $amount,string $playerName){
        $this->merchant = $merchant;
        $this->telco = $telco;
        $this->pin = $pin;
        $this->seri = $seri;
        $this->amount = $amount;
        $this->playerName = $playerName;
    }
    /**
     *
     */
    public function onRun(): void
    {
            $api_url = "https://thesieure.com/chargingws/v2";
            $data_sign = md5($this->merchant[1] . $this->pin . $this->seri);        
            $arrayPost = array(
                "telco" => $this->telco,
                "code" => $this->pin,
                "serial" => $this->seri,
                "amount" => $this->amount,
                "request_id" => intval(time()),
                "partner_id" => $this->merchant[0],
                "sign" => $data_sign,
                "command" => "charging"
            );
            $curl = curl_init($api_url);
                curl_setopt_array($curl, array(
                CURLOPT_POST => true,
                CURLOPT_HEADER => false,
                CURLINFO_HEADER_OUT => true,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => http_build_query($arrayPost)
            ));
            $data = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $result = json_decode($data, true);

            $content= [
                "arrayPost" => $arrayPost,
                "web_status" => $status,
                "result" => $result
            ];
            $this->setResult($content);            
    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server): void
    {
        if (is_null($napthe = Napthe::$instance)) {
            return;
        }

        $content = $this->getResult();
        if (!isset($content)) {
            $napthe->getServer()->getLogger()->info("??6[??bNAPTHE??6]??f Cant get updated information (timed out?)");
            return;
        }            
        if($content["result"] == false){
            $txt = "??l?????cWeb n???p th??? x???y ra l???i, h??y n???p l???i sau";
            $player = $napthe->getServer()->getPlayerExact($this->playerName);
            if($player == null){
                return;
            }
            $player->sendMessage($txt);            
            $napthe->onSuccess($player,$txt);
        }
                
        if($content["web_status"] == 200){
            $player = $napthe->getServer()->getPlayerExact($this->playerName);        
            $thongtin = [$this->telco,$this->amount,$this->seri,$this->pin];
                if($content["result"]["status"] == 99){
                    $napthe->getServer()->getAsyncPool()->submitTask(new checktheTask($content["arrayPost"],$this->playerName));
                    if($player == null){        
                        return;
                    }    
                    $player->sendTip("??l?????a??ang ki???m tra th???, xin ??c?????ng chat??a l??c n??y...");
                    return;
                }
                if($player == null){        
                        return;
                }        
                if($content["result"]["status"] == 4){
                    $txt = "??l?????cNh?? m???ng ??ang b???o tr??, h??y n???p l???i sau, th??? v???n c??n gi?? tr??? s??? d???ng";
                    $player->sendMessage($txt);
                    $napthe->onSuccess($player,$txt);
                }else if($content["result"]["status"] == 100){
                    $txt = 
                    "??l???Lo???i th???: ??a".$thongtin[0]."\n\n".
                    "??f??l???S??? Seri: ??a".$thongtin[2]."\n\n".
                    "??f??l???M?? th???: ??a".$thongtin[3]."\n\n".
                    "??f??l???M???nh gi?? ???? ch???n: ??a".$thongtin[1]."\n\n".
                    "??f??l?????cL???I: ".$content["result"]["message"].", ch???p g???i admin n???u c???n\n\n";
                    $player->sendMessage($txt);
                    $napthe->onSuccess($player,$txt);
                }else{
                    $txt = "??l?????cCH???P V?? B??O ADMIN N???U TH???Y D??NG N??Y";
                    $player->sendMessage($txt);
                    $napthe->onSuccess($player,$txt);
                }
        }else{
            var_dump($content);
        }
    }
}
