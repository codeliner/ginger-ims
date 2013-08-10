<?php
namespace Cl\Translator;

use Zend\I18n\Translator\Translator as ZendTranslator;
use Zend\I18n\Exception;
use Zend\I18n\Translator\Loader\FileLoaderInterface;
use Zend\I18n\Translator\Loader\RemoteLoaderInterface;

/**
 * The Cl\Translator provides a hack for loading same textdomain and locale files from different sources
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Translator extends ZendTranslator
{
    public function getMessages($textDomain, $locale)
    {
        if (!isset($this->messages[$textDomain]) || !isset($this->messages[$textDomain][$locale])) {
            $this->loadMessages($textDomain, $locale);
        }

        return $this->messages[$textDomain][$locale];
    }
    /**
     * Load messages for a given language and domain.
     *
     * @param  string $textDomain
     * @param  string $locale
     * @throws Exception\RuntimeException
     * @return void
     */
    protected function loadMessages($textDomain, $locale)
    {
        if (!isset($this->messages[$textDomain])) {
            $this->messages[$textDomain] = array();
        }

        if (null !== ($cache = $this->getCache())) {
            $cacheId = 'Zend_I18n_Translator_Messages_' . md5($textDomain . $locale);

            if (null !== ($result = $cache->getItem($cacheId))) {
                $this->messages[$textDomain][$locale] = $result;
                return;
            }
        }

        // Try to load from remote sources
        if (isset($this->remote[$textDomain])) {
            foreach ($this->remote[$textDomain] as $loaderType) {
                $loader = $this->getPluginManager()->get($loaderType);

                if (!$loader instanceof RemoteLoaderInterface) {
                    throw new Exception\RuntimeException('Specified loader is not a remote loader');
                }

                $this->messages[$textDomain][$locale] = $loader->load($locale, $textDomain);
                goto cache;
            }
        }

        // Try to load from pattern
        if (isset($this->patterns[$textDomain])) {
            $fileFound = false;
            foreach ($this->patterns[$textDomain] as $pattern) {
                $filename = $pattern['baseDir'] . '/' . sprintf($pattern['pattern'], $locale);

                if (is_file($filename)) {
                    $fileFound = true;
                    $loader = $this->getPluginManager()->get($pattern['type']);

                    if (!$loader instanceof FileLoaderInterface) {
                        throw new Exception\RuntimeException('Specified loader is not a file loader');
                    }

                    //merge same $textDomain and $locale from different sources
                    //with this hack, different modules don't need to define different text domains
                    //for their language files
                    if (!isset($this->messages[$textDomain][$locale])) {
                        $this->messages[$textDomain][$locale] = $loader->load($locale, $filename);
                    } else {
                        $td = $loader->load($locale, $filename);

                        foreach ($td->getArrayCopy() as $key => $value) {
                            $this->messages[$textDomain][$locale][$key] = $value;
                        }
                    }
                }
            }
            if ($fileFound) {
                goto cache;
            }
        }

        // Try to load from concrete files
        foreach (array($locale, '*') as $currentLocale) {
            if (!isset($this->files[$textDomain][$currentLocale])) {
                continue;
            }

            $file   = $this->files[$textDomain][$currentLocale];
            $loader = $this->getPluginManager()->get($file['type']);

            if (!$loader instanceof FileLoaderInterface) {
                throw new Exception\RuntimeException('Specified loader is not a file loader');
            }

            $this->messages[$textDomain][$locale] = $loader->load($locale, $file['filename']);

            unset($this->files[$textDomain][$currentLocale]);
            goto cache;
        }

        // Cache the loaded text domain
        cache:
        if ($cache !== null) {
            $cache->setItem($cacheId, $this->messages[$textDomain][$locale]);
        }
    }
}