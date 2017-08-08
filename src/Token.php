<?php 
namespace Jsnlib;


class Token
{
    protected $key;

    function __construct()
    {
    }

    public function key($key)
    {
        $this->key = $key;
    }

    public function json(\Jsnlib\Ao $token)
    {
        return json_encode($token->toArray());
    }

    /**
     * 當前時間，相對於指定時間，是否過期了？
     * @param   $datetime  date()的格式如 '2014-12-25 00:00:00'
     * @return  bool
     */
    public function is_expiry($datetime): bool
    {
        return (time() >= strtotime($datetime)) ? true : false;
    }


    /**
     * 產生新令牌
     * @param   $bind_id 綁定的值
     * @param   $expiry  過期日，可用  $this->expriy_date() 產生
     */
    public function create($bind_id, $expiry): \Jsnlib\Ao
    {
        // 混和鑰匙
        $key = $this->mixkey();
        
        // 混和
        $hash = $this->get_hash([$bind_id, $expiry, $key]);

        return new \Jsnlib\Ao(
        [
            'value' => $hash,
            'expiry' => $expiry 
        ]);
    }

    private function mixkey()
    {
        return uniqid($this->key);
    }

    /**
     * 取得多少天前後的日期格式
     * 如 expriy_date("Y-m-d H:i:s", "now", +1, "day");
     * 可取得明天的日期
     * 
     * @param  $date_format 日期格式如 "Y-m-d H:i:s"
     * @param  $use_date    基準日期 可用now代表今天, 或使用 date() 字串
     * @param  $limit_num   多少天
     * @param  $type        參考 strtotime 的類型 如天數 day
     * @return              $date_format 指定的日期字串       
     */
    public function expriy_date($date_format, $use_date, $limit_num, $type)
    {
        return date($date_format, strtotime("{$use_date} {$limit_num} {$type}"));
    }

    // 依序混入 唯一鍵, 過期時間, 
    private function get_hash($ary)
    {
        $mix = implode(",", $ary);
        return hash("sha256", $mix);
    }
}