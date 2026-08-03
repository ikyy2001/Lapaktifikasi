<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Bonus Akumulasi Referral
    |--------------------------------------------------------------------------
    |
    | Nominal virtual yang ditambahkan ke `total_belanja_akumulasi` milik customer
    | pereferensi setiap kali referral baru melakukan transaksi SUKSES pertama kali.
    |
    */
    'bonus_akumulasi' => (float) env('REFERRAL_BONUS_AKUMULASI', 50000),
];
