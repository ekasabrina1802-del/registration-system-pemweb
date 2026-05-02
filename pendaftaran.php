<?php
session_start();

// Ambil error & data lama dari session (dikirim balik dari hasil_pendaftaran.php)
$errors = $_SESSION['form_errors'] ?? [];
$data   = $_SESSION['form_old']    ?? [];

// Hapus session setelah dibaca agar tidak muncul lagi saat refresh
unset($_SESSION['form_errors'], $_SESSION['form_old']);

// Helper: tambah class error pada div field
function hasErr($field, $errors) {
    return isset($errors[$field]) ? ' field-error' : '';
}

// 38 Kabupaten & Kota di Jawa Timur
$kota_jatim = [
    'Kab. Bangkalan', 'Kab. Banyuwangi', 'Kab. Blitar', 'Kab. Bojonegoro',
    'Kab. Bondowoso', 'Kab. Gresik', 'Kab. Jember', 'Kab. Jombang',
    'Kab. Kediri', 'Kab. Lamongan', 'Kab. Lumajang', 'Kab. Madiun',
    'Kab. Magetan', 'Kab. Malang', 'Kab. Mojokerto', 'Kab. Nganjuk',
    'Kab. Ngawi', 'Kab. Pacitan', 'Kab. Pamekasan', 'Kab. Pasuruan',
    'Kab. Ponorogo', 'Kab. Probolinggo', 'Kab. Sampang', 'Kab. Sidoarjo',
    'Kab. Situbondo', 'Kab. Sumenep', 'Kab. Trenggalek', 'Kab. Tuban',
    'Kab. Tulungagung',
    'Kota Batu', 'Kota Blitar', 'Kota Kediri', 'Kota Madiun',
    'Kota Malang', 'Kota Mojokerto', 'Kota Pasuruan', 'Kota Probolinggo',
    'Kota Surabaya',
];

