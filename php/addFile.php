<!doctype html>
<html lang="ru">
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

<main class="container">

  <!-- Page header -->
  <section class="pagehead">
    <div class="pagehead__left">
      <h1 class="pagehead__title">Upload file</h1>
      <p class="pagehead__subtitle">
        Добавь файл в категорию и выбери, каким посетителям он будет доступен.
      </p>
    </div>
    <div class="pagehead__right">
      <a class="btn btn--ghost" href="portfolio.php">← Back</a>
    </div>
  </section>

  <section class="grid grid--upload">
    <!-- Upload form -->
    <article class="card card--form">
      <header class="card__head">
        <h2 class="card__title">File details</h2>
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
            placeholder="Кратко опиши что это за файл, к какому проекту относится и что демонстрирует."></textarea>
        </div>

        <div class="field">
          <label class="label" for="file">Choose file</label>

          <!-- Drag-drop визуально (работает и как обычный input) -->
          <label class="drop" for="file">
            <div class="drop__icon" aria-hidden="true">⬆</div>
            <div class="drop__text">
              <strong>Click to upload</strong> or drag & drop
            </div>
            <div class="drop__hint">PDF, images, office files (max size зависит от хостинга)</div>
          </label>
          <input class="fileinput" id="file" name="file" type="file" required />
        </div>

        <div class="divider"></div>

        <div class="field">
          <div class="field__head">
            <label class="label">Allowed visitors</label>
            <span class="muted small">Выбери, кто сможет просматривать этот файл</span>
          </div>

          <div class="checks">
            <label class="check">
              <input type="checkbox" name="visitors[]" value="1" />
              <span class="check__box" aria-hidden="true"></span>
              <span class="check__text">
                <span class="check__name">Internship Supervisor</span>
                <span class="check__meta">supervisor@example.com</span>
              </span>
            </label>

            <label class="check">
              <input type="checkbox" name="visitors[]" value="2" />
              <span class="check__box" aria-hidden="true"></span>
              <span class="check__text">
                <span class="check__name">SLB Staff</span>
                <span class="check__meta">slb@example.com</span>
              </span>
            </label>

            <label class="check">
              <input type="checkbox" name="visitors[]" value="3" />
              <span class="check__box" aria-hidden="true"></span>
              <span class="check__text">
                <span class="check__name">Classmate</span>
                <span class="check__meta">classmate@example.com</span>
              </span>
            </label>
          </div>
        </div>

        <div class="form__actions">
          <button class="btn btn--primary" type="submit">Upload</button>
          <a class="btn btn--secondary" href="portfolio.php">Cancel</a>
        </div>
      </form>
    </article>

    <!-- Help / rules -->
    <aside class="card card--side">
      <h2 class="card__title">Tips</h2>
      <p class="card__desc">
        Для соблюдения требований лучше загружать отчёты и рефлексии в PDF — так файл можно просматривать прямо в браузере.
      </p>

      <div class="side__list">
        <div class="side__item">
          <div class="side__k">Access</div>
          <div class="side__v">Доступ задаётся для каждого файла и каждого visitor отдельно.</div>
        </div>
        <div class="side__item">
          <div class="side__k">Privacy</div>
          <div class="side__v">Не делись личными данными в публичных файлах.</div>
        </div>
        <div class="side__item">
          <div class="side__k">Preview</div>
          <div class="side__v">PDF и изображения обычно отображаются без скачивания.</div>
        </div>
      </div>

      <div class="note">
        <strong>Note:</strong> Реальную проверку доступа нужно делать на сервере (PHP), не только в интерфейсе.
      </div>
    </aside>
  </section>

</main>

<script>
  // мини-удобство: показываем имя выбранного файла (можно убрать)
  const file = document.getElementById('file');
  const drop = document.querySelector('.drop__text');
  file?.addEventListener('change', () => {
    if (file.files && file.files[0]) drop.innerHTML = `<strong>${file.files[0].name}</strong>`;
  });
</script>

</body>
</html>