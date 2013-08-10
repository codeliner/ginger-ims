<?php
namespace Cl\Mvc\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
/**
 * Description of SendDownload
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class SendDownload  extends AbstractPlugin
{
    public function __invoke ($content, $contentType, $filename = "download") {
        $controller = $this->getController();

        $respone = $controller->getResponse()->setContent($content);

        $headers = $respone->getHeaders();
        $headers->clearHeaders();
        $headers->addHeaderLine('Content-Type', $contentType)
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->addHeaderLine('Content-Length', strlen($content));

        return $respone;
    }
}