$prodi_list = [
    'Pendidikan Teknologi Informasi', 'Teknik Informatika', 'Sistem Informasi', 'Teknik Komputer',
    'Ilmu Komunikasi', 'Manajemen', 'Akuntansi', 'Ekonomi Pembangunan',
    'Hukum', 'Psikologi', 'Pendidikan Bahasa Inggris',
    'Pendidikan Matematika', 'Teknik Sipil', 'Teknik Elektro',
    'Arsitektur', 'Kedokteran', 'Farmasi', 'Keperawatan',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pendaftaran Mahasiswa Baru — Universitas Nusantara</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --navy:   #0b1f3a;
    --gold:   #c9972c;
    --gold2:  #e8b84b;
    --cream:  #fdf8f0;
    --white:  #ffffff;
    --gray:   #6b7280;
    --border: #d4c5a9;
    --shadow: 0 8px 40px rgba(11,31,58,.12);
  }

  html { scroll-behavior: smooth; }
  body { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; color: var(--navy); }

  /* HEADER */
  .site-header { background: var(--navy); position: relative; overflow: hidden; }
  .site-header::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c9972c' fill-opacity='0.07'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }
  .header-inner {
    position: relative; z-index: 1;
    max-width: 860px; margin: 0 auto; padding: 48px 32px 40px;
    display: flex; align-items: center; gap: 28px;
  }
  .logo-circle {
    width: 80px; height: 80px; flex-shrink: 0; border-radius: 50%;
    border: 3px solid var(--gold); background: rgba(201,151,44,.12);
    display: flex; align-items: center; justify-content: center;
  }
  .logo-circle svg { width: 42px; height: 42px; }
  .header-text h1 { font-family: 'Playfair Display', serif; font-size: clamp(1.5rem,3vw,2rem); color: var(--white); line-height: 1.2; }
  .header-text p { color: var(--gold2); font-size: .85rem; font-weight: 500; letter-spacing: .08em; text-transform: uppercase; margin-top: 4px; }
  .gold-bar { height: 4px; background: linear-gradient(90deg, var(--gold), var(--gold2), var(--gold)); }

  /* PROGRESS */
  .progress-wrap { background: var(--navy); padding: 0 32px 28px; }
  .progress-inner { max-width: 860px; margin: 0 auto; display: flex; gap: 8px; align-items: center; }
  .prog-step { display: flex; align-items: center; gap: 8px; flex: 1; }
  .prog-dot {
    width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem; font-weight: 700;
    background: var(--gold); color: var(--navy);
  }
  .prog-label { font-size: .72rem; color: var(--gold2); font-weight: 500; white-space: nowrap; }
  .prog-line { flex: 1; height: 2px; background: rgba(255,255,255,.1); border-radius: 2px; }

  /* MAIN */
  .page-body { max-width: 860px; margin: 0 auto; padding: 40px 32px 80px; }

  /* ALERT */
  .alert { border-radius: 12px; padding: 18px 22px; margin-bottom: 28px; display: flex; gap: 14px; align-items: flex-start; animation: slideDown .4s ease; }
  .alert-error { background: #fef2f2; border: 1px solid #fca5a5; color: #c0392b; }
  .alert-icon { font-size: 1.3rem; flex-shrink: 0; }
  .alert ul { margin: 6px 0 0 18px; }
  .alert li { font-size: .875rem; margin-top: 3px; }
  .alert strong { display: block; font-size: .95rem; }
  @keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }

  /* CARD */
  .card { background: var(--white); border-radius: 20px; box-shadow: var(--shadow); overflow: hidden; margin-bottom: 24px; border: 1px solid rgba(212,197,169,.4); animation: fadeUp .5s ease both; }
  .card:nth-child(2) { animation-delay: .08s; }
  .card:nth-child(3) { animation-delay: .16s; }
  .card:nth-child(4) { animation-delay: .24s; }
  @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }

  .card-header { background: linear-gradient(135deg, var(--navy) 0%, #1a3a5c 100%); padding: 20px 28px; display: flex; align-items: center; gap: 14px; }
  .card-header-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(201,151,44,.25); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
  .card-header h2 { font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--white); font-weight: 700; }
  .card-header span { font-size: .75rem; color: var(--gold2); font-weight: 500; display: block; margin-top: 1px; }
  .card-body { padding: 28px; }

  /* GRID */
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
  .col-span-2 { grid-column: span 2; }
  @media (max-width: 640px) { .grid-2 { grid-template-columns: 1fr; } .col-span-2 { grid-column: span 1; } }

  /* FIELDS */
  .field { display: flex; flex-direction: column; gap: 6px; }
  label { font-size: .82rem; font-weight: 600; color: var(--navy); letter-spacing: .02em; }
  label .req { color: var(--gold); margin-left: 3px; }
  .field-hint { font-size: .75rem; color: var(--gray); }

  input[type="text"], input[type="email"], input[type="tel"],
  input[type="date"], input[type="number"], select, textarea {
    width: 100%; padding: 12px 16px;
    border: 1.5px solid var(--border); border-radius: 10px;
    font-family: 'DM Sans', sans-serif; font-size: .9rem; color: var(--navy);
    background: var(--cream); outline: none; appearance: none; -webkit-appearance: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
  }
  input:focus, select:focus, textarea:focus {
    border-color: var(--gold); background: var(--white);
    box-shadow: 0 0 0 3px rgba(201,151,44,.15);
  }
  select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 14px center;
    padding-right: 40px; cursor: pointer;
  }
  textarea { resize: vertical; min-height: 90px; }

  /* Field error */
  .field-error input, .field-error select, .field-error textarea {
    border-color: #e74c3c !important; background: #fff5f5 !important;
  }
  .field-msg { font-size: .75rem; color: #c0392b; display: flex; align-items: center; gap: 4px; }

  /* RADIO */
  .radio-group { display: flex; flex-wrap: wrap; gap: 10px; }
  .radio-label {
    display: flex; align-items: center; gap: 8px; cursor: pointer;
    padding: 9px 16px; border-radius: 8px;
    border: 1.5px solid var(--border); background: var(--cream);
    font-size: .875rem; font-weight: 500; transition: all .2s; user-select: none;
  }
  .radio-label:hover { border-color: var(--gold); background: rgba(201,151,44,.06); }
  .radio-group.has-error .radio-label { border-color: #e74c3c; background: #fff5f5; }
  input[type="radio"] { width: 16px; height: 16px; accent-color: var(--gold); cursor: pointer; }

  /* DIVIDER */
  .divider { border: none; border-top: 1px dashed var(--border); margin: 24px 0; }

  /* SUBMIT */
  .submit-section {
    background: var(--white); border-radius: 20px; box-shadow: var(--shadow);
    border: 1px solid rgba(212,197,169,.4); padding: 28px;
    display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;
    animation: fadeUp .5s .32s ease both;
  }
  .submit-note { font-size: .82rem; color: var(--gray); max-width: 400px; line-height: 1.6; }
  .submit-note strong { color: var(--navy); }
  .btn-submit {
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold2) 100%);
    color: var(--navy); font-family: 'DM Sans', sans-serif; font-weight: 700; font-size: 1rem;
    padding: 14px 40px; border: none; border-radius: 12px; cursor: pointer; white-space: nowrap;
    box-shadow: 0 4px 20px rgba(201,151,44,.4);
    transition: transform .2s, box-shadow .2s, filter .2s;
  }
  .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(201,151,44,.5); filter: brightness(1.05); }

  /* FOOTER */
  .site-footer { text-align: center; padding: 24px; font-size: .78rem; color: var(--gray); border-top: 1px solid var(--border); margin-top: 20px; }
  .site-footer strong { color: var(--navy); }
