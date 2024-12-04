<?php
//Your Settings can be read here: settings::read('myArray/settingName') = $settingValue;
//Your Settings can be saved here: settings::set('myArray/settingName',$settingValue,$overwrite = true/false);
class forge_installer{
    //public static function command($line):void{}//Run when base command is class name, $line is anything after base command (string). e.g. > [base command] [$line]
    //public static function init():void{}//Run at startup
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
            "1.21.1"=>array(
                "52.0.22"
            ),
            "1.21"=>array(
                "51.0.33"
            ),
            "1.20.6"=>array(
                "50.1.0"
            ),
            "1.20.4"=>array(
                "49.1.0"
            ),
            "1.20.3"=>array(
                "49.0.2"
            ),
            "1.20.2"=>array(
                "48.1.0"
            ),
            "1.20.1"=>array(
                "47.3.0"
            ),
            "1.20"=>array(
                "46.0.14"
            ),
            "1.19.4"=>array(
                "45.2.0"
            ),
            "1.19.3"=>array(
                "44.1.0"
            ),
            "1.19.2"=>array(
                "43.4.0"
            ),
            "1.19.1"=>array(
                "42.0.9"
            ),
            "1.19"=>array(
                "41.1.0"
            ),
            "1.18.2"=>array(
                "40.2.0"
            ),
            /*
            "1.18.1"=>array(
                
            ),
            "1.18"=>array(
                
            ),
            "1.17.1"=>array(
                
            ),
            "1.16.5"=>array(
                
            ),
            "1.16.4"=>array(
                
            ),
            "1.16.3"=>array(
                
            ),
            "1.16.2"=>array(
                
            ),
            "1.16.1"=>array(
                
            ),
            "1.16"=>array(
                
            ),
            "1.15.2"=>array(
                
            ),
            "1.15.1"=>array(
                
            ),
            "1.15"=>array(
                
            ),
            "1.14.4"=>array(
                
            ),
            "1.14.3"=>array(
                
            ),
            "1.14.2"=>array(
                
            ),
            "1.13.2"=>array(
                
            ),*/
            "1.12.2"=>array(
                "14.23.5.2859"
            ),
            "1.12.1"=>array(
                "14.22.1.2478"
            ),
            "1.12"=>array(
                "14.21.1.2387"
            ),
            "1.11.2"=>array(
                
            ),
            /*
            "1.11"=>array(
                
            ),
            "1.10.2"=>array(
                
            ),
            "1.10"=>array(
                
            ),
            "1.9.4"=>array(
                
            ),
            "1.9"=>array(
                
            ),
            "1.8.9"=>array(
                
            ),
            "1.8.8"=>array(
                
            ),
            "1.8"=>array(
                
            ),
            "1.7.10"=>array(
                
            ),
            "1.7"=>array(
                
            ),
            "1.6.4"=>array(
                
            ),
            "1.6.3"=>array(
                
            ),
            "1.6.2"=>array(
                
            ),
            "1.6.1"=>array(
                
            ),
            "1.5.2"=>array(
                
            ),
            "1.5.1"=>array(
                
            ),
            "1.5"=>array(
                
            ),
            "1.4.7"=>array(
                
            ),
            "1.4.6"=>array(
                
            ),
            "1.4.5"=>array(
                
            ),
            "1.4.4"=>array(
                
            ),
            "1.4.3"=>array(
                
            ),
            "1.4.2"=>array(
                
            ),
            "1.4.1"=>array(
                
            ),
            "1.4.0"=>array(
                
            ),
            "1.3.2"=>array(
                
            ),
            "1.2.5"=>array(
                
            ),
            "1.2.4"=>array(
                
            ),
            "1.2.3"=>array(
                
            ),
            "1.1"=>array(
                
            )*/
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