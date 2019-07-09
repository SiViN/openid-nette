<?php declare(strict_types = 1);

namespace SiViN\OpenIdNette\DI;

use Lcobucci\JWT\Signer\Rsa\Sha256;
use Nette\DI\CompilerExtension;
use SiViN\OpenIdNette\OpenIdNette;
use UnexpectedValueException;

class OpenIdNetteExtension extends CompilerExtension
{
	const CONFIG_CLIENT_ID = 'clientId';
	
	const CONFIG_CLIENT_SECRET = 'clientSecret';
	
	const CONFIG_ID_TOKEN_ISSUER = 'idTokenIssuer';
	
	const CONFIG_REDIRECT_URI = 'redirectUri';
	
	const CONFIG_URL_AUTHORIZE = 'urlAuthorize';
	
	const CONFIG_URL_ACCESS_TOKEN = 'urlAccessToken';
	
	const CONFIG_URL_RESOURCE_OWNER_DETAILS = 'urlResourceOwnerDetails';
	
	const CONFIG_PUBLIC_KEY_PATH = 'publicKeyPath';
	
	const CONFIG_SCOPES = 'scopes';
	
	const CONFIG_VERIFY_PUBLIC_KEY = 'verifyPublicKey';
	
	private $defaults = [
		self::CONFIG_CLIENT_ID => null,
		self::CONFIG_CLIENT_SECRET => null,
		self::CONFIG_ID_TOKEN_ISSUER => null,
		self::CONFIG_REDIRECT_URI => null,
		self::CONFIG_URL_AUTHORIZE => null,
		self::CONFIG_URL_ACCESS_TOKEN => null,
		self::CONFIG_URL_RESOURCE_OWNER_DETAILS => null,
		self::CONFIG_PUBLIC_KEY_PATH => null,
		self::CONFIG_SCOPES => [ 'openid' ],
		self::CONFIG_VERIFY_PUBLIC_KEY => true
	];
	
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults, $this->config);
		
		$errors = [];
		if (empty($config[self::CONFIG_CLIENT_ID])) {
			$errors[] = self::CONFIG_CLIENT_ID;
		}
		
		if (empty($config[self::CONFIG_CLIENT_SECRET])) {
			$errors[] = self::CONFIG_CLIENT_SECRET;
		}
		
		if (empty($config[self::CONFIG_ID_TOKEN_ISSUER])) {
			$errors[] = self::CONFIG_ID_TOKEN_ISSUER;
		}
		
		if (empty($config[self::CONFIG_REDIRECT_URI])) {
			$errors[] = self::CONFIG_REDIRECT_URI;
		}
		
		if (empty($config[self::CONFIG_URL_ACCESS_TOKEN])) {
			$errors[] = self::CONFIG_URL_ACCESS_TOKEN;
		}
		
		if (empty($config[self::CONFIG_URL_RESOURCE_OWNER_DETAILS])) {
			$errors[] = self::CONFIG_URL_RESOURCE_OWNER_DETAILS;
		}
		
		if (empty($config[self::CONFIG_PUBLIC_KEY_PATH])) {
			$config[self::CONFIG_VERIFY_PUBLIC_KEY] = false;
		}
		
		if (is_array($config[self::CONFIG_SCOPES]) === false) {
			$errors[] = self::CONFIG_SCOPES;
		}
		
		if (count($errors) > 0) {
			throw new UnexpectedValueException('Please configure the OpenIdNette extensions using the section ' . $this->name . ' and fill these properties: ' . implode(', ', $errors) . ' in your config file.');
		}
		
		$collaborators = [
			'signer' => new Sha256()
		];
		
		$builder->addDefinition($this->prefix('openidnette'))
			->setFactory(OpenIdNette::class, ['options' => $config, 'collaborators' => $collaborators]);
	}
}
