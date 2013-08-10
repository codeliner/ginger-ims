<?php

/**
 * CssManager
 * 
 * @package Css
 * @author Alexander Miertsch <miertsch@codeliner.ws>
 * @copyright (c) 2012, Alexander Miertsch
 */
namespace Css;

class CssManager
{
    protected $cssScripts = array();
    
    protected $publicFolder = "css";
    
    protected $overrideFiles = false;

    public function setPublicFolder($folder)
    {
        $this->publicFolder = $folder;
    }
    
    public function setOverrideFiles($flag)
    {
        $this->overrideFiles = $flag;
    }

    public function transferCssScript($publicFileName, $path)
    {
        $publicFile = 'public/' . $this->publicFolder . '/' . $publicFileName;
        
        if (!file_exists($publicFile) 
            || $this->overrideFiles) {
            $f = fopen($publicFile, 'w+');
            
            if ($f) {
                fwrite($f, file_get_contents($path));
                fclose($f);
            }
        }
    }
    
    public function transferLessScript($publicFileName, $path) 
    {
        require_once __DIR__ . '/../../vendor/less/lessc.inc.php';
        
        $less = new \Lessc();
        
        $publicFile = 'public/' . $this->publicFolder . '/' . $publicFileName;
        if ($this->overrideFiles) {
            $less->checkedCompile($path, $publicFile);
        }
    }
    
    public function getPublicFolder()
    {
        return $this->publicFolder;
    }
}
