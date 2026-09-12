-- ============================================================
-- Commission System Setup
-- Run this in phpMyAdmin → ebay database → SQL tab
-- ============================================================

-- 1. Add commission columns to order_items (if not already present)
ALTER TABLE `order_items`
    ADD COLUMN IF NOT EXISTS `commission_rate`   DECIMAL(5,2)  NOT NULL DEFAULT 0 AFTER `tax_amount`,
    ADD COLUMN IF NOT EXISTS `commission_amount` DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `commission_rate`,
    ADD COLUMN IF NOT EXISTS `vendor_amount`     DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER `commission_amount`;

-- Also rename 'name' to 'product_name' if needed (check your order_items columns first)
-- ALTER TABLE `order_items` CHANGE `name` `product_name` VARCHAR(255) NULL;

-- 2. Create settings table (if not already present)
CREATE TABLE IF NOT EXISTS `settings` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key`         VARCHAR(255)    NOT NULL,
    `value`       TEXT            NULL,
    `group`       VARCHAR(255)    NOT NULL DEFAULT 'general',
    `type`        VARCHAR(255)    NOT NULL DEFAULT 'text',
    `label`       VARCHAR(255)    NOT NULL DEFAULT '',
    `description` VARCHAR(255)    NULL,
    `created_at`  TIMESTAMP       NULL,
    `updated_at`  TIMESTAMP       NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Insert commission default settings (skip if key already exists)
INSERT IGNORE INTO `settings` (`key`, `value`, `group`, `type`, `label`, `created_at`, `updated_at`) VALUES
('commission_type',     'percentage', 'commission', 'select', 'Commission Type',          NOW(), NOW()),
('commission_rate',     '10',         'commission', 'number', 'Commission Rate (%)',       NOW(), NOW()),
('commission_fixed',    '0',          'commission', 'number', 'Fixed Commission Amount',   NOW(), NOW()),
('commission_min',      '0',          'commission', 'number', 'Minimum Commission',        NOW(), NOW()),
('commission_max',      '0',          'commission', 'number', 'Maximum Commission (0=no cap)', NOW(), NOW()),
('payout_schedule',     'manual',     'commission', 'select', 'Payout Schedule',           NOW(), NOW()),
('payout_min_amount',   '20',         'commission', 'number', 'Minimum Payout Amount',     NOW(), NOW()),
('payout_hold_days',    '7',          'commission', 'number', 'Payout Hold Period (days)', NOW(), NOW()),
('payout_method',       'bank',       'commission', 'select', 'Payout Method',             NOW(), NOW()),
('payout_auto_approve', '0',          'commission', 'boolean','Auto-approve Payouts',      NOW(), NOW()),
('listing_free_count',  '50',         'commission', 'number', 'Free Listings per Month',   NOW(), NOW()),
('listing_fee',         '0',          'commission', 'number', 'Listing Fee (paid)',         NOW(), NOW()),
('auction_fee_rate',    '0',          'commission', 'number', 'Auction Extra Fee (%)',      NOW(), NOW());

-- 4. Also insert other default settings if missing
INSERT IGNORE INTO `settings` (`key`, `value`, `group`, `type`, `label`, `created_at`, `updated_at`) VALUES
('site_name',       'eBay Clone',         'general', 'text',    'Site Name',          NOW(), NOW()),
('site_tagline',    'Buy & Sell Everything', 'general', 'text', 'Site Tagline',       NOW(), NOW()),
('currency',        'USD',                'general', 'text',    'Currency Code',      NOW(), NOW()),
('currency_symbol', '$',                  'general', 'text',    'Currency Symbol',    NOW(), NOW()),
('contact_email',   'support@ebay.test',  'general', 'text',    'Contact Email',      NOW(), NOW()),
('stripe_enabled',  '0',                  'payment', 'boolean', 'Stripe Enabled',     NOW(), NOW()),
('paypal_enabled',  '0',                  'payment', 'boolean', 'PayPal Enabled',     NOW(), NOW()),
('paypal_mode',     'sandbox',            'payment', 'select',  'PayPal Mode',        NOW(), NOW()),
('vendor_approval_required', '1',         'vendors', 'boolean', 'Require Approval',   NOW(), NOW());

-- Done!
SELECT 'Commission system setup complete!' AS status;
