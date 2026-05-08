    <section class="content-section">
      <div class="sub-container">
        <h2>Settings</h2>
        <form action="<?= url('settings') ?>" method="POST">
          <label for="cbo-timezones">Timezone:
            <select id="cbo-timezones" name="cbo-timezones">
              <?= $tzList ?>
            </select>
          </label>
          <input type="submit" value="Save changes" />
        </form>
      </div>
    </section>
    <script src="src/search.js"></script>
