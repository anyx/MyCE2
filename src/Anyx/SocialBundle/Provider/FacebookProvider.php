<?php

namespace Anyx\SocialBundle\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FacebookProvider extends OAuthProvider {

    /**
     * {@inheritDoc}
     */
    protected $options = array(
        'authorization_url' => 'https://www.facebook.com/dialog/oauth',
        'access_token_url'  => 'https://graph.facebook.com/oauth/access_token',
        'infos_url'         => 'https://graph.facebook.com/me',
	);

    /**
     * {@inheritDoc}
     */
    public function getAuthorizationUrl()
    {
        $parameters = array(
            'response_type' => 'code',
            'client_id'     => $this->getOption('client_id'),
            'scope'         => $this->getOption('scope'),
            'redirect_uri'  => $this->getRedirectUri()
        );

        return $this->getOption('authorization_url').'?'.http_build_query($parameters);
    }	
	
    /**
     * {@inheritDoc}
     */
    public function getAccessToken(Request $request )
    {
		
		if ( $request->get('code') == null ) {
			$response = new RedirectResponse( $this->getAuthorizationUrl() );
			return $response->send();
		}
		
		$parameters = array(
            'code'          => $request->get('code'),
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->getOption('client_id'),
            'client_secret' => $this->getOption('secret'),
            'redirect_uri'  => $this->getRedirectUri(),
        );

		$url = $this->getOption('access_token_url');

		$response = $this->getBrowser()->call( $url . '?' . http_build_query($parameters), 'GET' );

		$content = array();
		parse_str( $response->getContent(), $content );
		
		if ( !is_array( $content ) || !array_key_exists('access_token', $content ) ) {
			throw new Authentication\Exception( 'Access token not present in response' );
		}
		
		return $content['access_token'];
    }	
}