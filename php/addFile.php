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

<header class="topbar">
  <div class="container topbar__inner">
    <a class="brand" href="home.php">
      <span class="brand__mark"></span>
      <span class="brand__text">Portfolio</span>
    </a>

    <nav class="topbar__nav">
      <a class="navlink" href="home.php">Home</a>
      <a class="navlink" href="portfolio.php">Portfolio</a>
    </nav>
  </div>
</header>

<main class="container container--wide">

  <!-- Page header -->
  <section class="pagehead">
    <div class="pagehead__left">
      <h1 class="pagehead__title">Upload File</h1>
      <p class="pagehead__subtitle">
        Add a file to a category. Optionally block access for a specific username.
      </p>
    </div>
    <div class="pagehead__right">
      <a class="btn btn--ghost" href="portfolio.php">← Back</a>
    </div>
  </section>

  <section class="grid grid--upload">

    <!-- LEFT: Upload form -->
    <article class="card card--form">
      <header class="card__head">
        <h2 class="card__title">File Details</h2>
        <span class="badge badge--info">Admin</span>
      </header>

      <form class="form" action="#" method="post" enctype="multipart/form-data">

        <div class="field">
          <label class="label" for="title">Title</label>
          <input class="input" id="title" name="title" type="text" placeholder="e.g. Week 3 Reflection" required />
        </div>

        <div class="field">
          <label class="label" for="desc">Description</label>
          <textarea class="textarea" id="desc" name="description" rows="4"
            placeholder="Briefly describe what this file is, which project it belongs to, and what it demonstrates."></textarea>
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

          <input
            class="input"
            id="blocked_user"
            name="blocked_user"
            type="text"
            placeholder="Enter username to block (e.g. john123)"
            autocomplete="off"
          />

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

    <!-- RIGHT: Upload rules -->
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
          <div class="rule__k">3) Short description</div>
          <div class="rule__v">Write what the file is, what you did, and what you learned.</div>
        </div>

        <div class="rule">
          <div class="rule__k">4) Privacy</div>
          <div class="rule__v">Do not upload passwords, phone numbers, addresses, or other personal data.</div>
        </div>

        <div class="rule">
          <div class="rule__k">5) Access control</div>
          <div class="rule__v">If you block a username, enforce it in PHP when serving the file.</div>
        </div>
      </div>

      <div class="note">
        <strong>Reminder:</strong> Validate file type and size on the server (PHP) before saving.
      </div>
    </aside>

  </section>

</main>

<script>
  // show selected file name
  const file = document.getElementById('file');
  const drop = document.querySelector('.drop__text');

  file?.addEventListener('change', () => {
    if (file.files && file.files[0]) {
      drop.innerHTML = `<strong>${file.files[0].name}</strong>`;
    }
  });
</script>

</body>
</html>