<?php

namespace dev_bots_ru\Common;

use dev_bots_ru\General\DB;
use dev_bots_ru\General\Parser;

class Encryption
{
    public static function encrypt_data(string $data): array
    {
        $encryption_key = DB::get__encryption_key();

        $encryption_data = [];
        $encryption_data['ivlen'] = openssl_cipher_iv_length('aes-128-ccm');
        $encryption_data['iv'] = openssl_random_pseudo_bytes($encryption_data['ivlen']);
        $encryption_data['additional_data'] = Parser::$tg_user_id;

        $encrypted_data = openssl_encrypt(
            $data,
            'aes-128-ccm',
            $encryption_key,
            OPENSSL_RAW_DATA,
            $encryption_data['iv'],
            $tag,
            $encryption_data['additional_data']
        );
        $encryption_data['tag'] = $tag;
        $encryption_data['encrypted_data'] = $encrypted_data;
        return $encryption_data;
    }

    public static function decrypt_data(string $data, string $iv, string $tag, string $additional_data): string
    {
        $encryption_key = DB::get__encryption_key();
        $decrypted_data = openssl_decrypt(
            $data,
            'aes-128-ccm',
            $encryption_key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            $additional_data
        );

        return $decrypted_data;
    }
}
