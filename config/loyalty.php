<?php

return [
    // Points awarded for positive review (rating >= 4)
    'points_per_review' => env('LOYALTY_POINTS_PER_REVIEW', 100),

    // Points to Rupiah conversion rate
    // 100 points = Rp 5.000
    'points_to_rupiah' => env('LOYALTY_POINTS_TO_RUPIAH', 50),

    // Minimum points required for redemption
    'min_points_redemption' => env('LOYALTY_MIN_POINTS_REDEMPTION', 100),
];
