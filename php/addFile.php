<?php
declare(strict_types=1);
session_start();

/* =========================
   CONFIG
   ========================= */

// Если у тебя Docker (в дампе часто host=mysql), поставь 'mysql'
// Если без Docker, оставь 'localhost'
$DB_HOST = 'mysql';
$DB_NAME = 'Portfolio';   // у тебя БД называется Portfolio (с большой буквы на скринах)
$DB_USER = 'root';
$DB_PASS = 'qwerty';

$MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB
$ALLOWED_MIME  = [
  'application/pdf' => 'pdf',
  'image/jpeg'      => 'jpg',
  'image/png'       => 'png',
  'application/zip' => 'zip',
];

// uploads folder (у тебя есть папка uploads)
$UPLOAD_DIR = __DIR__ . '/../uploads';
if (!is_dir($UPLOAD_DIR)) {
  @mkdir($UPLOAD_DIR, 0755, true);
}

/* =========================
   HELPERS
   ========================= */

function e(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function clean_text(string $s, int $maxLen): string {
  $s = trim($s);
  $s = preg_replace('/\s+/', ' ', $s) ?? '';
  if (mb_strlen($s) > $maxLen) $s = mb_substr($s, 0, $maxLen);
  return $s;
}

function is_valid_username(string $u): bool {
  return (bool)preg_match('/^[A-Za-z0-9._-]{3,32}$/', $u);
}

function pdo_conn(): PDO {
  global $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS;
  $dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4";
  return new PDO($dsn, $DB_USER, $DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
}

/* =========================
   CSRF
   ========================= */

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$success = false;

// чтобы значения оставались в форме после ошибки
$old = [
  'title' => '',
  'description' => '',
  'blocked_user' => '',
  'year' => '1',
  'period' => '1',
  'category' => 'General',
];

/* =========================
   HANDLE POST
   ========================= */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $old['title'] = $_POST['title'] ?? '';
  $old['description'] = $_POST['description'] ?? '';
  $old['blocked_user'] = $_POST['blocked_user'] ?? '';
  $old['year'] = $_POST['year'] ?? '1';
  $old['period'] = $_POST['period'] ?? '1';
  $old['category'] = $_POST['category'] ?? 'General';

  // CSRF check
  $token = $_POST['csrf_token'] ?? '';
  if (!hash_equals($_SESSION['csrf_token'], $token)) {
    $errors[] = 'Security error (CSRF). Please reload the page and try again.';
  }

  // Basic fields
  $title = clean_text($_POST['title'] ?? '', 120);
  $description = clean_text($_POST['description'] ?? '', 255);
  $blocked_user = clean_text($_POST['blocked_user'] ?? '', 32);

  $year = (int)($_POST['year'] ?? 0);
  $period = (int)($_POST['period'] ?? 0);
  $category = clean_text($_POST['category'] ?? '', 255);

  if ($title === '' || mb_strlen($title) < 3) {
    $errors[] = 'Title is required (min 3 characters).';
  }

  if ($year < 1 || $year > 4) {
    $errors[] = 'Year must be between 1 and 4.';
  }

  if ($period < 1 || $period > 3) {
    $errors[] = 'Period must be between 1 and 3.';
  }

  if ($category === '' || mb_strlen($category) < 2) {
    $errors[] = 'Category is required.';
  }

  if ($blocked_user !== '' && !is_valid_username($blocked_user)) {
    $errors[] = 'Blocked username is invalid (use letters, numbers, . _ - ; 3–32 chars).';
  }

  // File validation
  if (!isset($_FILES['file'])) {
    $errors[] = 'File is required.';
  } else {
    $f = $_FILES['file'];

    if ($f['error'] !== UPLOAD_ERR_OK) {
      $errors[] = 'Upload failed. Error code: ' . $f['error'];
    } else {
      if ($f['size'] <= 0 || $f['size'] > $MAX_FILE_SIZE) {
        $errors[] = 'File size must be between 1 byte and 10 MB.';
      }

      // Detect MIME safely
      $tmp = $f['tmp_name'];
      $fi = new finfo(FILEINFO_MIME_TYPE);
      $mime = $fi->file($tmp) ?: '';

      if (!array_key_exists($mime, $ALLOWED_MIME)) {
        $errors[] = 'File type not allowed. Allowed: PDF, JPG, PNG, ZIP.';
      }
    }
  }

  // Check blocked user exists in Users (if provided)
  if (!$errors && $blocked_user !== '') {
    try {
      $pdo = pdo_conn();
      $st = $pdo->prepare("SELECT user_id FROM Users WHERE user_name = :u LIMIT 1");
      $st->execute([':u' => $blocked_user]);
      if (!$st->fetch()) {
        $errors[] = "User '{$blocked_user}' not found in database.";
      }
    } catch (Throwable $ex) {
      $errors[] = "Database connection error (Users check).";
    }
  }

  // If ok: move file + save to DB
  if (!$errors) {
    $f = $_FILES['file'];
    $tmp = $f['tmp_name'];

    $fi = new finfo(FILEINFO_MIME_TYPE);
    $mime = $fi->file($tmp) ?: '';
    $ext = $ALLOWED_MIME[$mime];

    // Safe filename
    $random = bin2hex(random_bytes(16));
    $safeName = $random . '.' . $ext;
    $destPath = $UPLOAD_DIR . '/' . $safeName;

    if (!move_uploaded_file($tmp, $destPath)) {
      $errors[] = 'Could not save the uploaded file.';
    } else {

      try {
        $pdo = pdo_conn();
        $pdo->beginTransaction();

        // 1) Insert into Files
        $stmt = $pdo->prepare("
          INSERT INTO Files
            (file_title, file_year, file_period, file_description, stored_name, original_name, mime_type, file_size, categories)
          VALUES
            (:title, :year, :period, :descr, :stored, :orig, :mime, :size, :cat)
        ");

        $stmt->execute([
          ':title'  => $title,
          ':year'   => $year,
          ':period' => $period,
          ':descr'  => ($description !== '' ? $description : '—'),
          ':stored' => $safeName,
          ':orig'   => $f['name'],
          ':mime'   => $mime,
          ':size'   => (int)$f['size'],
          ':cat'    => $category,
        ]);

        $fileId = (int)$pdo->lastInsertId();

        // 2) If blocked_user provided, insert into Not_Available_Users
        if ($blocked_user !== '') {
          $blk = $pdo->prepare("
            INSERT INTO Not_Available_Users (user_name, file_id)
            VALUES (:u, :fid)
          ");
          $blk->execute([
            ':u' => $blocked_user,
            ':fid' => $fileId
          ]);
        }

        $pdo->commit();

        $success = true;
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // очистим old values после успеха
        $old = [
          'title' => '',
          'description' => '',
          'blocked_user' => '',
          'year' => '1',
          'period' => '1',
          'category' => 'General',
        ];

      } catch (Throwable $ex) {
        if (isset($pdo)) $pdo->rollBack();
        @unlink($destPath);
        $errors[] = 'Database error: ' . $ex->getMessage();
      }
    }
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upload File • Portfolio</title>
  <link rel="stylesheet" href="../css/addFile.css" />
  <link rel="stylesheet" href="../css/home.css">
</head>
<body>

<div class="bg" aria-hidden="true"></div>

<header class="topbar">
  <div class="container topbar__inner">
    <a class="brand" href="home.php">
      <span class="brand__mark"></span>
      <span class="brand__text">Portfolio</span>
    </a>

    <nav class="topbar__nav">
      <a class="navlink" href="home.php">Home</a>
      <a class="navlink" href="portfolio.php">Portfolio</a>
      <a class="navlink" href="addFile.php">Upload File</a>
    </nav>
  </div>
</header>

<main class="container container--wide">

  <section class="pagehead">
    <div class="pagehead__left">
      <h1 class="pagehead__title">Upload File</h1>
      <p class="pagehead__subtitle">
        Add a file to a category. Optionally block access for a specific username.
      </p>
    </div>
    <div class="pagehead__right">
      <a class="btn btn--ghost" href="home.php">← Back</a>
    </div>
  </section>

  <section class="grid grid--upload">

    <article class="card card--form">
      <header class="card__head">
        <h2 class="card__title">File Details</h2>
        <span class="badge badge--info">Admin</span>
      </header>

      <?php if (!empty($errors)): ?>
        <div class="note">
          <strong>Fix these errors:</strong>
          <ul style="margin:8px 0 0; padding-left:18px;">
            <?php foreach ($errors as $err): ?>
              <li><?php echo e($err); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php elseif ($success): ?>
        <div class="note" style="border-color: rgba(34,197,94,.35); background: rgba(34,197,94,.10);">
          <strong>Success:</strong> File uploaded and saved to database.
        </div>
      <?php endif; ?>

      <form class="form" action="addFile.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION['csrf_token']); ?>">

        <div class="field">
          <label class="label" for="title">Title</label>
          <input class="input" id="title" name="title" type="text"
                 value="<?php echo e($old['title']); ?>"
                 placeholder="e.g. Week 3 Reflection" required />
        </div>

        <div class="field">
          <label class="label" for="desc">Description</label>
          <textarea class="textarea" id="desc" name="description" rows="4"
            placeholder="Briefly describe what this file is, which project it belongs to, and what it demonstrates."><?php echo e($old['description']); ?></textarea>
        </div>

        <!-- ✅ new selects -->
        <div class="grid grid--mini">
          <div class="field">
            <label class="label" for="year">Year</label>
            <select class="select" id="year" name="year" required>
              <?php for ($y=1; $y<=4; $y++): ?>
                <option value="<?php echo $y; ?>" <?php echo ((string)$y === (string)$old['year']) ? 'selected' : ''; ?>>
                  Year <?php echo $y; ?>
                </option>
              <?php endfor; ?>
            </select>
          </div>

          <div class="field">
            <label class="label" for="period">Period</label>
            <select class="select" id="period" name="period" required>
              <?php for ($p=1; $p<=3; $p++): ?>
                <option value="<?php echo $p; ?>" <?php echo ((string)$p === (string)$old['period']) ? 'selected' : ''; ?>>
                  Period <?php echo $p; ?>
                </option>
              <?php endfor; ?>
            </select>
          </div>
        </div>

        <div class="field">
          <label class="label" for="category">Category</label>
          <select class="select" id="category" name="category" required>
            <?php
              $cats = ['General','Web Development','Project Innovate','Software Quality','Projects','Reflections','Reports'];
              foreach ($cats as $c):
            ?>
              <option value="<?php echo e($c); ?>" <?php echo ($c === $old['category']) ? 'selected' : ''; ?>>
                <?php echo e($c); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label class="label" for="file">Choose File</label>

          <label class="drop" for="file">
            <div class="drop__icon" aria-hidden="true">⬆</div>
            <div class="drop__text"><strong>Click to upload</strong> or drag & drop</div>
            <div class="drop__hint">PDF, images, office files (max size depends on hosting)</div>
          </label>
          <input class="fileinput" id="file" name="file" type="file" required />
        </div>

        <div class="divider"></div>

        <div class="field">
          <div class="field__head">
            <label class="label" for="blocked_user">Block access for username (optional)</label>
            <span class="muted small">The user with this username will NOT be able to view the uploaded file.</span>
          </div>

          <input class="input" id="blocked_user" name="blocked_user" type="text"
                 value="<?php echo e($old['blocked_user']); ?>"
                 placeholder="Enter username to block (e.g. john123)"
                 autocomplete="off" />

          <div class="note note--compact">
            <strong>Note:</strong> This restriction must be checked on the server (PHP) when a user opens the file.
          </div>
        </div>

        <div class="form__actions">
          <button class="btn btn--primary" type="submit">Upload</button>
          <a class="btn btn--secondary" href="portfolio.php">Cancel</a>
        </div>

      </form>
    </article>

    <aside class="card card--rules">
      <header class="rules__head">
        <h2 class="card__title">Upload Rules</h2>
      </header>

      <p class="card__desc">
        Follow these rules to keep your portfolio clean, readable, and compliant with requirements.
      </p>

      <div class="rules__list">
        <div class="rule">
          <div class="rule__k">1) Clear titles</div>
          <div class="rule__v">Use meaningful names (e.g. “Year1 - WebDev - Reflection Week 3”).</div>
        </div>

        <div class="rule">
          <div class="rule__k">2) PDF preferred</div>
          <div class="rule__v">Upload reports/reflections as PDF so they can be previewed in the browser.</div>
        </div>

        <div class="rule">
          <div class="rule__k">3) Fill Year/Period/Category</div>
          <div class="rule__v">These fields help organize files inside your portfolio.</div>
        </div>

        <div class="rule">
          <div class="rule__k">4) Privacy</div>
          <div class="rule__v">Do not upload passwords, phone numbers, addresses, or other personal data.</div>
        </div>

        <div class="rule">
          <div class="rule__k">5) Access control</div>
          <div class="rule__v">Blocked username must be enforced in PHP when serving the file.</div>
        </div>
      </div>

      <div class="note">
        <strong>Reminder:</strong> Validate file type and size on the server (PHP) before saving.
      </div>
    </aside>

  </section>

</main>

<script>
  const file = document.getElementById('file');
  const drop = document.querySelector('.drop__text');

  file?.addEventListener('change', () => {
    if (file.files && file.files[0]) {
      drop.innerHTML = `<strong>${file.files[0].name}</strong>`;
    }
  });
</script>

<footer class="footer">
  <div class="container footer__inner">

    <div class="footer__left">
      <div class="footer__brand">
        <span class="brand__mark"></span>
        <span class="brand__text">Portfolio</span>
      </div>

      <p class="footer__copy">
        © <?php echo date("Y"); ?> My Portfolio. All rights reserved.
      </p>
    </div>

    <div class="footer__socials">

      <a class="social" href="https://github.com/OleksiiKhomiak" target="_blank">
        <span>GitHub</span>
      </a>

      <a class="social" href="https://t.me/dntxry" target="_blank">
        <span>Telegram</span>
      </a>

      <a class="social" href="khomiak2007@gmail.com">
        <span>Email</span>
      </a>

    </div>

  </div>
</footer>

</body>
</html>