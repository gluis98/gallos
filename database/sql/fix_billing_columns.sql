-- Correr UNA VEZ en la BD de producción (galpon) si falla con "Unknown column plan_sku".
-- Si alguna columna ya existe, omite esa línea y continúa con las demás.

ALTER TABLE `subscriptions` ADD COLUMN `plan_sku` VARCHAR(32) NULL AFTER `plan`;
ALTER TABLE `subscriptions` ADD COLUMN `billing_cycle` VARCHAR(16) NULL AFTER `plan_sku`;
ALTER TABLE `subscriptions` ADD COLUMN `trial_ends_at` TIMESTAMP NULL AFTER `ends_at`;
ALTER TABLE `subscriptions` ADD COLUMN `paypal_subscription_id` VARCHAR(255) NULL AFTER `trial_ends_at`;
ALTER TABLE `subscriptions` ADD COLUMN `paypal_payer_id` VARCHAR(255) NULL AFTER `paypal_subscription_id`;
ALTER TABLE `subscriptions` ADD COLUMN `cancelled_at` TIMESTAMP NULL AFTER `paypal_payer_id`;

ALTER TABLE `payment_orders` ADD COLUMN `plan_sku` VARCHAR(32) NULL AFTER `tenant_id`;
ALTER TABLE `payment_orders` ADD COLUMN `gateway` VARCHAR(32) NOT NULL DEFAULT 'manual' AFTER `method`;
ALTER TABLE `payment_orders` ADD COLUMN `gateway_order_id` VARCHAR(255) NULL AFTER `gateway`;
ALTER TABLE `payment_orders` ADD COLUMN `gateway_subscription_id` VARCHAR(255) NULL AFTER `gateway_order_id`;
ALTER TABLE `payment_orders` ADD COLUMN `period_starts_at` TIMESTAMP NULL AFTER `notes`;
ALTER TABLE `payment_orders` ADD COLUMN `period_ends_at` TIMESTAMP NULL AFTER `period_starts_at`;

CREATE TABLE IF NOT EXISTS `billing_webhook_events` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `gateway` VARCHAR(32) NOT NULL,
  `event_id` VARCHAR(255) NOT NULL,
  `event_type` VARCHAR(255) NOT NULL,
  `payload` JSON NULL,
  `processed_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  UNIQUE KEY `billing_webhook_events_event_id_unique` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
