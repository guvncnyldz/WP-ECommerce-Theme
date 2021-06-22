<?php

function translate_order_status($status)
{
    switch ($status) {
        case 'completed':
            return "Teslim Edildi";
        case 'pending':
            return "Beklemede";
        case 'processing':
            return "Hazırlanıyor";
        case 'on-hold':
            return "Ödeme Bekleniyor";
        case 'cancelled':
            return "İptal Edildi";
        case 'refunded':
            return "İade Edildi";
        case 'failed':
            return "Başarısız";
        default:
            return $status;
    }
}