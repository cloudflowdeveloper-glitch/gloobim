<?php

if (!function_exists('formatCount')) {
    function formatCount($num): string {
        $num = (int) $num;
        if ($num >= 1000000) return round($num / 1000000, 1) . 'M';
        if ($num >= 1000) return round($num / 1000, 1) . 'K';
        return (string) $num;
    }
}

if (!function_exists('formatDuration')) {
    function formatDuration(int $seconds): string {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;
        if ($h > 0) return "{$h}:" . str_pad($m, 2, '0', STR_PAD_LEFT) . ':' . str_pad($s, 2, '0', STR_PAD_LEFT);
        return "{$m}:" . str_pad($s, 2, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo(?string $datetime): string {
        if (!$datetime) return '';
        $now = time();
        $then = strtotime($datetime);
        $diff = $now - $then;
        if ($diff < 60) return 'just now';
        if ($diff < 3600) return floor($diff / 60) . 'm';
        if ($diff < 86400) return floor($diff / 3600) . 'h';
        if ($diff < 604800) return floor($diff / 86400) . 'd';
        return floor($diff / 604800) . 'w';
    }
}
