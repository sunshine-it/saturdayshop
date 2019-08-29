<?php

namespace App\Console\Commands\Cron;

use Illuminate\Console\Command;
use App\Models\CrowdfundingProduct;
use App\Models\Order;
use Carbon\Carbon;
use App\Services\OrderService; // 退款逻辑
use App\Jobs\RefundCrowdfundingOrders; // 使用定时任务中的失败退款逻辑改为触发这个异步任务

// 众筹结束 的定时任务命令
class FinishCrowdfunding extends Command
{
    protected $signature = 'cron:finish-crowdfunding';

    protected $description = '结束众筹';

    public function handle()
    {
        CrowdfundingProduct::query()
            // 众筹结束时间早于当前时间
            ->where('end_at', '<=', Carbon::now())
            // 众筹状态为众筹中
            ->where('status', CrowdfundingProduct::STATUS_FUNDING)
            ->get()
            ->each(function (CrowdfundingProduct $crowdfunding) {
                // 如果众筹目标金额大于实际众筹金额
                if ($crowdfunding->target_amount > $crowdfunding->total_amount) {
                    // 调用众筹失败逻辑
                    $this->crowdfundingFailed($crowdfunding);
                }
                else {
                    // 否则调用众筹成功逻辑
                    $this->crowdfundingSucceed($crowdfunding);
                }
            });
    }

    // 众筹成功
    protected function crowdfundingSucceed(CrowdfundingProduct $crowdfunding)
    {
        // 只需将众筹状态改为众筹成功即可
        $crowdfunding->update([
            'status' => CrowdfundingProduct::STATUS_SUCCESS,
        ]);
    }

    // 众筹失败
    protected function crowdfundingFailed(CrowdfundingProduct $crowdfunding)
    {
        // 将众筹状态改为众筹失败
        $crowdfunding->update([
            'status' => CrowdfundingProduct::STATUS_FAIL,
        ]);
        // 触发异步任务 RefundCrowdfundingOrders
        dispatch(new RefundCrowdfundingOrders($crowdfunding));
    }
}
