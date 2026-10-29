<?php

if (!function_exists('formatRupiah')) {
    function formatRupiah($amount): string
    {
        if ($amount == 0) {
            return 'Free';
        }

        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
