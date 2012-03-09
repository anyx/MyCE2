<?php

namespace Anyx\SocialBundle\Provider;


class FacebookProvider extends OAuthProvider {

    /**
     * {@inheritDoc}
     */
    protected $options = array(
        'authorization_url' => 'https://www.facebook.com/dialog/oauth',
        'access_token_url'  => 'https://graph.facebook.com/oauth/access_token',
        'infos_url'         => 'https://graph.facebook.com/me',
	);
}