</style>
</head>
<body>

<!-- HEADER -->
<header class="site-header">
  <div class="header-inner">
    <div class="logo-circle">
      <svg viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M21 4L5 14v14l16 10 16-10V14L21 4z" stroke="#c9972c" stroke-width="2" fill="rgba(201,151,44,.15)"/>
        <path d="M21 10l-10 6v8l10 6 10-6v-8L21 10z" stroke="#e8b84b" stroke-width="1.5" fill="rgba(232,184,75,.1)"/>
        <circle cx="21" cy="22" r="4" fill="#c9972c"/>
        <path d="M17 16v-4M25 16v-4" stroke="#c9972c" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="header-text">
      <h1>Universitas Nusantara</h1>
      <p>Formulir Pendaftaran Mahasiswa Baru &mdash; Tahun Akademik 2025/2026</p>
    </div>
  </div>
</header>
<div class="gold-bar"></div>

<!-- PROGRESS -->
<div class="progress-wrap">
  <div class="progress-inner">
    <div class="prog-step"><div class="prog-dot">1</div><div class="prog-label">Data Pribadi</div></div>
    <div class="prog-line"></div>
    <div class="prog-step"><div class="prog-dot">2</div><div class="prog-label">Pendidikan</div></div>
    <div class="prog-line"></div>
    <div class="prog-step"><div class="prog-dot">3</div><div class="prog-label">Program</div></div>
    <div class="prog-line"></div>
    <div class="prog-step"><div class="prog-dot">✓</div><div class="prog-label">Submit</div></div>
  </div>
</div>

