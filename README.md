# CafeSync

Aplikasi web coffee shop untuk operasional kasir, backoffice admin, pantauan owner, dan stok gudang. Dibangun dengan **Laravel 13 (PHP 8.3)**, Blade MVC, Tailwind CSS v4, dan Vite.

## Fitur yang sudah jalan

| Area | Yang bisa dilakukan |
| --- | --- |
| Autentikasi | Login & register 2 langkah (layout [CafeSync Remake](https://www.figma.com/design/AAeHERmHp8xZcK7X6EcNGt/CafeSync-Remake?node-id=4-2): panel foto kiri, form kanan, tombol hijau) |
| Kasir | Dashboard, POS + keranjang, pembayaran, pesanan, riwayat, struk ([Figma Remake 30-1359](https://www.figma.com/design/AAeHERmHp8xZcK7X6EcNGt/CafeSync-Remake?node-id=30-1359)): sidebar gelap, latar krem, tombol hijau |
| Admin | Dashboard backoffice, laporan + export CSV, analitik, pengguna, menu, hak akses, POS/pesanan/riwayat, dan gudang |
| Owner | Dashboard [Figma Remake 8-463](https://www.figma.com/design/AAeHERmHp8xZcK7X6EcNGt/CafeSync-Remake?node-id=8-463): sapaan, 4 KPI berikon, grafik 7 hari, donut pembayaran, terlaris, stok, transaksi terbaru. Plus laporan & analitik |
| Gudang | Dashboard stok produk |
| Profil | Setiap akun membuka `/profile` dari menu avatar (bukan sidebar): nama, username, email, password |

Profil akun dibuka dari **menu avatar** di navbar (Profil + Keluar), tidak ada item Profil di sidebar.

## Cara pemakaian

### Menjalankan aplikasi

```bash
composer setup
php artisan db:seed
composer run dev
```

Atau terpisah:

```bash
php artisan serve
npm run dev
```

Buka `http://localhost:8000` (HTTP, bukan HTTPS). Root `/` mengarah ke `/login`. Halaman masuk dan daftar memakai split screen: kiri foto + peran/manfaat, kanan form.

Setelah daftar, akun baru tetap berperan **kasir** (sesuai aplikasi saat ini), meski copy Figma menyebut customer.

Jika aset frontend tidak berubah, jalankan `npm run build` atau biarkan `npm run dev` menyala.

### Akun lokal (hasil seeder)

Hanya untuk development. Jangan dipakai di produksi.

| Peran | Email / username | Password |
| --- | --- | --- |
| Admin | `admin@cafesync.test` / `admin` | `admin123` |
| Kasir | `kasir@cafesync.test` / `kasir` | `kasir123` |
| Owner | `owner@coffee.com` / `owner` | `owner123` |
| Gudang | `gudang@coffee.com` / `gudang` | `gudang123` |

Register publik membuat akun **kasir** baru.

Profil: klik inisial di kanan atas → **Profil** (`/profile`). Peran tidak bisa diubah dari halaman ini.

### Alur kasir (POS)

1. Masuk sebagai kasir → diarahkan ke POS (`/kasir`). Dashboard ada di `/kasir/dashboard`.
2. Pilih menu (foto + kategori), atur jumlah di **Keranjang**, isi nama pelanggan bila perlu.
3. **Bayar** → konfirmasi metode & nominal → stok berkurang, transaksi tercatat atas kasir yang login.
4. Lihat **Pesanan**, **Riwayat**, atau cetak **struk**. Kasir lain tidak bisa membuka detail transaksi milik kasir lain.

### Alur admin

1. Masuk sebagai admin → dashboard backoffice (`/admin`).
2. Sidebar admin: **Dashboard**, **Gudang**, **POS / Pesanan / Riwayat**, **Laporan**, **Analitik**, **Menu / Pengguna / Hak Akses**.
3. **Laporan** (`/admin/laporan`) dan **Analitik**.

### Owner dan gudang

- Owner: `/owner` — dashboard Figma 8-463 (krem). Sidebar: Dashboard, Laporan, Analitik. Tanpa POS dan tanpa kelola user/menu.
- Gudang: `/gudang` — ringkasan stok. Manajemen produk penuh tetap di admin.

## Hak akses (matriks)

Kode: **C** Create, **R** Read, **U** Update, **D** Delete, **CRUD** lengkap, **RU** Read+Update, **CR** Create+Read, **—** tidak ada akses.

Sumber yang sama ditampilkan di `/admin/access` (`App\Support\AccessMatrix`).

| Fitur | Owner | Admin/Manager | Kasir | Staff Gudang |
| --- | --- | --- | --- | --- |
| Login | R | R | R | R |
| Dashboard | R | R | R | R |
| POS | — | CRUD | CRUD | — |
| Pesanan | R | CRUD | CRUD | R |
| Proses Order | — | CRUD | RU | — |
| Riwayat Transaksi | R | CRUD | R | — |
| Laporan Penjualan | R | CRUD | — | — |
| Analitik Penjualan | R | R | — | — |
| Produk/Menu | R | CRUD | R | — |
| Supplier | R | CRUD | — | CRUD |
| Bahan Baku | R | CRUD | — | CRUD |
| Purchase Order | R | CRUD | — | CRUD |
| Riwayat Stok | R | CRUD | — | CR |
| Kelola User | — | CRUD | — | — |

**Catatan implementasi saat ini:** Admin boleh masuk POS, pesanan, riwayat, dan gudang. Owner boleh baca laporan dan analitik (termasuk export CSV). Dashboard `/owner` memakai controller yang sama dengan `/admin` untuk KPI; kasir tidak diarahkan ke dashboard itu. Kasir lain tidak melihat transaksi kasir lain; admin melihat semua penjualan lunas. Pengeluaran di laporan masih 0 karena belum ada data biaya. Supplier, bahan baku, dan purchase order **belum punya halaman** — nilai di matriks adalah target hak akses.

## Arsitektur

Alur: rute → controller → Eloquent → Blade. Tidak memakai service/repository/Livewire.

Pendukung Laravel (bukan lapisan arsitektur terpisah): Form Request, Policy, `RoleMiddleware`, `AccessMatrix`.

## Perintah berguna

```bash
php artisan migrate
php artisan db:seed
php artisan test --compact
vendor/bin/pint --dirty --format agent
npm run build
```

## Lisensi

Proyek ini memakai kerangka Laravel (MIT). Lihat [LICENSE](https://opensource.org/licenses/MIT) untuk kerangka kerja.
