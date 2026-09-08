<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PieSocketService
{
    /**
     * Publish an event to PieSocket REST API.
     *
     * @param string $event Event name, e.g. "birth_alert"
     * @param array $data Associated payload data
     * @return bool
     */
    public static function publish(string $event, array $data = []): bool
    {
        $clusterId = config('services.piesocket.cluster_id', 'free.blr2');
        $apiKey    = config('services.piesocket.api_key', '8Z72V1NXBvXdXABPTyxgAvUvLWUl8ZsTAJdaqskK');
        $apiSecret = config('services.piesocket.api_secret', 'jjYOsAJO0iYDRadfcMJtWa2knahuEjzt');
        $roomId    = (string) config('services.piesocket.room_id', '1');

        $url = "https://{$clusterId}.piesocket.com/api/publish";

        $messagePayload = json_encode([
            'event'     => $event,
            'data'      => $data,
            'timestamp' => now()->toIso8601String(),
        ]);

        try {
            $response = Http::timeout(8)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ])
                ->post($url, [
                    'key'     => $apiKey,
                    'secret'  => $apiSecret,
                    'roomId'  => $roomId,
                    'message' => $messagePayload,
                ]);

            if ($response->successful()) {
                Log::info("PieSocket published successfully: [{$event}] to room [{$roomId}]");
                return true;
            }

            Log::warning("PieSocket publish warning: HTTP {$response->status()} - {$response->body()}");
            return false;
        } catch (\Throwable $e) {
            Log::error("PieSocket publish exception: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Broadcast an alert specifically for Birth / Kelahiran.
     *
     * @param array $patient
     * @param array $birthData
     * @return bool
     */
    public static function broadcastBirthAlert(array $patient, array $birthData = []): bool
    {
        $payload = [
            'type'                => 'birth_alert',
            'title'               => 'ALERTA KELAHIRAN BARU!',
            'patient_id'          => $patient['id'] ?? null,
            'nama_lengkap'        => $patient['nama_lengkap'] ?? 'Ibu Hamil',
            'nama_suami'          => $patient['nama_suami'] ?? null,
            'umur'                => $patient['umur'] ?? null,
            'nik'                 => $patient['nik'] ?? null,
            'no_telepon'          => $patient['no_telepon'] ?? null,
            'kelurahan'           => $patient['kelurahan'] ?? 'Puskesmas',
            'kabupaten'           => $patient['kabupaten'] ?? 'Kab. Tangerang',
            'tanggal_bersalin'    => $birthData['tanggal_bersalin'] ?? date('Y-m-d'),
            'tempat_bersalin'     => $birthData['tempat_bersalin'] ?? 'Puskesmas / Faskes',
            'penolong_persalinan' => $birthData['penolong_persalinan'] ?? 'Bidan / Nakes',
            'kondisi_bayi'        => $birthData['kondisi_bayi'] ?? 'Sehat',
            'berat_lahir_bayi'    => $birthData['berat_lahir_bayi'] ?? null,
            'komplikasi'          => $birthData['komplikasi_persalinan'] ?? null,
            'status_risti'        => $patient['status_risti'] ?? 'Normal',
            'kategori_poedji'     => $patient['kategori_poedji_rochjati'] ?? 'KRR',
            'created_at_time'     => now()->format('H:i:s, d M Y'),
        ];

        return self::publish('birth_alert', $payload);
    }
}
