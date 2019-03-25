<?php

namespace Loonins\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class LooninsUserBundle extends Bundle
{
	public function getParent() {
        return 'FOSUserBundle';
    }
}
