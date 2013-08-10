<?php

/**
 * von Weth Online Shop
 *
 * @link      http://vonwerth.de/
 * @copyright Copyright (c) 2012 von Werth GmbH
 */
namespace Cl\Mvc\Router\Http;

use Zend\Mvc\Router\Http\Regex;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Stdlib\RequestInterface as Request;

class RawUriRegex extends Regex
{
    /**
     * match(): defined by RouteInterface interface.
     *
     * @param  Request $request
     * @param  integer $pathOffset
     * @return RouteMatch
     */
    public function match(Request $request, $pathOffset = null)
    {
        if (!method_exists($request, 'getUri')) {
            return null;
        }

        $uri  = $request->getUri();
        $path = rawurldecode($uri->getPath());

        if ($pathOffset !== null) {
            $result = preg_match('(\G' . $this->regex . ')', $path, $matches, null, $pathOffset);
        } else {
            $result = preg_match('(^' . $this->regex . '$)', $path, $matches);
        }

        if (!$result) {
            return null;
        }

        $matchedLength = strlen($matches[0]);

        foreach ($matches as $key => $value) {
            if (is_numeric($key) || is_int($key)) {
                unset($matches[$key]);
            } else {
                $matches[$key] = urldecode($matches[$key]);
            }
        }

        return new RouteMatch(array_merge($this->defaults, $matches), $matchedLength);
    }
    
    /**
     * assemble(): Defined by Route interface.
     *
     * @see    Route::assemble()
     * @param  array $params
     * @param  array $options
     * @return mixed
     */
    public function assemble(array $params = array(), array $options = array())
    {
        $url                   = $this->spec;
        $mergedParams          = array_merge($this->defaults, $params);
        $this->assembledParams = array();
        
        foreach ($mergedParams as $key => $value) {
            $spec = '%' . $key . '%';
            
            if (strpos($url, $spec) !== false) {
                $url = str_replace($spec, $value, $url);
                
                $this->assembledParams[] = $key;
            }
        }
        
        return $url;
    }
}