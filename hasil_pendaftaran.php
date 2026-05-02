<?php
session_start();
include 'koneksi.php';

// Jika diakses langsung tanpa data POST, redirect ke form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pendaftaran.php');
    exit;
}

// Ambil data mentah (belum di-escape) untuk validasi
$raw = [
    'nama_lengkap'  => trim($_POST['nama_lengkap']  ?? ''),
    'nik'           => trim($_POST['nik']            ?? ''),
    'tempat_lahir'  => trim($_POST['tempat_lahir']   ?? ''),
    'tanggal_lahir' => trim($_POST['tanggal_lahir']  ?? ''),
    'jenis_kelamin' => trim($_POST['jenis_kelamin']  ?? ''),
    'agama'         => trim($_POST['agama']          ?? ''),
    'kota_asal'     => trim($_POST['kota_asal']      ?? ''),
    'alamat'        => trim($_POST['alamat']         ?? ''),
    'no_hp'         => trim($_POST['no_hp']          ?? ''),
    'email'         => trim($_POST['email']          ?? ''),
    'asal_sekolah'  => trim($_POST['asal_sekolah']   ?? ''),
    'tahun_lulus'   => trim($_POST['tahun_lulus']    ?? ''),
    'program_studi' => trim($_POST['program_studi']  ?? ''),
    'jalur_masuk'   => trim($_POST['jalur_masuk']    ?? ''),
];


$errors = [];

if (empty($raw['nama_lengkap']))
    $errors['nama_lengkap'] = 'Nama lengkap wajib diisi.';

if (empty($raw['nik']))
    $errors['nik'] = 'NIK wajib diisi.';
elseif (!ctype_digit($raw['nik']) || strlen($raw['nik']) !== 16)
    $errors['nik'] = 'NIK harus tepat 16 digit angka.';

if (empty($raw['tanggal_lahir']))
    $errors['tanggal_lahir'] = 'Tanggal lahir wajib diisi.';

if (empty($raw['jenis_kelamin']))
    $errors['jenis_kelamin'] = 'Jenis kelamin wajib dipilih.';

if (empty($raw['no_hp']))
    $errors['no_hp'] = 'Nomor HP wajib diisi.';
elseif (!preg_match('/^[0-9+\-\s]{8,15}$/', $raw['no_hp']))
    $errors['no_hp'] = 'Format nomor HP tidak valid.';

if (empty($raw['email']))
    $errors['email'] = 'Email wajib diisi.';
elseif (!filter_var($raw['email'], FILTER_VALIDATE_EMAIL))
    $errors['email'] = 'Format email tidak valid.';

if (empty($raw['kota_asal']))
    $errors['kota_asal'] = 'Kota asal wajib dipilih.';

if (empty($raw['program_studi']))
    $errors['program_studi'] = 'Program studi wajib dipilih.';

if (empty($raw['jalur_masuk']))
    $errors['jalur_masuk'] = 'Jalur masuk wajib dipilih.';


if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_old']    = $raw;
    header('Location: pendaftaran.php');
    exit;
}


function clean($val) {
    return htmlspecialchars(trim($val ?? ''), ENT_QUOTES, 'UTF-8');
}

$nama_lengkap   = clean($raw['nama_lengkap']);
$nik            = clean($raw['nik']);
$tempat_lahir   = clean($raw['tempat_lahir']);
$tanggal_lahir  = clean($raw['tanggal_lahir']);
$jenis_kelamin  = clean($raw['jenis_kelamin']);
$agama          = clean($raw['agama']);
$kota_asal      = clean($raw['kota_asal']);
$alamat         = clean($raw['alamat']);
$no_hp          = clean($raw['no_hp']);
$email          = clean($raw['email']);
$asal_sekolah   = clean($raw['asal_sekolah']);
$tahun_lulus    = clean($raw['tahun_lulus']);
$program_studi  = clean($raw['program_studi']);
$jalur_masuk    = clean($raw['jalur_masuk']);

