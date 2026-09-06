<?php

namespace App\Services;

class RegionHelper
{
    /**
     * Normalize a Kabupaten / Kota name into standard Title Case format
     * Examples:
     *  - "LEBAK", "lebak", "KAB. LEBAK", "Kab. Lebak", "Kabupaten Lebak" => "Kab. Lebak"
     *  - "KOTA BEKASI", "kota bekasi", "Bekasi (Kota)" => "Kota Bekasi"
     *  - "KABUPATEN TANGERANG", "TANGERANG", "Kab. Tangerang" => "Kab. Tangerang"
     */
    public static function normalizeKabupaten(?string $name): ?string
    {
        if ($name === null) {
            return null;
        }

        $trimmed = trim($name);
        if ($trimmed === '') {
            return null;
        }

        // Clean excess whitespaces
        $clean = preg_replace('/\s+/', ' ', $trimmed);

        // Check if explicitly starting with Kota
        $isKota = (bool) preg_match('/^(kota|kotamadya)\b/i', $clean);

        // Remove known prefixes: "kabupaten", "kab.", "kab", "kota", "kotamadya"
        $core = preg_replace('/^(kabupaten|kab\.|kab|kota|kotamadya)\s+/i', '', $clean);
        $core = trim($core, " \t\n\r\0\x0B.,-");

        if ($core === '') {
            return ucwords(strtolower($clean));
        }

        // Title case the core region name
        $titleCore = ucwords(strtolower($core));

        // Format standardized prefix
        return $isKota ? 'Kota ' . $titleCore : 'Kab. ' . $titleCore;
    }

    /**
     * Normalize Kelurahan or Kecamatan name into standard Title Case
     * Examples:
     *  - "PAGEDANGAN", "pagedangan", "Pagedangan" => "Pagedangan"
     *  - "KADU SIRUNG", "kadu sirung" => "Kadu Sirung"
     *  - "DESA BOJONG KAMAL", "Kel. Bojong Kamal" => "Bojong Kamal"
     */
    public static function normalizeKelurahan(?string $name): ?string
    {
        if ($name === null) {
            return null;
        }

        $trimmed = trim($name);
        if ($trimmed === '') {
            return null;
        }

        // Remove prefix 'kelurahan', 'kel.', 'desa' if present
        $clean = preg_replace('/^(kelurahan|kel\.|kel|desa)\s+/i', '', $trimmed);
        $clean = trim(preg_replace('/\s+/', ' ', $clean));

        return ucwords(strtolower($clean));
    }

    /**
     * Extract core search string without 'kabupaten', 'kab.', 'kota'
     * e.g., "Kab. Lebak" => "lebak"
     */
    public static function extractCoreName(?string $name): string
    {
        if (empty($name)) {
            return '';
        }

        $clean = preg_replace('/^(kabupaten|kab\.|kab|kota|kotamadya|kelurahan|kel\.|desa)\s+/i', '', trim($name));
        $clean = trim(preg_replace('/\s+/', ' ', $clean));

        return strtolower($clean);
    }

    /**
     * Apply case-insensitive and prefix-insensitive filter to an Eloquent/DB query for Kabupaten
     */
    public static function filterKabupaten($query, ?string $kabupaten, string $column = 'kabupaten')
    {
        if (empty($kabupaten)) {
            return $query;
        }

        $core = self::extractCoreName($kabupaten);
        if ($core === '') {
            return $query;
        }

        return $query->where(function ($q) use ($column, $core, $kabupaten) {
            $q->whereRaw("LOWER({$column}) = ?", [strtolower(trim($kabupaten))])
              ->orWhereRaw("LOWER({$column}) LIKE ?", ['%' . $core . '%']);
        });
    }

    /**
     * Apply case-insensitive filter to an Eloquent/DB query for Kelurahan
     */
    public static function filterKelurahan($query, ?string $kelurahan, string $column = 'kelurahan')
    {
        if (empty($kelurahan)) {
            return $query;
        }

        $core = self::extractCoreName($kelurahan);
        if ($core === '') {
            return $query;
        }

        return $query->where(function ($q) use ($column, $core, $kelurahan) {
            $q->whereRaw("LOWER({$column}) = ?", [strtolower(trim($kelurahan))])
              ->orWhereRaw("LOWER({$column}) LIKE ?", ['%' . $core . '%']);
        });
    }
}
