<?php

namespace App\Support;

/**
 * Aturan validasi yang dipakai bersama oleh semua form (register, akun admin,
 * edit profil, reservasi, fasilitas) agar formatnya konsisten.
 */
class FormRules
{
    /** Domain email institusi */
    public const EMAIL_DOMAIN = '@charm.ac.id';

    /** Pola yang juga dipakai sebagai atribut "pattern" di HTML (validasi sisi client) */
    public const NAME_PATTERN    = "[A-Za-zÀ-ÿ .'\\-]+";
    public const NIM_NIP_PATTERN = '\d{14}|\d{18}';
    public const PHONE_PATTERN   = '08\d{8,11}';

    /** Nama: huruf, spasi, titik, apostrof, tanda hubung */
    public static function name(bool $required = true): array
    {
        return [$required ? 'required' : 'nullable', 'string', 'max:100', "regex:/^[\\pL\\s.'\\-]+$/u"];
    }

    /** Email institusi @charm.ac.id (opsional: abaikan id tertentu saat cek unik) */
    public static function campusEmail(?int $ignoreUserId = null): array
    {
        return [
            'required', 'string', 'lowercase', 'email', 'max:255',
            'ends_with:' . self::EMAIL_DOMAIN,
            'unique:users,email' . ($ignoreUserId ? ',' . $ignoreUserId : ''),
        ];
    }

    /** NIM (14 digit) atau NIP (18 digit), hanya angka */
    public static function nimNip(bool $required = true): array
    {
        return [$required ? 'required' : 'nullable', 'regex:/^(\d{14}|\d{18})$/'];
    }

    /** Nomor telepon / WhatsApp: diawali 08, total 10–13 digit angka */
    public static function phone(bool $required = true): array
    {
        return [$required ? 'required' : 'nullable', 'regex:/^08\d{8,11}$/'];
    }

    /** Pesan error berbahasa Indonesia untuk aturan di atas */
    public static function messages(): array
    {
        return [
            'name.regex'          => 'Nama hanya boleh berisi huruf, spasi, titik, atau tanda hubung.',
            'requester_name.regex'=> 'Nama hanya boleh berisi huruf, spasi, titik, atau tanda hubung.',
            'email.ends_with'     => 'Gunakan email institusi yang berakhiran ' . self::EMAIL_DOMAIN . '.',
            'email.unique'        => 'Email ini sudah terdaftar.',
            'nim_nip.regex'       => 'NIM harus 14 digit atau NIP 18 digit, hanya angka.',
            'nim_nip.required'    => 'NIM/NIP wajib diisi.',
            'whatsapp.regex'      => 'Nomor WhatsApp harus diawali 08 dan terdiri dari 10–13 digit angka.',
            'contact_phone.regex' => 'Nomor kontak harus diawali 08 dan terdiri dari 10–13 digit angka.',
        ];
    }
}