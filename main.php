<?php
class forge_installer{
    public static function downloadVersion(string $minecraftVersion, string $specialVersion):bool{
        return downloader::downloadFile(settings::read('url') . '/' . $minecraftVersion . '-' . $specialVersion . '/forge-' . $minecraftVersion . '-' . $specialVersion . '-installer.jar', settings::read('libraryDir') . '\\forge-' . $minecraftVersion . '-' . $specialVersion . '-installer.jar');
    }
    public static function init():void{
        $defaultSettings = array(
            "url" => "https://maven.minecraftforge.net/net/minecraftforge/forge",
            "libraryDir" => "mcservers\\library\\forge"
        );
        foreach($defaultSettings as $settingName => $settingValue){
            settings::set($settingName,$settingValue,false);
        }
    }//Run at startup
    public static function setSetting(string $settingName, mixed $settingValue, bool $overwrite):bool{
        return settings::set($settingName,$settingValue,$overwrite);
    }
    public static function installForge(string $minecraftVersion, string $specialVersion, string $path, bool $autoDownload = true):bool|string{
        $jarName = 'forge-' . $minecraftVersion . '-' . $specialVersion . '-installer.jar';
        $file = settings::read('libraryDir') . '\\' . $jarName;
        $i=0;
        retry:
        if(is_file($file)){
            files::copyFile($file,$path . '\\' . $jarName);
            cmd::run('java -jar "' . $path . '\\' . $jarName . '" --installServer "' . $path . '"');
            unlink($path . '\\' . $jarName);
            unlink($jarName . '.log');
            if(is_dir($path . "//libraries")){
                return true;
            }
        }
        else{
            if($i < 2 && $autoDownload){
                self::downloadVersion($minecraftVersion,$specialVersion);
                $i++;
                goto retry;
            }
        }
        return false;
    }
    public static function listVersions():array{
        return array(
            "1.21.4" => ["54.1.0"],
            "1.21.3" => ["53.0.53"],
            "1.21.1" => ["52.0.22"],
            "1.21"   => ["51.0.33"],
            "1.20.6" => ["50.1.0"],
            "1.20.4" => ["49.1.0"],
            "1.20.3" => ["49.0.2"],
            "1.20.2" => ["48.1.0"],
            "1.20.1" => ["47.3.0"],
            "1.20"   => ["46.0.14"],
            "1.19.4" => ["45.2.0"],
            "1.19.3" => ["44.1.0"],
            "1.19.2" => ["43.4.0"],
            "1.19.1" => ["42.0.9"],
            "1.19"   => ["41.1.0"],
            "1.18.2" => ["40.2.0"],
            "1.18.1" => ["39.1.0"],
            "1.18"   => ["38.0.17"],
            "1.17.1" => ["37.1.1"],
            "1.16.5" => ["36.2.34"],
            "1.16.4" => ["35.1.4"],
            "1.16.3" => ["34.1.0"],
            "1.16.2" => ["33.0.61"],
            "1.16.1" => ["32.0.108"],
            "1.15.2" => ["31.2.57"],
            "1.15.1" => ["30.0.51"],
            "1.15"   => ["29.0.4"],
            "1.14.4" => ["28.2.26"],
            "1.14.3" => ["27.0.60"],
            "1.14.2" => ["26.0.63"],
            "1.13.2" => ["25.0.223"],
            "1.12.2" => ["14.23.5.2859"],
            "1.12.1" => ["14.22.1.2478"],
            "1.12"   => ["14.21.1.2387"],
            "1.11.2" => ["13.20.1.2588"],
            "1.11"   => ["13.19.1.2189"],
            "1.10.2" => ["12.18.3.2511"],
            "1.10"   => ["12.18.0.2000"],
            "1.9.4"  => ["12.17.0.2317"],
            "1.9"    => ["12.16.1.1887"],
            "1.8.9"  => ["11.15.1.2318"],
            "1.8.8"  => ["11.15.0.1655"],
            "1.8"    => ["11.14.4.1563"],
            "1.7.10" => ["10.13.4.1614"],
            "1.7.2"  => ["10.12.2.1161"]
        );
    }
    public static function listSpecialVersions(string $version):array{
        return self::listVersions()[$version];
    }
    public static function listMinecraftVersions():array{
        $array = array();
        foreach(self::listVersions() as $version => $versionBuilds){
            $array[] = $version;
        }
        return $array;
    }
}