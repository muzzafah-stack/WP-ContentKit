# WP ContentKit

**Smart Tools for Better Content.**

WP ContentKit adalah plugin WordPress modular dan ultra-ringan yang menggabungkan:
1. **Smart Table of Contents (TOC) berbasis Server-Side Rendering (SSR)** untuk Elementor.
2. **Interactive Inline Content Box & HTML Code Generator** untuk WordPress Classic Editor, Elementor, Gutenberg, dan Email/External CMS.

Kompatibel penuh dengan **WordPress 7.1.2 & 6.x**, **Elementor 4.3.1 & 3.x**, serta **PHP 7.4 - 8.3+**.

---

## 🚀 Panduan Menggunakan Content Box

### 1. Menggunakan di Classic Editor (WordPress Classic Editor)
Tersedia 3 cara mudah untuk mengakses template Content Box di Classic Editor:
- **Tombol "Content Box" (Media Buttons)**: Terletak tepat di samping tombol **"Tambah Media" (Add Media)** di atas editor. Klik tombol ini untuk membuka modal generator.
- **Ikon Toolbar TinyMCE (Visual Mode)**: Klik ikon kotak pada deretan toolbar TinyMCE.
- **Tombol Quicktags (Text / HTML Mode)**: Klik tombol `Content Box` pada deretan tombol HTML mode.

**Langkah penggunaan di modal:**
1. Pilih salah satu template (Poin Penting, Author Box, Reviewed By, Related Content, Catatan, Warning, Simple Info, Custom Box).
2. Isi atau sesuaikan konten, warna latar, dan warna aksen border secara live.
3. Pratinjau langsung tampilan di panel **Visual Preview** atau lihat kodenya di tab **Kode HTML**.
4. Klik **"Insert Box ke Editor"** untuk langsung menyematkan ke artikel pada posisi kursor, atau klik **"Salin Kode HTML"** untuk menyalin kodenya.

---

### 2. Generate Langsung Kode HTML (Admin Studio)
Jika Anda ingin menghasilkan kode HTML siap pakai tanpa harus membuka editor postingan:
1. Masuk ke menu **WP ContentKit** di sidebar admin WordPress.
2. Buka tab **Content Box Generator**.
3. Pilih template, sesuaikan teks/warna.
4. Klik tombol **"Salin Kode HTML"** (atau beralih ke tab *Kode HTML* untuk melihat kodenya).
5. Kode HTML murni (100% Inline CSS) langsung tersalin ke clipboard dan siap ditempel ke:
   - **Elementor**: Widget *HTML* atau *Text Editor*.
   - **Gutenberg**: Blok *Custom HTML*.
   - **Email Marketing / Newsletter**: Mailchimp, Kirim.Email, ConvertKit, Gmail, dll.
   - **Website Eksternal / CMS Lain**.

---

## ✨ Fitur Utama

### 1. Smart Table of Contents (Elementor Add-on)
- **100% Server-Side Rendered (SSR)**: Bebas loading spinner & kebal terhadap JS delay/defer (Perfmatters, FlyingPress, WP Rocket, LiteSpeed Cache, Cloudflare).
- **SEO Heading Hierarchy Validator**: Mendeteksi loncatan struktur heading (H2–H6) dan memberikan saran SEO ramah pemula langsung di panel Elementor.
- **Stable Anchor IDs**: Slug ID unik otomatis tanpa tabrakan nama heading duplikat.
- **Smooth Scroll & Sticky Header Offset**: Navigasi mulus dengan kompensasi jarak header sticky agar judul tidak tertutup.
- **Dukungan Elementor 4.3.1 & 3.5+**: Terdaftar rapi di kategori widget *WP ContentKit*.

### 2. 8 Template Inline Content Box Siap Pakai
1. **Poin Penting (Important Points)**: Highlight border kiri biru tebal dengan bullet points rapi.
2. **Author Box**: Profil penulis lengkap dengan avatar, jabatan, bio, dan link profil.
3. **Reviewed By / Ditinjau Oleh (E-E-A-T)**: Verifikasi kredibilitas ahli bersertifikasi dengan gaya kotak dashed.
4. **Related Content / Baca Juga**: Badge rekomendasi artikel terkait di tengah artikel.
5. **Note / Catatan**: Aksen amber hangat untuk tips atau klarifikasi penting.
6. **Warning / Peringatan**: Aksen merah waspada untuk disclaimer dan peringatan krusial.
7. **Simple Info Box**: Nuansa hijau minimalis untuk tips tambahan.
8. **Custom Box**: Kustomisasi bebas warna latar, warna teks, tebal border, gaya garis (solid/dashed/dotted/double), radius sudut, dan padding.

### 3. GitHub Auto-Updater
- Terintegrasi langsung dengan GitHub Releases repository `muzzafah-stack/WP-ContentKit` untuk update otomatis 1-klik di dashboard WordPress.

---

## 🛠️ Persyaratan Sistem
- **WordPress**: Versi 5.8 s/d 7.1.2+
- **PHP**: Versi 7.4 s/d 8.3+
- **Editor**: Classic Editor (Official plugin) dan/atau Elementor 3.5.0 s/d 4.3.1+

---

## 👤 Lisensi & Pengembang

- **Author**: [Hipnolink Team Digital](https://www.hipnolink.com)
- **Repository**: [muzzafah-stack/WP-ContentKit](https://github.com/muzzafah-stack/WP-ContentKit)
- **License**: GPL v2 or later
