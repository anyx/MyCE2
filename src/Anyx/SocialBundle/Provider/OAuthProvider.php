<?php

namespace Anyx\SocialBundle\Provider;

use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\Request;
use Anyx\SocialBundle\Authentication;

use Buzz\Browser;


/**
 * OAuthProvider
 *
 */
class OAuthProvider
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var Buzz\Browser
     */
    protected $browser;

    /**
     * @param Buzz\Client\ClientInterface $httpClient
     * @param array                       $options
     */
    public function __construct( Browser $browser, array $options )
    {
		$this->browser = $browser;
		
		$this->options = array_merge($this->options, $options);

		$this->configure();
    }

    /**
     * Gives a chance for extending providers to customize stuff
     */
    public function configure()
    {

    }

    /**
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->getOption('redirect_uri');
    }

    /**
     * Retrieve an option by name
     *
     * @throws InvalidArgumentException When the option does not exist
     * @param string                    $name The option name
     * @return mixed                    The option value
     */
    public function getOption($name)
    {
		if (!array_key_exists($name, $this->options)) {
            throw new \InvalidArgumentException(sprintf('Unknown option "%s"', $name));
        }

        return $this->options[$name];
    }

	/**
	 * 
	 */
	public function getOptions() {
		return $this->options;
	}

	/**
	 *
	 * @param string $name
	 * @param mixed $value 
	 */
	public function setOption( $name, $value ) {
		$this->options[$name] = $value;
	}

	/**
	 *
	 * @return Buzz\Browser
	 */
	public function getBrowser() {
		return $this->browser;
	}

    /**
     * {@inheritDoc}
     */
    public function getUserData(  Authentication\AccessToken $accessToken )
    {

		if ($this->getOption('infos_url') === null) {
            return $accessToken;
        }

        $url = $this->getOption('infos_url').'?'.http_build_query(array(
            'access_token' => $accessToken
        ));

        $content = $this->getBrowser()->call($url, 'GET')->getContent();

		return json_decode( $content, true );
    }
	
    /**
     * {@inheritDoc}
     */
    public function getAccessToken(Request $request )
    {
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