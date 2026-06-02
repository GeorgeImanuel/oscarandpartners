# OKJ Project Overview

Tanggal ringkas: 2026-05-06
Versi aplikasi: 0.2.7 (config/constants.php)

## 0) Scope Laporan (Penting)
- Modul Card/Kartu Produksi tidak dibahas dalam laporan ini.
- Jika ada referensi teknis ke card_id atau record produksi internal, cukup sebut sebagai penghubung policy-client-payment tanpa detail.
- Fokus laporan pada policy, payment, claim, report, dan master data.

## 1) Ringkasan Project
Aplikasi ini adalah sistem manajemen produksi asuransi yang mencakup data klien, relasi/keluarga, objek tertanggung, polis, pembayaran, klaim, dan laporan evaluasi. Fokus utamanya adalah:
- Mengelola data master (relation, client, object, occupation, interest, coverage, currency, insurance).
- Membuat polis dan menghubungkan polis ke objek dan coverage.
- Mengelola cicilan pembayaran (installments), status pembayaran client dan insurance.
- Mengelola klaim dan laporan evaluasi produksi serta laporan klaim tahunan.

## 2) Tech Stack
- Backend: PHP 8.2, Laravel 12
- Frontend: Blade + Livewire 3.7
- UI/build: Tailwind CSS 4, Vite 7, laravel-vite-plugin
- Library utama:
  - spatie/laravel-permission (role dan permission)
  - spatie/laravel-activitylog (dependency tersedia, penggunaan belum terlihat eksplisit)
  - yaza/laravel-repository-service (pola Repository + Service)
- Database: default sqlite (bisa mysql/mariadb/pgsql/sqlsrv via config/database.php)

## 3) Struktur Direktori (High Level)
- app/
  - Http/Controllers: controller modul bisnis
  - Http/Requests: validasi form (Store/Update per modul)
  - Models: Eloquent model dan relasi
  - Repositories: akses data terstruktur
  - Services: business logic
  - Livewire: komponen Livewire untuk form dinamis
  - Helpers: helper fungsi perhitungan dan formatting
- database/migrations: definisi schema data
- resources/views: Blade views per modul
- resources/js, resources/css: entry frontend untuk Vite
- routes: web.php dan admin_web.php

## 4) Arsitektur Backend
### 4.1 Alur Request
Controller -> Service -> Repository -> Model (Eloquent)

### 4.2 Pattern Repository + Service
Semua modul utama memakai LaravelEasyRepository:
- Repository mengurus query dan relasi.
- Service mengurus business logic (transaksi, perhitungan, aturan domain).

### 4.3 Helper
- app/Helpers/FormulaHelper.php
  - calculate_payment, premi_value, commision_netto, dll.
- app/Helpers/FormatHelper.php
  - renderDueDate (status jatuh tempo)
  - numberFormat (format rupiah)
- app/Helpers/RouteHelper.php
  - routeActive/prefixActive untuk menu aktif

### 4.4 Auth dan Roles
- Login pakai username + password.
- Role yang disebut: owner, admin, finance, client.
- Spatie Permission dipakai di User model.

### 4.5 Global View Composer
AppServiceProvider boot() menyuntikkan overdueNotifications ke semua view berdasarkan PaymentRepository.

## 5) Modul Bisnis dan Logika Utama
### 5.1 User dan Auth
- AuthController: login, auth, logout.
- UserService: create/update user dengan role, validasi aktif.
- UserRepository: filter admin/finance.

### 5.2 Relation dan Client
- Relation: grup/keluarga (code, name).
- Client: terhubung ke Relation.
- ClientService: create/update client, delete dengan guard jika client sudah terkait data produksi internal.

### 5.3 Object dan Interest
- Object adalah objek tertanggung (kendaraan, properti, dll).
- ObjectOccupation adalah kategori objek.
- ObjectService: create/update object dengan interest dan occupation (termasuk input nama occupation baru).
- ObjectRepository: query object + interest + coverage.

### 5.4 Coverage dan Currency
- Coverage adalah jenis jaminan dengan code unik (PAR, EQ, CIS).
- Currency dipakai untuk kurs nilai premi/komisi pada report.

### 5.5 Insurance
- Insurance adalah perusahaan asuransi (principal).
- PolicyInsurance menghubungkan policy dan insurance dengan is_main, percentage, dan tax.

### 5.6 Policy
- PolicyService:
  - createPolicy/updatePolicy: header policy, object, coverage, insurance, dan sync object coverages.
  - allActive dan allByActiveDate untuk listing periode.
  - delete policy punya guard jika sudah dipakai oleh data produksi internal atau claim.
- PolicyRepository:
  - findWithRelations memuat objects, coverages, insurances, currency.
