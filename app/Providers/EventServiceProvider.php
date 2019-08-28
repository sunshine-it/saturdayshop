<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\OrderPaid;
use App\Listeners\UpdateProductSoldCount;
use App\Listeners\SendOrderPaidMail;
use App\Events\OrderReviewed;
use App\Listeners\UpdateProductRating;
use App\Listeners\UpdateCrowdfundingProductProgress;

// 注册一下事件与监听器
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     * 关联事件和监听器
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderPaid::class => [
            UpdateProductSoldCount::class,  // 更新商品销量的监听器
            SendOrderPaidMail::class,  // 执行发送邮件的动作
            UpdateCrowdfundingProductProgress::class, // 更新众筹商品销量的监听器
        ],
        OrderReviewed::class => [
            UpdateProductRating::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
