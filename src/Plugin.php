<?php
namespace ComposerWordPress;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

class Plugin implements PluginInterface,EventSubscriberInterface
{
    function recurse_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public function recurse_delete($dir) { 
        if (is_dir($dir)) { 
          $objects = scandir($dir);
          foreach ($objects as $object) { 
            if ($object != "." && $object != "..") { 
              if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
              $this->recurse_delete($dir. DIRECTORY_SEPARATOR .$object);
              else
                unlink($dir. DIRECTORY_SEPARATOR .$object); 
            } 
          }
          rmdir($dir); 
        } 
    }

    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => 'onPostInstallCmd',
            ScriptEvents::POST_UPDATE_CMD => 'onPostInstallCmd',
        ];
    }

    public function onPostInstallCmd(Event $event)
    {
        // geting paths from composer.json extra node
        $composer = $event->getComposer();
        $extra = $composer->getPackage()->getExtra();
        $composer_install_path_src = $extra['composer-custom-install-path-src'];
        $composer_install_path_dest = $extra['composer-custom-install-path-dest'];
        print_r("##### COPYING wordpress packages from {$composer_install_path_src} to {$composer_install_path_dest} ######");
        if ( is_dir(getcwd() . '/'.$composer_install_path_src) && is_dir(getcwd() . '/' . $composer_install_path_dest ) ) {
            $this->recurse_copy(getcwd() . '/'.$composer_install_path_src, getcwd() . '/' . $composer_install_path_dest);
        }
    }

    public function activate(Composer $composer, IOInterface $io)
    {
        // Implement activate method
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        // Implement deactivate method
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // Implement uninstall method
    }
}