// Format tanggal lahir ke format Indonesia
$tgl_obj     = !empty($tanggal_lahir) ? DateTime::createFromFormat('Y-m-d', $tanggal_lahir) : null;
$tgl_indo    = $tgl_obj ? $tgl_obj->format('d') . ' ' . [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
    7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
][(int)$tgl_obj->format('n')] . ' ' . $tgl_obj->format('Y') : '-';

// Hitung umur
$umur = $tgl_obj ? (int)(new DateTime())->diff($tgl_obj)->y . ' tahun' : '-';

// Nomor pendaftaran unik (simulasi)
$no_daftar = 'UN-' . date('Y') . '-' . strtoupper(substr(md5($nik . $email), 0, 8));
$query = "INSERT INTO pendaftaran (
nama_lengkap,
nik,
tempat_lahir,
tanggal_lahir,
jenis_kelamin,
agama,
kota_asal,
alamat,
no_hp,
email,
asal_sekolah,
tahun_lulus,
program_studi,
jalur_masuk,
nomor_pendaftaran
) VALUES (

'$nama_lengkap',
'$nik',
'$tempat_lahir',
'$tanggal_lahir',
'$jenis_kelamin',
'$agama',
'$kota_asal',
'$alamat',
'$no_hp',
'$email',
'$asal_sekolah',
'$tahun_lulus',
'$program_studi',
'$jalur_masuk',
'$no_daftar'
)";

mysqli_query($conn,$query);

