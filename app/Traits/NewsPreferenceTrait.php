<?php

namespace App\Traits;

use App\Models\NewsPreference;
use Cache;

trait NewsPreferenceTrait {

  public function getPreference($name) {
    $settings = $this->getCache();
    $value = array_get($settings, $name);
    return ($value !== '') ? $value : NULL;
  }

  public function setPreference($name, $value) {
    $this->storePreference($name, $value);
    $this->setCache();
  }

  public function setPreferences($data = []) {
    foreach($data as $name => $value) {
      $this->storePreference($name, $value);
    }
    $this->setCache();
  }

  private function storePreference($name, $value) {
    $record = NewsPreference::where(['user_id' => $this->id, 'name' => $name])->first();

    if ($record) {
      $record->value = $value;
      $record->save();
    } else {
      $data = new NewsPreference(['name' => $name, 'value' => $value]);
      $this->preferences()->save($data);
    }
  }

  private function getCache() {
    if (Cache::has('news_preferences_' . $this->id)) {
      return Cache::get('news_preferences_' . $this->id);
    }
    return $this->setCache();
  }

  private function setCache() {
    if (Cache::has('news_preferences_' . $this->id)) {
      Cache::forget('news_preferences_' . $this->id);
    }
    $settings = NewsPreference::where('user_id',$this->id)->get()->toArray();;
    Cache::forever('news_preferences_' . $this->id, $settings);
    return $this->getCache();
  }

}
