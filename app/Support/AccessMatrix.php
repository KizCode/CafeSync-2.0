<?php

namespace App\Support;

class AccessMatrix
{
    public const NONE = '—';

    public const READ = 'R';

    public const CREATE_READ = 'CR';

    public const READ_UPDATE = 'RU';

    public const CRUD = 'CRUD';

    /**
     * @return list<string>
     */
    public static function roles(): array
    {
        return ['owner', 'admin', 'kasir', 'gudang'];
    }

    /**
     * @return array<string, string>
     */
    public static function roleLabels(): array
    {
        return [
            'owner' => 'Owner',
            'admin' => 'Admin/Manager',
            'kasir' => 'Kasir',
            'gudang' => 'Staff Gudang',
        ];
    }

    /**
     * @return list<array{feature: string, owner: string, admin: string, kasir: string, gudang: string}>
     */
    public static function rows(): array
    {
        return [
            ['feature' => 'Login', 'owner' => self::READ, 'admin' => self::READ, 'kasir' => self::READ, 'gudang' => self::READ],
            ['feature' => 'Dashboard', 'owner' => self::READ, 'admin' => self::READ, 'kasir' => self::READ, 'gudang' => self::READ],
            ['feature' => 'POS', 'owner' => self::NONE, 'admin' => self::CRUD, 'kasir' => self::CRUD, 'gudang' => self::NONE],
            ['feature' => 'Pesanan', 'owner' => self::READ, 'admin' => self::CRUD, 'kasir' => self::CRUD, 'gudang' => self::READ],
            ['feature' => 'Proses Order', 'owner' => self::NONE, 'admin' => self::CRUD, 'kasir' => self::READ_UPDATE, 'gudang' => self::NONE],
            ['feature' => 'Riwayat Transaksi', 'owner' => self::READ, 'admin' => self::CRUD, 'kasir' => self::READ, 'gudang' => self::NONE],
            ['feature' => 'Laporan Penjualan', 'owner' => self::READ, 'admin' => self::CRUD, 'kasir' => self::NONE, 'gudang' => self::NONE],
            ['feature' => 'Analitik Penjualan', 'owner' => self::READ, 'admin' => self::READ, 'kasir' => self::NONE, 'gudang' => self::NONE],
            ['feature' => 'Produk/Menu', 'owner' => self::READ, 'admin' => self::CRUD, 'kasir' => self::READ, 'gudang' => self::NONE],
            ['feature' => 'Supplier', 'owner' => self::READ, 'admin' => self::CRUD, 'kasir' => self::NONE, 'gudang' => self::CRUD],
            ['feature' => 'Bahan Baku', 'owner' => self::READ, 'admin' => self::CRUD, 'kasir' => self::NONE, 'gudang' => self::CRUD],
            ['feature' => 'Purchase Order', 'owner' => self::READ, 'admin' => self::CRUD, 'kasir' => self::NONE, 'gudang' => self::CRUD],
            ['feature' => 'Riwayat Stok', 'owner' => self::READ, 'admin' => self::CRUD, 'kasir' => self::NONE, 'gudang' => self::CREATE_READ],
            ['feature' => 'Kelola User', 'owner' => self::NONE, 'admin' => self::CRUD, 'kasir' => self::NONE, 'gudang' => self::NONE],
        ];
    }

    public static function allows(string $role, string $feature): bool
    {
        foreach (self::rows() as $row) {
            if ($row['feature'] !== $feature) {
                continue;
            }

            return ($row[$role] ?? self::NONE) !== self::NONE;
        }

        return false;
    }
}
