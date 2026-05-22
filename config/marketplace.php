<?php

return [
    'commission_percent' => (float) env('MARKETPLACE_COMMISSION_PERCENT', 5),
    'rating_days'        => (int)   env('MARKETPLACE_RATING_DAYS', 12),
];