<!-- MAIN -->
<main class="page-body">

  <?php if (!empty($errors)): ?>
  <div class="alert alert-error">
    <div class="alert-icon">⚠️</div>
    <div>
      <strong>Harap perbaiki kesalahan berikut:</strong>
      <ul>
        <?php foreach ($errors as $msg): ?>
          <li><?= htmlspecialchars($msg) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php endif; ?>

  <form method="POST" action="hasil_pendaftaran.php" novalidate>

    <!-- KARTU 1: DATA DIRI -->
    <div class="card">
      <div class="card-header">
        <div class="card-header-icon">👤</div>
        <div>
          <h2>Data Diri Calon Mahasiswa</h2>
          <span>Isi dengan data sesuai dokumen resmi (KTP / Akta Kelahiran)</span>
        </div>
      </div>
      <div class="card-body">
        <div class="grid-2">

          <!-- Field 1: Nama Lengkap -->
          <div class="field col-span-2<?= hasErr('nama_lengkap', $errors) ?>">
            <label for="nama_lengkap">Nama Lengkap <span class="req">*</span></label>
            <input type="text" id="nama_lengkap" name="nama_lengkap"
                   placeholder="Sesuai KTP / Ijazah"
                   value="<?= htmlspecialchars($data['nama_lengkap'] ?? '') ?>">
            <?php if (isset($errors['nama_lengkap'])): ?>
              <div class="field-msg">⚠ <?= htmlspecialchars($errors['nama_lengkap']) ?></div>
            <?php endif; ?>
          </div>

          <!-- Field 2: NIK -->
          <div class="field<?= hasErr('nik', $errors) ?>">
            <label for="nik">NIK (16 Digit) <span class="req">*</span></label>
            <input type="text" id="nik" name="nik" maxlength="16"
                   placeholder="3578xxxxxxxxxxxx"
                   value="<?= htmlspecialchars($data['nik'] ?? '') ?>">
            <?php if (isset($errors['nik'])): ?>
              <div class="field-msg">⚠ <?= htmlspecialchars($errors['nik']) ?></div>
            <?php endif; ?>
          </div>

          <!-- Field 3: No HP -->
          <div class="field<?= hasErr('no_hp', $errors) ?>">
            <label for="no_hp">Nomor HP / WhatsApp <span class="req">*</span></label>
            <input type="tel" id="no_hp" name="no_hp"
                   placeholder="08xxxxxxxxxx"
                   value="<?= htmlspecialchars($data['no_hp'] ?? '') ?>">
            <?php if (isset($errors['no_hp'])): ?>
              <div class="field-msg">⚠ <?= htmlspecialchars($errors['no_hp']) ?></div>
            <?php endif; ?>
          </div>

          <!-- Field 4: Tempat Lahir -->
          <div class="field">
            <label for="tempat_lahir">Tempat Lahir</label>
            <input type="text" id="tempat_lahir" name="tempat_lahir"
                   placeholder="Contoh: Surabaya"
                   value="<?= htmlspecialchars($data['tempat_lahir'] ?? '') ?>">
          </div>

          <!-- Field 5: Tanggal Lahir -->
          <div class="field<?= hasErr('tanggal_lahir', $errors) ?>">
            <label for="tanggal_lahir">Tanggal Lahir <span class="req">*</span></label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                   value="<?= htmlspecialchars($data['tanggal_lahir'] ?? '') ?>">
            <?php if (isset($errors['tanggal_lahir'])): ?>
              <div class="field-msg">⚠ <?= htmlspecialchars($errors['tanggal_lahir']) ?></div>
            <?php endif; ?>
          </div>

        </div><!-- /grid-2 -->

        <hr class="divider">

        <!-- Field 6: Jenis Kelamin -->
        <div class="field<?= hasErr('jenis_kelamin', $errors) ?>">
          <label>Jenis Kelamin <span class="req">*</span></label>
          <div class="radio-group<?= isset($errors['jenis_kelamin']) ? ' has-error' : '' ?>">
            <label class="radio-label">
              <input type="radio" name="jenis_kelamin" value="Laki-laki"
                     <?= (($data['jenis_kelamin'] ?? '') === 'Laki-laki') ? 'checked' : '' ?>>
              <span>♂ Laki-laki</span>
            </label>
            <label class="radio-label">
              <input type="radio" name="jenis_kelamin" value="Perempuan"
                     <?= (($data['jenis_kelamin'] ?? '') === 'Perempuan') ? 'checked' : '' ?>>
              <span>♀ Perempuan</span>
            </label>
          </div>
          <?php if (isset($errors['jenis_kelamin'])): ?>
            <div class="field-msg">⚠ <?= htmlspecialchars($errors['jenis_kelamin']) ?></div>
          <?php endif; ?>
        </div>

        <hr class="divider">

        <div class="grid-2">

          <!-- Field 7: Agama -->
          <div class="field">
            <label for="agama">Agama</label>
            <select id="agama" name="agama">
              <option value="">— Pilih Agama —</option>
              <?php foreach (['Islam','Kristen Protestan','Katolik','Hindu','Buddha','Konghucu'] as $a): ?>
                <option value="<?= $a ?>" <?= (($data['agama'] ?? '') === $a) ? 'selected' : '' ?>><?= $a ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Field 8: Kota Asal (38 Kab & Kota Jawa Timur) -->
          <div class="field<?= hasErr('kota_asal', $errors) ?>">
            <label for="kota_asal">Kota / Kabupaten Asal <span class="req">*</span></label>
            <select id="kota_asal" name="kota_asal">
              <option value="">— Pilih Kota di Jawa Timur —</option>
              <?php foreach ($kota_jatim as $k): ?>
                <option value="<?= $k ?>" <?= (($data['kota_asal'] ?? '') === $k) ? 'selected' : '' ?>><?= $k ?></option>
              <?php endforeach; ?>
            </select>
            <span class="field-hint">38 Kabupaten &amp; Kota Jawa Timur</span>
            <?php if (isset($errors['kota_asal'])): ?>
              <div class="field-msg">⚠ <?= htmlspecialchars($errors['kota_asal']) ?></div>
            <?php endif; ?>
          </div>

          <!-- Field 9: Alamat -->
          <div class="field col-span-2">
            <label for="alamat">Alamat Lengkap</label>
            <textarea id="alamat" name="alamat"
                      placeholder="Jl. ... No. ..., RT/RW, Kelurahan, Kecamatan"><?= htmlspecialchars($data['alamat'] ?? '') ?></textarea>
          </div>

          <!-- Field 10: Email -->
          <div class="field col-span-2<?= hasErr('email', $errors) ?>">
            <label for="email">Alamat Email <span class="req">*</span></label>
            <input type="email" id="email" name="email"
                   placeholder="nama@email.com"
                   value="<?= htmlspecialchars($data['email'] ?? '') ?>">
            <?php if (isset($errors['email'])): ?>
              <div class="field-msg">⚠ <?= htmlspecialchars($errors['email']) ?></div>
            <?php endif; ?>
          </div>

        </div><!-- /grid-2 -->
      </div><!-- /card-body -->
    </div><!-- /card 1 -->

    <!-- KARTU 2: RIWAYAT PENDIDIKAN -->
    <div class="card">
      <div class="card-header">
        <div class="card-header-icon">📚</div>
        <div>
          <h2>Riwayat Pendidikan</h2>
          <span>Data SMA / SMK / MA terakhir yang ditempuh</span>
        </div>
      </div>
      <div class="card-body">
        <div class="grid-2">

          <!-- Field 11: Nama Sekolah -->
          <div class="field col-span-2">
            <label for="asal_sekolah">Nama Sekolah Asal</label>
            <input type="text" id="asal_sekolah" name="asal_sekolah"
                   placeholder="Contoh: SMA Negeri 5 Surabaya"
                   value="<?= htmlspecialchars($data['asal_sekolah'] ?? '') ?>">
          </div>

          <!-- Field 12: Tahun Lulus -->
          <div class="field">
            <label for="tahun_lulus">Tahun Lulus</label>
            <input type="number" id="tahun_lulus" name="tahun_lulus"
                   min="2000" max="2030" placeholder="2024"
                   value="<?= htmlspecialchars($data['tahun_lulus'] ?? '') ?>">
          </div>

        </div>
      </div>
    </div><!-- /card 2 -->

    <!-- KARTU 3: PROGRAM STUDI -->
    <div class="card">
      <div class="card-header">
        <div class="card-header-icon">🎯</div>
        <div>
          <h2>Pilihan Program Studi &amp; Jalur Masuk</h2>
          <span>Tentukan minat studi dan jalur seleksi yang diikuti</span>
        </div>
      </div>
      <div class="card-body">
        <div class="grid-2">

          <!-- Field 13: Program Studi -->
          <div class="field<?= hasErr('program_studi', $errors) ?>">
            <label for="program_studi">Program Studi <span class="req">*</span></label>
            <select id="program_studi" name="program_studi">
              <option value="">— Pilih Program Studi —</option>
              <?php foreach ($prodi_list as $p): ?>
                <option value="<?= $p ?>" <?= (($data['program_studi'] ?? '') === $p) ? 'selected' : '' ?>><?= $p ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['program_studi'])): ?>
              <div class="field-msg">⚠ <?= htmlspecialchars($errors['program_studi']) ?></div>
            <?php endif; ?>
          </div>

          <!-- Field 14: Jalur Masuk -->
          <div class="field<?= hasErr('jalur_masuk', $errors) ?>">
            <label for="jalur_masuk">Jalur Masuk <span class="req">*</span></label>
            <select id="jalur_masuk" name="jalur_masuk">
              <option value="">— Pilih Jalur —</option>
              <?php foreach (['Seleksi Tulis (SBMPTN)','Seleksi Prestasi (SNMPTN)','Jalur Mandiri','Beasiswa Unggulan','Pindahan / Transfer'] as $j): ?>
                <option value="<?= $j ?>" <?= (($data['jalur_masuk'] ?? '') === $j) ? 'selected' : '' ?>><?= $j ?></option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['jalur_masuk'])): ?>
              <div class="field-msg">⚠ <?= htmlspecialchars($errors['jalur_masuk']) ?></div>
            <?php endif; ?>
          </div>

        </div>
      </div>
    </div><!-- /card 3 -->

    <!-- SUBMIT -->
    <div class="submit-section">
      <div class="submit-note">
        <strong>Pernyataan Pendaftar:</strong><br>
        Dengan menekan tombol di bawah, saya menyatakan bahwa semua data yang diisi adalah
        <strong>benar dan dapat dipertanggungjawabkan</strong>.
      </div>
      <button type="submit" class="btn-submit">Kirim Pendaftaran &rarr;</button>
    </div>

  </form>

</main>

<footer class="site-footer">
  &copy; 2025 <strong>Universitas Nusantara</strong> &mdash; Formulir Pendaftaran Mahasiswa Baru.<br>
  Dibuat untuk keperluan Tugas Praktikum Pemrograman Web.
</footer>

</body>
</html>