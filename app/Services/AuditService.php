<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuditService
{
    private const FILE = 'audit_log.json';
    private const MAX  = 2000;

    public static function log(string $action, string $description, array $extra = []): void
    {
        $entry = [
            'id'          => uniqid('', true),
            'action'      => $action,
            'description' => $description,
            'user_id'     => optional(Auth::user())->id,
            'user_name'   => optional(Auth::user())->name,
            'user_email'  => optional(Auth::user())->email,
            'tenant_id'   => optional(Auth::user())->tenant_id,
            'ip'          => request()->ip(),
            'extra'       => $extra,
            'created_at'  => now()->toIso8601String(),
        ];

        $existing = self::all();
        array_unshift($existing, $entry);

        if (count($existing) > self::MAX) {
            $existing = array_slice($existing, 0, self::MAX);
        }

        Storage::put(self::FILE, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public static function all(): array
    {
        if (!Storage::exists(self::FILE)) {
            return [];
        }
        $decoded = json_decode(Storage::get(self::FILE), true);
        return is_array($decoded) ? $decoded : [];
    }

    public static function paginate(int $perPage = 50, int $page = 1): array
    {
        $all    = self::all();
        $total  = count($all);
        $offset = ($page - 1) * $perPage;
        $items  = array_slice($all, $offset, $perPage);

        return [
            'items'      => $items,
            'total'      => $total,
            'per_page'   => $perPage,
            'page'       => $page,
            'last_page'  => (int) ceil($total / $perPage),
        ];
    }
}
