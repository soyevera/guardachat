<?php

namespace App\Controllers;

use stdClass;

class SettingsController extends BaseController
{
  private stdClass $settings;

  public function __construct()
  {
    $this->settings = json_decode(file_get_contents(SETTINGS_FILE));
  }

  private function renderTzList(): string
  {
    $tzArray = timezone_identifiers_list();
    $tzList  = '';
    $currTz  = $this->getTimezone();
    
    for ($i = 0; $i < count($tzArray); $i++) {
      $value    = $tzArray[$i];
      $option   = str_replace('_', ' ', $value);
      $selected = ($value === $currTz) ? ' selected' : '';
      $tzList .= "<option value=\"{$value}\"$selected>{$option}</option>";
    }

    return $tzList;
  }

  private function getTimezone(): string
  {
    return $this->settings->timezone;
  }

  private function setTimezone($tz): void
  {
    $this->settings->timezone = $tz;
    file_put_contents(SETTINGS_FILE, json_encode($this->settings, JSON_PRETTY_PRINT));
  }

  public function index(): void
  {
    // If request method is POST, there will be options
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['cbo-timezones'])) $this->setTimezone($_POST['cbo-timezones']);
    }

    $data = ['tzList' => $this->renderTzList()];
    $this->render('settings', $data);
  }
}