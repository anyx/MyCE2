<?php

namespace Anyx\CrosswordBundle\Listener;

use Anyx\SocialUserBundle\Event;

class UserListener {
	
	/**
	 * @todo move all user activity's
	 * 
	 * @param Event\MergeUsersEvent $event 
	 */
	public function onMergeUsers( Event\MergeUsersEvent $event ) {
	}
}