- Livewire PolicyForm:
  - load policy, set currency code, objectId, coverage_percentage.

### 5.7 Payments
- PaymentService:
  - getOverduePayments.
  - processClientPayment dan processInsurancePayment set status lunas.
- PaymentController:
  - updateInstallments: hapus payment lama lalu create ulang berdasarkan cicilan.
  - overdue endpoint.
- PaymentRepository:
  - getMonthlySumByYears untuk report evaluasi (join policy + currency).

### 5.8 Claims
- ClaimService + ClaimRepository:
  - listing with pagination dan search by policy_number atau client id_name.
- ClaimController: CRUD klaim.

### 5.9 Reports
- ReportService:
  - generateEvaluationReport: akumulasi premi dan komisi per bulan, growth dibanding tahun sebelumnya.
  - generateClaimsReport: ringkasan jumlah klaim, estimasi, bayar, outstanding per bulan.
- ReportController: endpoint JSON untuk report.

### 5.10 Dashboard
- DashboardController:
  - total client
  - policy aktif
  - overdue payments (status pending)
  - grafik 12 bulan premi dan komisi

## 6) Struktur Data (Schema Utama)
Catatan: field di bawah diambil dari migrations di database/migrations.

### 6.1 Users
- users: id, username, password, name, email, is_active, last_login_at, timestamps

### 6.2 Master
- relations: id, code, name, timestamps
- clients: id, relation_id, id_name, id_address, timestamps
- object_occupation: id, name, timestamps
- objects: id, object_occupation_id, name, tjh_value, keterangan, tjh, timestamps
- interests: id, name, timestamps
- coverages: code (PK), name
- currencies: code (PK), name, value, timestamps
- insurances: id, name, timestamps
- banks: id, kode_bank, nama_bank, alias, is_active, timestamps

### 6.3 Policy
- policies: id, policy_number, currency_code, started_date, ended_date, timestamps, soft_deletes
- policy_objects: policy_id, object_id
- policy_coverages: policy_id, coverage_code, percentage
- policy_insurances: policy_id, insurance_id, is_main, percentage, tax, timestamps

### 6.4 Payment
- payment_types: id, name, timestamps
- payment_statuses: id, name, color, timestamps
- payments:
  - fk: card_id (record produksi internal, tidak dibahas), created_by, payment_status_id, insurance_payment_status_id (nullable)
  - sequence, amount, deadline_at, paid_at, paid_amount, insurance_paid_at, insurance_paid_amount
  - timestamps

### 6.5 Claims
- claims: id, policy_id, incident_date, insurance_report_date, chronology, docs, casco, tjh, note, payment_date, amount (nullable), timestamps

### 6.6 Sistem
- cache, cache_locks
- sessions
- spatie permission tables: permissions, roles, model_has_roles, model_has_permissions, role_has_permissions
- audit_logs (custom): user_id, action, target_table, target_id, old_value, new_value, ip_address, created_at

## 7) Relasi Utama Antar Model
- User hasOne Client
- Client belongsTo Relation
- Policy belongsTo Currency
- Policy belongsToMany Objects (policy_objects)
- Policy belongsToMany Coverages (policy_coverages)
- Policy belongsToMany Insurances (policy_insurances)
- Object belongsTo ObjectOccupation
- Object hasMany ObjectInterests dan ObjectCoverages
- Payment belongsTo PaymentStatus (client dan insurance)
- Payment terhubung ke record produksi internal (card_id) namun tidak dibahas.
- Claim belongsTo Policy

## 8) Frontend
- Blade templates di resources/views (folder per modul: policies, payments, claims, reports, master data, dll)
- Livewire components:
  - ClientForm: select relation
  - CurrencyForm dan CurrencyDetails
  - ObjectForm: occupation, interests, coverages
  - PolicyForm: object, coverages, insurances, currency
- Tailwind CSS 4 via Vite, entry di resources/css/app.css
- Vite entry di resources/js/app.js

## 9) Konfigurasi dan Runtime
- config/app.php: setting umum Laravel
- config/auth.php: guard web, user provider eloquent
- config/constants.php: status dan tipe ID
  - payment_status: pending=1, completed=2, failed=3, refunded=4
  - payment_type: penuh=1, cicilan=2
- config/database.php: default sqlite
- config/permission.php: spatie permission

## 10) Script dan Perintah Build
Dari composer.json:
- setup: install dep, generate key, migrate, npm install, npm run build
- dev: concurrently php artisan serve + queue:listen + npm run dev
- test: php artisan test

Dari package.json:
- dev: vite
- build: vite build

