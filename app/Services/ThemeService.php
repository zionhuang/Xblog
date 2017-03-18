<?php
/**
 * Created by PhpStorm.
 * User: lufficc
 * Date: 2017/3/18
 * Time: 15:14
 */

namespace App\Services;


use App\Facades\XblogConfig;
use File;
use Illuminate\Support\Collection;

class ThemeService
{
    protected $themes = null;
    protected $currentTheme = null;

    /**
     * @return Collection
     */
    public function getThemes()
    {
        if ($this->themes != null)
            return $this->themes;
        $this->themes = new Collection();
        $themeDirections = File::directories(base_path('themes/'));
        foreach ($themeDirections as $themeDirection) {
            $theme = json_decode((File::get($themeDirection . '/theme.json')));
            $this->themes->push($theme);
        }
        return $this->themes;
    }

    public function getCurrentTheme()
    {
        if ($this->currentTheme != null)
            return $this->currentTheme;
        $themeDirection = base_path('themes/' . get_config('theme', 'xblog'));
        return $this->currentTheme = json_decode((File::get($themeDirection . '/theme.json')));
    }

    private function getThemePath($themeName)
    {
        return base_path('themes/' . $themeName);
    }

    public function exists($themeName)
    {
        return File::exists($this->getThemePath($themeName));
    }

    public function delete($themeName)
    {
        if ($themeName == 'xblog' || !$this->exists($themeName))
            return false;
        $path = $this->getThemePath($themeName);

        return File::deleteDirectory($path);
    }

    public function setTheme($themeName)
    {
        if (!$this->exists($themeName))
            return false;
        return XblogConfig::saveSetting('theme', $themeName);
    }
}