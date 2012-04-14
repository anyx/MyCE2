<?php

namespace Anyx\SocialUserBundle\User;

/**
 * 
 */
class SocialAccount
{
    /**
	 * 
     */
    protected $id;
	
	/**
	 * 
	 */
	protected $serviceName;
	
	/**
	 * 
	 */
	protected $accountId;

	/**
	 * 
	 */
	protected $userName;
	
	/**
	 * 
	 */
	protected $accountData;

	/**
	 *
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 *
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 *
	 */
	public function getServiceName() {
		return $this->serviceName;
	}

	/**
	 *
	 */
	public function setServiceName($serviceName) {
		$this->serviceName = $serviceName;
	}

	/**
	 *
	 */
	public function getAccountId() {
		return $this->accountId;
	}

	/**
	 *
	 */
	public function setAccountId($accountId) {
		$this->accountId = $accountId;
	}

	/**
	 *
	 */
	public function getUserName() {
		return $this->userName;
	}

	/**
	 *
	 */
	public function setUserName($userName) {
		$this->userName = $userName;
	}
	
	/**
	 *
	 */
	public function getAccountData() {
		return $this->accountData;
	}

	/**
	 *
	 */
	public function setAccountData($accountData) {
		$this->accountData = $accountData;
	}
}