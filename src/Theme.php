<?php

namespace Cankod\Theme;

class Theme {

    protected $theme;

    public function asset($param)
    {
        return asset(config('theme.assets_path').'/'.config('theme.current_theme').'/'.$param);
    }

    public function assetLink($style,$param, $secure = false)
    {
        if($style == "css")
        {
            if ($secure == true) {
                return '<link rel="stylesheet" type="text/css" href="'.secure_asset(config('theme.assets_path').'/'.config('theme.current_theme').'/'.$param).'"/>';
            }
            return '<link rel="stylesheet" type="text/css" href="'.asset(config('theme.assets_path').'/'.config('theme.current_theme').'/'.$param).'"/>';
        }
        if($style == "js")
        {
            if ($secure == true) {
                return '<script src="'.secure_asset(config('theme.assets_path').'/'.config('theme.current_theme').'/'.$param).'"></script>';
            }
            return '<script src="'.asset(config('theme.assets_path').'/'.config('theme.current_theme').'/'.$param).'"></script>';
        }
    }

}

