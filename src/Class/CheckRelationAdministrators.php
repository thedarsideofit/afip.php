<?php
/**
 * SDK for AFIP Register Scope Ten (ws_sr_padron_a10)
 * 
 * @link http://www.afip.gob.ar/ws/ws_sr_padron_a10/manual_ws_sr_padron_a10_v1.1.pdf WS Specification
 *
 * @author 	Diego Ramirez
 * @package Afip
 * @version 1.0
 **/

class CheckRelationAdministrators extends AfipWebService {

	var $soap_version 	= SOAP_1_1;
	var $WSDL 			= 'ws-check-ar.wsdl';
	var $URL 			= 'https://wsaa.afip.gov.ar/ws-check-ar/CheckArService/CheckBean';
	var $WSDL_TEST 		= 'ws-check-ar.wsdl';
	var $URL_TEST 		= 'https://wsaahomo.afip.gov.ar/ws-check-ar/CheckArService/CheckBean';

	/**
	 * Asks to web service for servers status {@see WS 
	 * Specification item 3.1}
	 *
	 * @since 1.0
	 *
	 * @return object { appserver => Web Service status, 
	 * dbserver => Database status, authserver => Autentication 
	 * server status}
	**/
	public function GetServerStatus()
	{
		return $this->ExecuteRequest('dummy');
	}

	/**
	 * Asks to web service for taxpayer details {@see WS 
	 * Specification item 3.2}
	 *
	 * @since 1.0
	 *
	 * @throws Exception if exists an error in response 
	 *
	 * @return object|null if taxpayer does not exists, return null,  
	 * if it exists, returns persona property of response {@see 
	 * WS Specification item 3.2.2}
	**/
	public function GetIsAdminRel($autorizado, $representado)
	{
		$ta = $this->afip->GetServiceTA('ws-check-ar');
		
		$params = array(
			'token' 			=> $ta->token,
			'sign' 				=> $ta->sign,
			'cuit' 	            => $this->afip->CUIT,
			'autorizado' 		=> $autorizado,
			'representado'		=> $representado,
		);

		try {
			return $this->ExecuteRequest('esAdminRel', $params);
		} catch (Exception $e) {
			if (strpos($e->getMessage(), 'No existe') !== FALSE)
				return NULL;
			else
				throw $e;
		}
	}

	/**
	 * Sends request to AFIP servers
	 * 
	 * @since 1.0
	 *
	 * @param string 	$operation 	SOAP operation to do 
	 * @param array 	$params 	Parameters to send
	 *
	 * @return mixed Operation results 
	 **/
	public function ExecuteRequest($operation, $params = array())
	{
		$results = parent::ExecuteRequest($operation, $params);
                return $results;
                
		return $results->{$operation == 'esAdminRel' ? 'esAdminRel' : 'return'};
	}
}

