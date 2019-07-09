<?php declare(strict_types = 1);

namespace SiViN\OpenIdNette;

use OpenIDConnectClient\AccessToken;
use OpenIDConnectClient\Exception\InvalidTokenException;
use OpenIDConnectClient\OpenIDConnectProvider;

class OpenIdNette extends OpenIDConnectProvider
{
	/**
	 * @param string $code
	 *
	 * @return AccessToken
	 * @throws InvalidTokenException
	 */
	public function getToken(string $code): AccessToken
	{
		$token = $this->getAccessToken('authorization_code', [ 'code' => $code ]);
		return $token;
	}
	
}