## 11) Catatan Konsistensi Schema vs Model
Ini penting untuk laporan teknis jika ingin di-follow up:
- Model Insurance memiliki field bank_account, va_number, default_commission_pct, namun migrations hanya membuat kolom name.
- Model Client punya relasi user (user_id), tetapi migrations clients tidak menambahkan user_id.
- PaymentService menangani client_evident_file, namun kolom di payments belum terlihat pada migrations.

Jika perlu, bagian ini bisa dikembangkan jadi daftar TODO teknis.

## 12) Use Case (Ringkas per Peran)
Catatan: ini merujuk ke perilaku yang terlihat di code saat ini.

- Owner
  - Akses dashboard ringkasan dan grafik.
  - CRUD master data (relation, client, object, occupation, interest, coverage, currency, insurance, user).
  - CRUD policy, payment, claim.
  - Lihat report evaluasi produksi dan report klaim.

- Admin
  - Akses dashboard ringkasan dan grafik.
  - CRUD master data (relation, client, object, occupation, interest, coverage, currency, insurance, user).
  - CRUD policy, payment, claim.
  - Lihat report evaluasi produksi dan report klaim.

- Finance
  - Akses dashboard ringkasan dan grafik.
  - Kelola pembayaran: ubah cicilan, update bayar client, update bayar insurance.
  - Lihat list overdue payments.
  - Lihat report evaluasi produksi dan report klaim.

- Client
  - Akses dashboard ringkasan dan grafik.
  - Lihat data polis dan pembayaran terkait relation-nya (filter otomatis).

## 13) User Story
- Sebagai owner/admin, saya ingin menambah policy beserta objek, coverage, dan insurance agar data polis konsisten.
- Sebagai finance, saya ingin mengubah cicilan pembayaran agar jadwal dan nominal tagihan sesuai kesepakatan.
- Sebagai finance, saya ingin menandai pembayaran client dan insurance sebagai lunas agar status terupdate.
- Sebagai admin, saya ingin melihat pembayaran yang overdue agar bisa follow up.
- Sebagai admin, saya ingin melihat laporan evaluasi produksi per bulan untuk perbandingan tahun berjalan.
- Sebagai admin, saya ingin melihat laporan klaim tahunan untuk monitoring outstanding.
- Sebagai client, saya ingin melihat data polis dan pembayaran yang terkait relation saya agar transparan.
- Sebagai admin, saya ingin mengelola master data (coverage, currency, insurance) agar perhitungan akurat.
- Sebagai admin, saya ingin mengelola user dan role agar akses terkontrol.

## 14) Aturan Bisnis dan Guard Utama
- Policy tidak bisa dihapus jika sudah dipakai pada data produksi internal atau claim.
- Client tidak bisa dihapus jika sudah terkait data produksi internal.
- Object tidak bisa dihapus jika sudah dipakai pada policy_objects.
- User tidak bisa dihapus jika terkait client, atau jika membuat payment.
- Persentase coverage dan insurance disimpan sebagai desimal (input persen dibagi 100).
- Pembayaran client dan insurance di-set status lunas melalui proses update pembayaran.

## 15) Alur Data Utama
- Create Policy:
  - Buat header policy.
  - Sync object, coverage, insurance.
  - Jika policy coverage kosong, object coverage dipakai untuk kalkulasi premi.

- Update Installments:
  - Hapus semua payment lama pada record produksi internal.
  - Buat ulang payment berdasarkan cicilan baru dan status lama.

- Report Evaluasi:
  - Join data produksi internal + policy + currency untuk total premi/komisi per bulan.
  - Hitung akumulasi dan growth per bulan.

## 16) Endpoint dan Halaman Utama
- Auth: /login (GET/POST), /logout (POST)
- Dashboard: /dashboard
- Policies: /policies, /policies/{id}
- Payments: /payments, /payments/{id}, /payments/overdue
- Claims: /claims, /claims/{id}
- Reports:
  - /reports/evaluation (page)
  - /reports/evaluation/data (JSON)
  - /reports/claims (page)
  - /reports/claims/data (JSON)

## 17) Validasi Form (Request Classes)
Request classes penting:
- Store/Update: Policy, Client, Claim, Coverage, Insurance, Interest, Object, ObjectOccupation, Relation, User
- Update: Installments, ClientPayment, InsurancePayment, Profile
- Report: GetEvaluationRequest, GetClaimsRequest

## 18) Dokumentasi Operasional
- Dev script menjalankan: php artisan serve, queue:listen, dan vite dev.
- Setup script menjalankan migrasi dan build asset.
- Default DB sqlite, bisa pindah ke mysql/mariadb/pgsql/sqlsrv.

## 19) Catatan Dokumentasi Tambahan
- Audit logs table tersedia, namun pemakaian eksplisit belum terlihat.
- Package activity log tersedia, pemakaian eksplisit belum terlihat.
- Beberapa mismatch schema vs model ada di bagian 11.
