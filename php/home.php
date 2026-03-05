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
      <a class="navlink" href="home.php">Home</a>
      <a class="navlink" href="portfolio.php">Portfolio</a>
      <a class="navlink" href="addFile.php">Upload File</a>
      </nav>
    </div>
  </header>

  <main class="container">
    <!-- Hero -->
    <section class="hero">
      <div class="hero__content">
        <h1 class="hero__title">My IT Portfolio</h1>
        <p class="hero__subtitle">
          This section contains my educational projects, assignments, and professional achievements.
             The materials are organised by year and category, reflecting my growth as an IT specialist.
        </p>

        <div class="hero__actions">
          <a class="btn btn--primary" href="../portfolio.php">Register</a>
          <a class="btn btn--secondary" href="register.php">Login</a>
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


    <!-- Portfolio Structure -->
    <section class="section">
      <div class="section__head">
        <h2 class="section__title">My Work & Projects</h2>
        <p class="section__desc">Here you will find my academic and practical work, organised by year of study and subject area.</p>
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
  </main>

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