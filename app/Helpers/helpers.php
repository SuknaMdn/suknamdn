<?php

if (!function_exists('formatToArabic')) {
    function formatToArabic($number) {
        // Remove commas and decimals
        $cleanedNumber = (int)str_replace([',', '.00'], '', $number);

        // Arabic numbers mapping
        $arabicNumbers = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];

        // Calculate millions and thousands
        $millions = floor($cleanedNumber / 1000000);
        $thousands = floor(($cleanedNumber % 1000000) / 1000);

        $result = [];

        // Handle millions
        if ($millions > 0) {
            $millionsArabic = strtr((string)$millions, array_combine(range(0, 9), $arabicNumbers));
            $result[] = $millionsArabic . ' مليون';
        }

        // Handle thousands
        if ($thousands > 0) {
            $thousandsArabic = strtr((string)$thousands, array_combine(range(0, 9), $arabicNumbers));
            if (!empty($result)) {
                $result[] = 'و';
            }
            $result[] = $thousandsArabic . ' الف';
        }

        return implode(' ', $result);
    }
}
