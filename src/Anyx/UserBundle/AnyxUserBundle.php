<?php

namespace Anyx\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AnyxUserBundle extends Bundle {
	
	/**
	 * 
	 */
	public function getParent() {
		return 'FOSUserBundle';
	}
}
