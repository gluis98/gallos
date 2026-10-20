<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('subscriptions')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                if (! Schema::hasColumn('subscriptions', 'plan_sku')) {
                    $table->string('plan_sku', 32)->nullable()->after('plan');
                }
                if (! Schema::hasColumn('subscriptions', 'billing_cycle')) {
                    $table->string('billing_cycle', 16)->nullable()->after('plan_sku');
                }
                if (! Schema::hasColumn('subscriptions', 'trial_ends_at')) {
                    $table->timestamp('trial_ends_at')->nullable()->after('ends_at');
                }
                if (! Schema::hasColumn('subscriptions', 'paypal_subscription_id')) {
                    $table->string('paypal_subscription_id')->nullable()->index()->after('trial_ends_at');
                }
                if (! Schema::hasColumn('subscriptions', 'paypal_payer_id')) {
                    $table->string('paypal_payer_id')->nullable()->after('paypal_subscription_id');
                }
                if (! Schema::hasColumn('subscriptions', 'cancelled_at')) {
                    $table->timestamp('cancelled_at')->nullable()->after('paypal_payer_id');
                }
            });
        }

        if (Schema::hasTable('payment_orders')) {
            Schema::table('payment_orders', function (Blueprint $table) {
                if (! Schema::hasColumn('payment_orders', 'plan_sku')) {
                    $table->string('plan_sku', 32)->nullable()->after('tenant_id');
                }
                if (! Schema::hasColumn('payment_orders', 'gateway')) {
                    $table->string('gateway', 32)->default('manual')->after('method');
                }
                if (! Schema::hasColumn('payment_orders', 'gateway_order_id')) {
                    $table->string('gateway_order_id')->nullable()->index()->after('gateway');
                }
                if (! Schema::hasColumn('payment_orders', 'gateway_subscription_id')) {
                    $table->string('gateway_subscription_id')->nullable()->index()->after('gateway_order_id');
                }
                if (! Schema::hasColumn('payment_orders', 'period_starts_at')) {
                    $table->timestamp('period_starts_at')->nullable()->after('notes');
                }
                if (! Schema::hasColumn('payment_orders', 'period_ends_at')) {
                    $table->timestamp('period_ends_at')->nullable()->after('period_starts_at');
                }
            });
        }

        if (! Schema::hasTable('billing_webhook_events')) {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_webhook_events');

        if (Schema::hasTable('payment_orders')) {
            Schema::table('payment_orders', function (Blueprint $table) {
                foreach (['plan_sku', 'gateway', 'gateway_order_id', 'gateway_subscription_id', 'period_starts_at', 'period_ends_at'] as $col) {
                    if (Schema::hasColumn('payment_orders', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        if (Schema::hasTable('subscriptions')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                foreach (['plan_sku', 'billing_cycle', 'trial_ends_at', 'paypal_subscription_id', 'paypal_payer_id', 'cancelled_at'] as $col) {
                    if (Schema::hasColumn('subscriptions', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
