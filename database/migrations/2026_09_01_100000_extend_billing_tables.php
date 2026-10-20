<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('plan_sku', 32)->nullable()->after('plan');
            $table->string('billing_cycle', 16)->nullable()->after('plan_sku');
            $table->timestamp('trial_ends_at')->nullable()->after('ends_at');
            $table->string('paypal_subscription_id')->nullable()->index()->after('trial_ends_at');
            $table->string('paypal_payer_id')->nullable()->after('paypal_subscription_id');
            $table->timestamp('cancelled_at')->nullable()->after('paypal_payer_id');
        });

        Schema::table('payment_orders', function (Blueprint $table) {
            $table->string('plan_sku', 32)->nullable()->after('tenant_id');
            $table->string('gateway', 32)->default('manual')->after('method');
            $table->string('gateway_order_id')->nullable()->index()->after('gateway');
            $table->string('gateway_subscription_id')->nullable()->index()->after('gateway_order_id');
            $table->timestamp('period_starts_at')->nullable()->after('notes');
            $table->timestamp('period_ends_at')->nullable()->after('period_starts_at');
        });

        Schema::create('billing_webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('gateway', 32);
            $table->string('event_id')->unique();
            $table->string('event_type');
            $table->json('payload')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_webhook_events');

        Schema::table('payment_orders', function (Blueprint $table) {
            $table->dropColumn([
                'plan_sku', 'gateway', 'gateway_order_id',
                'gateway_subscription_id', 'period_starts_at', 'period_ends_at',
            ]);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'plan_sku', 'billing_cycle', 'trial_ends_at',
                'paypal_subscription_id', 'paypal_payer_id', 'cancelled_at',
            ]);
        });
    }
};
