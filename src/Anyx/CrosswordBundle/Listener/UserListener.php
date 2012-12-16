<?php

namespace Anyx\CrosswordBundle\Listener;

use Anyx\SocialUserBundle\Event;

class UserListener
{

    /**
     * @todo move all user activity's
     * 
     * @param Event\MergeUsersEvent $event 
     */
    public function onMergeUsers(Event\MergeUsersEvent $event)
    {
        
    }

    /**
     * 
     * @param \Anyx\SocialUserBundle\Event\CreateUserEvent $event
     */
    public function onCreateUser(Event\CreateUserEvent $event)
    {
        $user = $event->getUser();
        $user->setName($event->getAccount()->getValue('userName'));
        $user->setEnabled(true);
        $event->setUser($user);
    }
}