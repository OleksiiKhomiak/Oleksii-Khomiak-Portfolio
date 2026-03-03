<?php 
session_start();
$_SESSION['userName'] = "UserName";
?>
<!DOCTYPE html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Online Portfolio</title>
  <link rel="stylesheet" href="../css/home.css" />
</head>
<body>
 
  <!-- Top Bar -->
  <header class="topbar">
    <div class="container topbar__inner">
      <a class="brand" href="#">
        <span class="brand__mark" aria-hidden="true"></span>
        <span class="brand__text">Portfolio</span>
      </a>

      <nav class="topbar__nav" aria-label="Top navigation">
        <a class="navlink" href="#">Home</a>
        <a class="navlink" href="#">Login</a>
      </nav>
    </div>
  </header>

  <main class="container">
    <!-- Hero -->
    <section class="hero">
      <div class="hero__content">
        <h1 class="hero__title">Online Portfolio System</h1>
        <p class="hero__subtitle">
          Структурируй доказательства по годам и категориям. Делись материалами выборочно — доступ задаётся
          для каждого файла отдельно.
        </p>

        <div class="hero__actions">
          <a class="btn btn--primary" href="../portfolio.php">Перейти в портфолио</a>
          <a class="btn btn--secondary" href="../register.php">Войти</a>
        </div>

        <div class="hero__meta">
          <div class="pill">Structure: Year → Categories → Files</div>
          <div class="pill">Roles: Administrator & Visitor</div>
        </div>
      </div>

      <aside class="hero__panel" aria-label="Quick preview">
        <div class="panel__header">
          <h2 class="panel__title">Quick preview</h2>
          <span class="badge badge--info">Minimal UI</span>
        </div>

        <div class="panel__list">
          <div class="panel__item">
            <div class="panel__k">Year 1</div>
            <div class="panel__v">Web Development, Project Innovate…</div>
          </div>
          <div class="panel__item">
            <div class="panel__k">Year 2</div>
            <div class="panel__v">Software Quality, Projects…</div>
          </div>
          <div class="panel__item">
            <div class="panel__k">Access</div>
            <div class="panel__v">Per visitor, per file</div>
          </div>
        </div>
      </aside>
    </section>

    <!-- Search / Filters (visual only, можно подключить позже) -->
    <section class="tools" aria-label="Portfolio tools">
      <div class="tools__row">
        <div class="field">
          <label class="label" for="q">Поиск</label>
          <input class="input" id="q" type="search" placeholder="Например: reflection, report, wireframe…" />
        </div>

        <div class="field">
          <label class="label" for="year">Год</label>
          <select class="select" id="year">
            <option value="">Все</option>
            <option>Year 1</option>
            <option>Year 2</option>
            <option>Year 3</option>
            <option>Year 4</option>
          </select>
        </div>

        <div class="field">
          <label class="label" for="cat">Категория</label>
          <select class="select" id="cat">
            <option value="">Все</option>
            <option>Web Development</option>
            <option>Project Innovate</option>
            <option>Software Quality</option>
            <option>Projects</option>
          </select>
        </div>

        <div class="field field--actions">
          <span class="label label--ghost"> </span>
          <button class="btn btn--primary btn--full" type="button">Применить</button>
        </div>
      </div>
      <p class="hint">
        Подсказка: для Visitor на реальном сайте показывай только доступные файлы. Админ видит всё.
      </p>
    </section>

    <!-- Portfolio Structure -->
    <section class="section">
      <div class="section__head">
        <h2 class="section__title">Portfolio structure</h2>
        <p class="section__desc">Пример организации материалов по годам, категориям и файлам.</p>
      </div>

      <!-- Year 1 -->
      <article class="year">
        <header class="year__head">
          <h3 class="year__title">Year 1</h3>
          <span class="muted">2 categories • 6 files</span>
        </header>

        <div class="grid">
          <!-- Category Card -->
          <div class="card">
            <div class="card__head">
              <h4 class="card__title">Web Development</h4>
              <span class="badge badge--ok">Visible</span>
            </div>
            <p class="card__desc">Weekly evidence, assignments, wireframes, code snippets.</p>

            <div class="filelist" aria-label="Files list">
              <a class="file" href="#">
                <div class="file__name">Week 3 Reflection (PDF)</div>
                <div class="file__meta">Updated: 2026-03-01</div>
              </a>
              <a class="file" href="#">
                <div class="file__name">Landing Page Wireframe (PDF)</div>
                <div class="file__meta">Updated: 2026-02-20</div>
              </a>
              <a class="file" href="#">
                <div class="file__name">HTML/CSS Practice (ZIP)</div>
                <div class="file__meta">Updated: 2026-02-10</div>
              </a>
            </div>

            <div class="card__actions">
              <a class="btn btn--secondary btn--sm" href="#">Open category</a>
              <a class="btn btn--ghost btn--sm" href="#">View all</a>
            </div>
          </div>

          <!-- Category Card -->
          <div class="card">
            <div class="card__head">
              <h4 class="card__title">Project Innovate</h4>
              <span class="badge badge--ok">Visible</span>
            </div>
            <p class="card__desc">Documentation, reports, presentations, progress evidence.</p>

            <div class="filelist" aria-label="Files list">
              <a class="file" href="#">
                <div class="file__name">Project Plan (PDF)</div>
                <div class="file__meta">Updated: 2026-02-28</div>
              </a>
              <a class="file" href="#">
                <div class="file__name">Sprint Review Slides (PDF)</div>
                <div class="file__meta">Updated: 2026-02-14</div>
              </a>
              <a class="file" href="#">
                <div class="file__name">Final Report (PDF)</div>
                <div class="file__meta">Updated: 2026-01-30</div>
              </a>
            </div>

            <div class="card__actions">
              <a class="btn btn--secondary btn--sm" href="#">Open category</a>
              <a class="btn btn--ghost btn--sm" href="#">View all</a>
            </div>
          </div>
        </div>
      </article>

      <!-- Year 2 (preview) -->
      <article class="year">
        <header class="year__head">
          <h3 class="year__title">Year 2</h3>
          <span class="muted">Preview</span>
        </header>

        <div class="grid grid--preview">
          <div class="card card--muted">
            <div class="card__head">
              <h4 class="card__title">Software Quality</h4>
              <span class="badge badge--info">Coming</span>
            </div>
            <p class="card__desc">Testing, QA reports, code reviews, quality metrics.</p>
          </div>

          <div class="card card--muted">
            <div class="card__head">
              <h4 class="card__title">Projects</h4>
              <span class="badge badge--info">Coming</span>
            </div>
            <p class="card__desc">Team projects, deliverables, demos and documentation.</p>
          </div>
        </div>
      </article>
    </section>

    <!-- Info Cards -->
    <section class="section">
      <div class="section__head">
        <h2 class="section__title">Key rules (MVP)</h2>
        <p class="section__desc">Что важно реализовать в логике (когда подключишь PHP).</p>
      </div>

      <div class="grid grid--3">
        <div class="infocard">
          <h3 class="infocard__title">Two roles</h3>
          <p class="infocard__text">
            Administrator управляет категориями, файлами и доступами. Visitor только просматривает разрешённые файлы.
          </p>
        </div>
        <div class="infocard">
          <h3 class="infocard__title">Access per visitor</h3>
          <p class="infocard__text">
            Доступ назначается для каждого файла отдельно и зависит от конкретного пользователя, а не от роли.
          </p>
        </div>
        <div class="infocard">
          <h3 class="infocard__title">Browser view</h3>
          <p class="infocard__text">
            Просмотр файлов должен быть возможен прямо в приложении (например, PDF в iframe). Download — опционально.
          </p>
        </div>
      </div>
    </section>
  </main>
</body>
</html>