<?php

namespace App\Common;

use Symfony\Component\HttpFoundation\RedirectResponse;

class BlankRedirectResponse extends RedirectResponse {
    public function setTargetUrl($url) {
        if (empty($url)) {
            throw new \InvalidArgumentException('Cannot redirect to an empty URL.');
        }

        $this->targetUrl = $url;

        $this->setContent(
            sprintf('<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="refresh" content="0;url=%1$s" />

        <title>Redireccionando a %1$s</title>
    </head>
    <body>
    </body>
</html>', htmlspecialchars($url, ENT_QUOTES, 'UTF-8')));

        $this->headers->set('Location', $url);

        return $this;
    }
}