// Timestamp
$waktu_daftar = date('d/m/Y H:i:s');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bukti Pendaftaran — <?= $nama_lengkap ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --navy:  #0b1f3a;
    --gold:  #c9972c;
    --gold2: #e8b84b;
    --cream: #fdf8f0;
    --white: #ffffff;
    --gray:  #6b7280;
    --light: #f3f4f6;
    --border:#d4c5a9;
    --green: #166534;
    --green-bg: #f0fdf4;
    --green-border: #86efac;
    --shadow: 0 8px 40px rgba(11,31,58,.13);
  }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--cream);
    min-height: 100vh;
    color: var(--navy);
  }

  /* ===== HEADER ===== */
  .site-header {
    background: var(--navy);
    position: relative; overflow: hidden;
  }
  .site-header::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c9972c' fill-opacity='0.07'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }
  .header-inner {
    position: relative; z-index: 1;
    max-width: 860px; margin: 0 auto;
    padding: 40px 32px 36px;
    display: flex; align-items: center; gap: 24px;
  }
  .logo-circle {
    width: 72px; height: 72px; flex-shrink: 0; border-radius: 50%;
    border: 3px solid var(--gold);
    display: flex; align-items: center; justify-content: center;
    background: rgba(201,151,44,.12);
  }
  .logo-circle svg { width: 38px; height: 38px; }
  .header-text h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.3rem, 3vw, 1.8rem);
    color: var(--white); line-height: 1.2;
  }
  .header-text p { color: var(--gold2); font-size: .82rem; font-weight: 500; letter-spacing: .07em; text-transform: uppercase; margin-top: 4px; }
  .gold-bar { height: 4px; background: linear-gradient(90deg, var(--gold), var(--gold2), var(--gold)); }

  /* ===== MAIN ===== */
  .page-body { max-width: 860px; margin: 0 auto; padding: 36px 32px 80px; }

  /* ===== SUCCESS BANNER ===== */
  .success-banner {
    background: var(--green-bg);
    border: 1.5px solid var(--green-border);
    border-radius: 16px;
    padding: 20px 26px;
    display: flex; align-items: center; gap: 16px;
    margin-bottom: 28px;
    animation: slideDown .5s ease;
  }
  .success-banner .icon { font-size: 2rem; flex-shrink: 0; }
  .success-banner h2 { font-family: 'Playfair Display', serif; font-size: 1.15rem; color: var(--green); }
  .success-banner p { font-size: .84rem; color: #166534cc; margin-top: 3px; }

  @keyframes slideDown {
    from { opacity: 0; transform: translateY(-12px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ===== NOMOR PENDAFTARAN ===== */
  .reg-number-card {
    background: linear-gradient(135deg, var(--navy) 0%, #1a3a5c 100%);
    border-radius: 18px;
    padding: 28px 32px;
    display: flex; align-items: center; justify-content: space-between; gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 24px;
    position: relative; overflow: hidden;
    box-shadow: var(--shadow);
    animation: fadeUp .5s ease;
  }
  .reg-number-card::after {
    content: '🎓';
    position: absolute; right: 28px; top: 50%;
    transform: translateY(-50%);
    font-size: 5rem; opacity: .06;
    pointer-events: none;
  }
  .reg-label { font-size: .75rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; color: var(--gold2); }
  .reg-number {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    color: var(--white);
    letter-spacing: .06em;
    margin-top: 6px;
  }
  .reg-meta { text-align: right; }
  .reg-meta .reg-label { text-align: right; }
  .reg-status {
    display: inline-block; margin-top: 8px;
    padding: 6px 18px;
    background: rgba(201,151,44,.2);
    border: 1.5px solid var(--gold);
    border-radius: 20px;
    color: var(--gold2);
    font-size: .82rem; font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
  }

  /* ===== CARDS ===== */
  .card {
    background: var(--white);
    border-radius: 18px;
    border: 1px solid rgba(212,197,169,.4);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 20px;
    animation: fadeUp .5s ease both;
  }
  .card:nth-child(3) { animation-delay: .08s; }
  .card:nth-child(4) { animation-delay: .16s; }
  .card:nth-child(5) { animation-delay: .24s; }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .card-header {
    background: linear-gradient(135deg, #f8f4ec 0%, #fdf8f0 100%);
    border-bottom: 1px solid var(--border);
    padding: 16px 24px;
    display: flex; align-items: center; gap: 12px;
  }
  .card-header-icon {
    width: 36px; height: 36px; border-radius: 9px;
    background: var(--navy);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
  }
  .card-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; color: var(--navy); font-weight: 700;
  }
  .card-header span { font-size: .73rem; color: var(--gray); display: block; margin-top: 1px; }

  /* ===== DATA TABLE ===== */
  .data-table { width: 100%; border-collapse: collapse; }
  .data-table tr { border-bottom: 1px solid #f0ebe0; }
  .data-table tr:last-child { border-bottom: none; }
  .data-table tr:hover { background: #fdf9f3; }
  .data-table td {
    padding: 14px 24px;
    font-size: .88rem;
    vertical-align: top;
    line-height: 1.5;
  }
  .data-table td.lbl {
    color: var(--gray);
    font-weight: 600;
    font-size: .78rem;
    letter-spacing: .03em;
    text-transform: uppercase;
    width: 38%;
    white-space: nowrap;
  }
  .data-table td.val {
    color: var(--navy);
    font-weight: 500;
  }
  .data-table td.val .empty { color: #b0b8c4; font-style: italic; font-weight: 400; }

  /* Badge / pill */
  .badge {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: .8rem;
    font-weight: 700;
  }
  .badge-gold   { background: rgba(201,151,44,.15); color: #7a5a10; border: 1px solid rgba(201,151,44,.4); }
  .badge-navy   { background: rgba(11,31,58,.08);   color: var(--navy); border: 1px solid rgba(11,31,58,.2); }
  .badge-green  { background: #f0fdf4; color: var(--green); border: 1px solid #86efac; }

  /* ===== GRID DATA (2 kolom) ===== */
  .two-col-cards {
    display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
  }
  @media (max-width: 620px) { .two-col-cards { grid-template-columns: 1fr; } }

  /* ===== ACTION BUTTONS ===== */
  .action-bar {
    display: flex; gap: 12px; flex-wrap: wrap; justify-content: center;
    margin-top: 32px;
    animation: fadeUp .5s .32s ease both;
  }
  .btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 13px 28px;
    border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: .9rem; font-weight: 700;
    cursor: pointer; text-decoration: none;
    transition: transform .2s, box-shadow .2s, filter .2s;
    border: none;
  }
  .btn:hover { transform: translateY(-2px); }
  .btn-primary {
    background: linear-gradient(135deg, var(--gold), var(--gold2));
    color: var(--navy);
    box-shadow: 0 4px 18px rgba(201,151,44,.4);
  }
  .btn-primary:hover { box-shadow: 0 8px 28px rgba(201,151,44,.5); filter: brightness(1.05); }
  .btn-secondary {
    background: var(--navy); color: var(--white);
    box-shadow: 0 4px 18px rgba(11,31,58,.2);
  }
  .btn-secondary:hover { background: #1a3a5c; }
  .btn-outline {
    background: var(--white); color: var(--navy);
    border: 1.5px solid var(--border);
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
  }
  .btn-outline:hover { border-color: var(--gold); }

  /* ===== PRINT STYLES ===== */
  @media print {
    .action-bar, .site-footer { display: none; }
    body { background: white; }
    .card, .reg-number-card { box-shadow: none; border: 1px solid #ccc; }
    .success-banner { display: none; }
  }

  /* ===== FOOTER ===== */
  .site-footer {
    text-align: center; padding: 24px;
    font-size: .78rem; color: var(--gray);
    border-top: 1px solid var(--border);
    margin-top: 20px;
  }
  .site-footer strong { color: var(--navy); }

  /* ===== WATERMARK PRINT ===== */
  .print-watermark {
    display: none;
    text-align: center; padding: 14px;
    font-size: .75rem; color: #999;
    border-top: 1px dashed #ccc; margin-top: 12px;
  }
  @media print { .print-watermark { display: block; } }
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
      <p>Bukti Pendaftaran Mahasiswa Baru &mdash; Tahun Akademik 2025/2026</p>
    </div>
  </div>
</header>
<div class="gold-bar"></div>

<main class="page-body">

  <!-- SUCCESS BANNER -->
  <div class="success-banner">
    <div class="icon">✅</div>
    <div>
      <h2>Pendaftaran Berhasil Diterima!</h2>
      <p>Data Anda telah tercatat. Simpan nomor pendaftaran di bawah sebagai bukti resmi. Tim kami akan menghubungi dalam <strong>3–5 hari kerja</strong>.</p>
    </div>
  </div>

  <!-- NOMOR PENDAFTARAN -->
  <div class="reg-number-card">
    <div>
      <div class="reg-label">Nomor Pendaftaran</div>
      <div class="reg-number"><?= $no_daftar ?></div>
    </div>
    <div class="reg-meta">
      <div class="reg-label">Waktu Pendaftaran</div>
      <div style="color:rgba(255,255,255,.7); font-size:.85rem; margin-top:6px;"><?= $waktu_daftar ?></div>
      <div class="reg-status">Menunggu Verifikasi</div>
    </div>
  </div>

  <!-- DATA DIRI -->
  <div class="card">
    <div class="card-header">
      <div class="card-header-icon">👤</div>
      <div>
        <h3>Data Diri Pendaftar</h3>
        <span>Informasi pribadi calon mahasiswa</span>
      </div>
    </div>
    <table class="data-table">
      <tr>
        <td class="lbl">Nama Lengkap</td>
        <td class="val"><strong><?= $nama_lengkap ?: '<span class="empty">—</span>' ?></strong></td>
      </tr>
      <tr>
        <td class="lbl">NIK</td>
        <td class="val"><?= $nik ? substr($nik,0,4).'–'.substr($nik,4,4).'–'.substr($nik,8,4).'–'.substr($nik,12) : '<span class="empty">—</span>' ?></td>
      </tr>
      <tr>
        <td class="lbl">Tempat, Tanggal Lahir</td>
        <td class="val">
          <?= ($tempat_lahir ? $tempat_lahir . ', ' : '') . ($tgl_indo !== '-' ? $tgl_indo : '<span class="empty">—</span>') ?>
          <?php if ($umur !== '-'): ?>
            <span style="color:var(--gray); font-size:.82rem;"> &nbsp;(<?= $umur ?>)</span>
          <?php endif; ?>
        </td>
      </tr>
      <tr>
        <td class="lbl">Jenis Kelamin</td>
        <td class="val">
          <?php if ($jenis_kelamin): ?>
            <span class="badge badge-navy"><?= ($jenis_kelamin === 'Laki-laki' ? '♂' : '♀') . ' ' . $jenis_kelamin ?></span>
          <?php else: ?><span class="empty">—</span><?php endif; ?>
        </td>
      </tr>
      <tr>
        <td class="lbl">Agama</td>
        <td class="val"><?= $agama ?: '<span class="empty">—</span>' ?></td>
      </tr>
      <tr>
        <td class="lbl">No. HP / WhatsApp</td>
        <td class="val"><?= $no_hp ?: '<span class="empty">—</span>' ?></td>
      </tr>
      <tr>
        <td class="lbl">Alamat Email</td>
        <td class="val"><?= $email ?: '<span class="empty">—</span>' ?></td>
      </tr>
      <tr>
        <td class="lbl">Kota / Kabupaten Asal</td>
        <td class="val">
          <?php if ($kota_asal): ?>
            <span class="badge badge-gold">📍 <?= $kota_asal ?></span>
          <?php else: ?><span class="empty">—</span><?php endif; ?>
        </td>
      </tr>
      <tr>
        <td class="lbl">Alamat Lengkap</td>
        <td class="val" style="white-space:pre-line;"><?= $alamat ?: '<span class="empty">—</span>' ?></td>
      </tr>
    </table>
  </div>

  <!-- 2-COLUMN CARDS: PENDIDIKAN + PROGRAM -->
  <div class="two-col-cards">

    <!-- RIWAYAT PENDIDIKAN -->
    <div class="card" style="margin-bottom:0;">
      <div class="card-header">
        <div class="card-header-icon">📚</div>
        <div>
          <h3>Riwayat Pendidikan</h3>
          <span>Data sekolah asal</span>
        </div>
      </div>
      <table class="data-table">
        <tr>
          <td class="lbl">Sekolah Asal</td>
          <td class="val"><?= $asal_sekolah ?: '<span class="empty">—</span>' ?></td>
        </tr>
        <tr>
          <td class="lbl">Tahun Lulus</td>
          <td class="val"><?= $tahun_lulus ?: '<span class="empty">—</span>' ?></td>
        </tr>
      </table>
    </div>

    <!-- PROGRAM STUDI -->
    <div class="card" style="margin-bottom:0;">
      <div class="card-header">
        <div class="card-header-icon">🎯</div>
        <div>
          <h3>Pilihan Program</h3>
          <span>Studi & jalur masuk</span>
        </div>
      </div>
      <table class="data-table">
        <tr>
          <td class="lbl">Program Studi</td>
          <td class="val">
            <?php if ($program_studi): ?>
              <strong><?= $program_studi ?></strong>
            <?php else: ?><span class="empty">—</span><?php endif; ?>
          </td>
        </tr>
        <tr>
          <td class="lbl">Jalur Masuk</td>
          <td class="val">
            <?php if ($jalur_masuk): ?>
              <span class="badge badge-green">✓ <?= $jalur_masuk ?></span>
            <?php else: ?><span class="empty">—</span><?php endif; ?>
          </td>
        </tr>
      </table>
    </div>

  </div><!-- end two-col -->

  <!-- ACTION BUTTONS -->
  <div class="action-bar">
    <button class="btn btn-primary" onclick="window.print()">
      🖨️ Cetak / Simpan PDF
    </button>
    <a href="pendaftaran.php" class="btn btn-secondary">
      ← Daftar Lagi
    </a>
    <button class="btn btn-outline" onclick="
      navigator.clipboard.writeText('<?= $no_daftar ?>');
      this.textContent = '✓ Disalin!';
      setTimeout(()=>this.textContent='📋 Salin Nomor Daftar', 2000);
    ">
      📋 Salin Nomor Daftar
    </button>
  </div>

  <div class="print-watermark">
    Dicetak dari Sistem Pendaftaran Universitas Nusantara &bull; <?= $waktu_daftar ?>
  </div>

</main>

<footer class="site-footer">
  &copy; 2025 <strong>Universitas Nusantara</strong> &mdash; Sistem Pendaftaran Mahasiswa Baru.<br>
  Dibuat untuk keperluan Tugas Praktikum Pemrograman Web.
</footer>

</body>
</html>