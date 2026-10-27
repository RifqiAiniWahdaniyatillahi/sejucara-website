<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;
class RajaOngkirService
{
    public function isConfigured(): bool
    {
        return filled(config('services.rajaongkir.key')) && filled(config('services.rajaongkir.origin_id'));
    }

    public function searchDestinations(string $keyword): array
    {
        if (! filled(config('services.rajaongkir.key')) || mb_strlen($keyword) < 3) {
            return [];
        }

        try {
            $response = Http::withHeaders($this->headers())
                ->timeout(12)
                ->get($this->url('destination/domestic-destination'), [
                    'search' => $keyword,
                    'limit' => 10,
                    'offset' => 0,
                ]);
        } catch (Throwable) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        return collect($response->json('data', []))
            ->map(fn (array $item) => [
                'id' => (string) ($item['id'] ?? $item['destination_id'] ?? ''),
                'label' => $this->destinationLabel($item),
            ])
            ->filter(fn (array $item) => filled($item['id']) && filled($item['label']))
            ->values()
            ->all();
    }

    public function rates(string $destinationId, string $courier, int $weight, int $itemValue): array
    {
        if (! $this->isConfigured() || blank($destinationId) || blank($courier)) {
            return [];
        }

        try {
            $response = Http::asForm()
                ->withHeaders($this->headers())
                ->timeout(15)
                ->post($this->url('calculate/domestic-cost'), [
                    'origin' => config('services.rajaongkir.origin_id'),
                'destination' => $destinationId,
                'weight' => max(1, $weight),
                'courier' => $courier,
                'price' => 'lowest',
            ]);
        } catch (Throwable) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        return $this->extractRates($response->json('data', []), $courier);
    }

    private function headers(): array
    {
        return [
            'key' => config('services.rajaongkir.key'),
            'Accept' => 'application/json',
        ];
    }

    private function url(string $path): string
    {
        return rtrim((string) config('services.rajaongkir.base_url'), '/').'/'.ltrim($path, '/');
    }

    private function destinationLabel(array $item): string
    {
        if (filled($item['label'] ?? null)) {
            return (string) $item['label'];
        }

        return collect([
            $item['subdistrict_name'] ?? null,
            $item['district_name'] ?? null,
            $item['city_name'] ?? $item['city'] ?? null,
            $item['province_name'] ?? $item['province'] ?? null,
            $item['zip_code'] ?? null,
        ])->filter()->implode(', ');
    }

    private function extractRates(array $data, string $courier): array
    {
        return collect($data)
            ->map(function (array $item) use ($courier) {
                $cost = (int) ($item['cost']
                    ?? $item['shipping_cost']
                    ?? $item['shipping_cost_net']
                    ?? 0);

                if ($cost <= 0) {
                    return null;
                }

                return [
                    'courier' => strtoupper((string) ($item['code'] ?? $item['name'] ?? $courier)),
                    'service' => (string) ($item['service'] ?? $item['service_name'] ?? $item['type'] ?? 'REG'),
                    'cost' => $cost,
                    'estimate' => (string) ($item['etd'] ?? $item['estimation'] ?? $item['sla'] ?? 'Estimasi mengikuti kurir'),
                    'description' => (string) ($item['description'] ?? ''),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
