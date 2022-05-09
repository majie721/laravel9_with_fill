<?php

namespace App\Http\Beans\Tiktok\Api;

use Fbg\Api\BaseBean;

class InventoryStatusUpload extends BaseBean
{
    /** @var string TTS系统产⽣的单据号码 (length=40)*/
    public string $inventory_count_no;

}
