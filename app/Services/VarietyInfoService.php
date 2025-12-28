<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class VarietyInfoService
{
    protected static function normalizeName(string $name): string
    {
        $n = trim(mb_strtolower($name, 'UTF-8'));
        $n = preg_replace('/\s+/', ' ', $n);
        return $n ?? '';
    }

    protected static function readAll(): array
    {
        return Cache::remember('variety_info_all', 3600, function () {
            try {
                if (!Storage::disk('public')->exists('buku_saku.txt')) {
                    return [];
                }
                $path = Storage::disk('public')->path('buku_saku.txt');
                $file = new \SplFileObject($path);
                $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
                $file->setCsvControl(',', '"', '\\');

                $rows = [];
                $header = null;
                foreach ($file as $row) {
                    if ($row === [null] || $row === false) continue;
                    if ($header === null) {
                        $header = array_map(fn($h) => strtolower(trim((string) $h)), $row);
                        continue;
                    }
                    $data = [];
                    foreach ($header as $i => $key) {
                        $data[$key] = isset($row[$i]) ? trim((string) $row[$i]) : '';
                    }
                    $rows[] = $data;
                }
                return $rows;
            } catch (\Throwable $e) {
                return [];
            }
        });
    }

    public static function getAllVarieties(int $limit = 6): array
    {
        $rows = static::readAll();
        $items = [];
        foreach ($rows as $r) {
            $nama = $r['nama_varietas'] ?? '';
            $asal = $r['asal'] ?? '';
            if ($nama === '' || $asal === '') continue;
            $umur = $r['umur_tanaman_hari'] ?? '';
            $umurFmt = $umur !== '' && is_numeric($umur) ? ((int) $umur) . ' hari' : ($umur !== '' ? $umur : '-');
            $hasil = $r['rata_rata_hasil'] ?? '';
            $tekstur = $r['tekstur_nasi'] ?? '';
            $ketHama = $r['ketahanan_hama'] ?? '';
            $ketPeny = $r['ketahanan_penyakit'] ?? '';
            $ketahanan = trim(implode(' • ', array_filter([$ketHama, $ketPeny])));
            $items[] = [
                'komoditas' => $r['komoditas'] ?? '',
                'nama_varietas' => $nama,
                'asal' => $asal,
                'umur_tanaman_hari' => $umurFmt,
                'rata_rata_hasil' => $hasil !== '' ? $hasil : '-',
                'tekstur_nasi' => $tekstur !== '' ? $tekstur : '-',
                'ketahanan_hama' => $ketHama,
                'ketahanan_penyakit' => $ketPeny,
                'deskripsi' => $r['deskripsi'] ?? $r['keterangan'] ?? 'Informasi detail mengenai varietas ini belum tersedia secara lengkap dalam database.',
            ];
            if (count($items) >= $limit) break;
        }
        return $items;
    }

    public static function getVarietyByName(string $name): ?array
    {
        $needle = static::normalizeName($name);
        foreach (static::readAll() as $r) {
            $nama = static::normalizeName($r['nama_varietas'] ?? '');
            if ($nama !== '' && $nama === $needle) {
                return $r;
            }
        }
        return null;
    }

    public static function matchVarietyDetails(array $product): ?array
    {
        $name = (string) ($product['name'] ?? $product['nama'] ?? '');
        if ($name === '') return null;
        $match = static::getVarietyByName($name);
        if (!$match) return null;
        return [
            'asal' => $match['asal'] ?? '-',
            'umur_tanaman_hari' => $match['umur_tanaman_hari'] ?? '-',
            'rata_rata_hasil' => $match['rata_rata_hasil'] ?? '-',
            'tekstur_nasi' => $match['tekstur_nasi'] ?? '-',
            'ketahanan_hama' => $match['ketahanan_hama'] ?? '-',
            'ketahanan_penyakit' => $match['ketahanan_penyakit'] ?? '-',
        ];
    }

    protected static function tokenizeResistance(string $text): array
    {
        $t = mb_strtolower(trim($text), 'UTF-8');
        if ($t === '' || $t === '-') return [];
        $t = preg_replace('/\s+/', ' ', $t);
        $parts = [];
        if (strpos($t, 'terhadap') !== false) {
            $after = trim(preg_replace('/^.*terhadap\s+/u', '', $t));
            $parts = preg_split('/[,;]| dan | & /u', $after) ?: [];
        } else {
            $parts = preg_split('/[,;]| dan | & /u', $t) ?: [];
        }
        $clean = [];
        foreach ($parts as $p) {
            $p = trim($p);
            if ($p === '' || $p === '-') continue;
            $p = preg_replace('/^(tahan|toleran|resisten|ketahanan)\s+/u', '', $p);
            $p = trim($p);
            if ($p === '' || mb_strlen($p, 'UTF-8') > 40) continue;
            $clean[] = $p;
        }
        $clean = array_unique($clean);
        return array_values($clean);
    }

    public static function resistanceTokens(?string $ketHama, ?string $ketPenyakit): array
    {
        $tokens = [];
        foreach (static::tokenizeResistance((string) $ketHama) as $it) {
            $tokens[] = ['label' => 'Hama', 'value' => 'Tahan terhadap ' . ucfirst($it)];
        }
        foreach (static::tokenizeResistance((string) $ketPenyakit) as $it) {
            $tokens[] = ['label' => 'Penyakit', 'value' => 'Tahan terhadap ' . ucfirst($it)];
        }
        return $tokens;
    }

    protected static function field(array $row, array $names): ?string
    {
        foreach ($names as $n) {
            if (isset($row[$n]) && trim((string) $row[$n]) !== '') return trim((string) $row[$n]);
        }
        return null;
    }

    public static function summarizePublic(array $row): string
    {
        $nama = static::field($row, ['nama_varietas']) ?? 'Varietas unggul';
        $asal = static::field($row, ['wilayah_asal', 'asal']);
        $ketH = static::field($row, ['ketahanan_hama']);
        $ketP = static::field($row, ['ketahanan_penyakit']);
        $tokens = static::resistanceTokens($ketH, $ketP);
        $phrases = array_map(fn($t) => $t['value'], $tokens);
        $resist = count($phrases) ? implode(', ', $phrases) : 'Ketahanan belum tersedia';
        $parts = [];
        $parts[] = $asal ? "$nama berasal dari $asal." : "$nama merupakan varietas unggul.";
        $parts[] = "Memiliki ketahanan: $resist.";
        return implode(' ', $parts);
    }

    public static function summarizeFarmer(array $row): array
    {
        $ketH = static::field($row, ['ketahanan_hama']);
        $ketP = static::field($row, ['ketahanan_penyakit']);
        $biotipeH = static::field($row, ['biotipe_hama', 'biotipe']);
        $patotipeP = static::field($row, ['patotipe_penyakit', 'patotipe']);
        $skorH = static::field($row, ['skor_hama', 'skor']);
        $skorP = static::field($row, ['skor_penyakit']);
        $hTokens = static::tokenizeResistance((string) $ketH);
        $pTokens = static::tokenizeResistance((string) $ketP);
        $hama = [];
        foreach ($hTokens as $h) {
            $s = "Tahan terhadap " . ucfirst($h);
            $meta = [];
            if ($biotipeH) $meta[] = "biotipe $biotipeH";
            if ($skorH) $meta[] = "skor $skorH";
            if ($meta) $s .= " (" . implode(', ', $meta) . ")";
            $hama[] = $s;
        }
        $penyakit = [];
        foreach ($pTokens as $p) {
            $s = "Tahan terhadap " . ucfirst($p);
            $meta = [];
            if ($patotipeP) $meta[] = "patotipe $patotipeP";
            if ($skorP) $meta[] = "skor $skorP";
            if ($meta) $s .= " (" . implode(', ', $meta) . ")";
            $penyakit[] = $s;
        }
        return [
            'hama' => $hama,
            'penyakit' => $penyakit,
        ];
    }

    public static function summarizeByName(string $name): ?array
    {
        $row = static::getVarietyByName($name);
        if (!$row) return null;
        return [
            'public' => static::summarizePublic($row),
            'farmer' => static::summarizeFarmer($row),
        ];
    }
}
