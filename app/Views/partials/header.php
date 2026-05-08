<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ChatGPT analyzer</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" />
  <link rel="stylesheet" href="src/styles.css" />
</head>
<body>
  <header class="title-header">
    <div class="page-title">GuardaChat | Keep track of your ChatGPT history</div>
  </header>
  <main class="main-container">
    <aside class="list-aside">
      <div>
        <a class="list-btn" title="View your chat archive" href="<?= url() ?>">💬 My chats</a>
        <a class="list-btn" title="Manage your chat database" href="<?= url('import') ?>">🛢️ Import</a>
        <a class="list-btn" title="Search in your chats" href="<?= url('search') ?>">🔎 Search</a>
        <a class="list-btn" title="Settings" href="<?= url('settings') ?>">⚙️ Settings</a>
        <a class="list-btn" title="About this application" href="<?= url('about') ?>">👨🏾‍💻 About</a>
      </div>
    </aside>
