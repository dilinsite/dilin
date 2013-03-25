<?php
class EcoCalculateurController extends Zend_Controller_Action
{
	private $model = null;
	private $json = null;
	private $wsclienvAddress = null;
	
	public function init()
	{
		$this->model = new Model_DbTable_TransportPlan();
		$environmentalOptions = $this->getInvokeArg('bootstrap')->getOption('environment');
		$this->wsclienvAddress = (string)$environmentalOptions['wsclienv']['url'];
	}
	
	public function getMainCarriageDestinationStationAction()
	{
		$hasRelatedStation = $this->_hasParam('related_origin_station_id');
		$hasRelatedCountry = $this->_hasParam('related_id_country');
		$hasRelatedZip = $this->_hasParam('related_zip_code');
		$idStation = null;
		$idCountry = null;
		$zipCode = null;
		$stations = null;
		
		if ($hasRelatedStation)
		{
			$idStation = (int)$this->_getParam('related_origin_station_id');
		}
		if ($hasRelatedCountry)
		{
			$idCountry = (int)$this->_getParam('related_id_country');
		}
		if ($hasRelatedZip)
		{
			$zipCode = substr((string)$this->_getParam('related_zip_code'), 0, 2);
		}
		
		$stations = $this->model->getEccDestinationStation($idStation, $idCountry, $zipCode);
		
		if (count($stations) > 0)
		{
			$this->json = array();
			$this->json['state'] = 'OK';
			
			$jsonStations = array();
			$jsonStations[] = array(
					'id' => '0',
					'label' => '...',
					'is_default' => '0'
				);
			foreach ($stations as $station)
			{
				$jsonStations[] = array(
					'id' => $station['STATION_ID'],
					'label' => $station['STATION_LABEL'],
					'is_default' => $station['IS_DEFAULT']
				);
			}
			$this->json['stations'] = $jsonStations;
		}
		else
		{
			$this->json = array();
			$this->json['state'] = 'NO_PLATEFORME_FOUND';
		}
		
		$this->_helper->json($this->json);
	}
	
	public function getMainCarriageOriginStationAction()
	{
		$hasRelatedStation = $this->_hasParam('related_destination_station_id');
		$hasRelatedCountry = $this->_hasParam('related_id_country');
		$hasRelatedZip = $this->_hasParam('related_zip_code');
		$idStation = null;
		$idCountry = null;
		$zipCode = null;
		$stations = null;
		
		if ($hasRelatedStation)
		{
			$idStation = (int)$this->_getParam('related_destination_station_id');
		}
		if ($hasRelatedCountry)
		{
			$idCountry = (int)$this->_getParam('related_id_country');
		}
		if ($hasRelatedZip)
		{
			$zipCode = substr((string)$this->_getParam('related_zip_code'), 0, 2);
		}
		
		$stations = $this->model->getEccOriginStation($idStation, $idCountry, $zipCode);
		
		if (count($stations) > 0)
		{
			$this->json = array();
			$this->json['state'] = 'OK';
			
			$jsonStations = array();
			$jsonStations[] = array(
					'id' => '0',
					'label' => '...',
					'is_default' => '0'
				);
			foreach ($stations as $station)
			{
				$jsonStations[] = array(
					'id' => $station['STATION_ID'],
					'label' => $station['STATION_LABEL'],
					'is_default' => $station['IS_DEFAULT']
				);
			}
			$this->json['stations'] = $jsonStations;
		}
		else
		{
			$this->json = array();
			$this->json['state'] = 'NO_PLATEFORME_FOUND';
		}
		
		$this->_helper->json($this->json);
	}
	
	public function getPrePostCarriageCountryAction()
	{
		$langue = $_COOKIE['locale'];
		$preCarriageCountry = $this->model->getPreCarriageCountry();
		$postCarriageCountry = $this->model->getPostCarriageCountry();
		
		$this->json = array();
		$jsonPreCarriageCountry = array();
		$jsonPreCarriageCountry[] = array(
			'id' => '0',
			'label' => '...'
		);
		$jsonPostCarriageCountry = array();
		$jsonPostCarriageCountry[] = array(
			'id' => '0',
			'label' => '...'
		);
		
		foreach ($preCarriageCountry  as $country)
		{
			$label = '';
			switch ($langue)
			{
				case 'fr_FR':
					$label = $country['COUNTRY_LABEL_FR_FR'];
					break;
				case 'en_US':
					$label = $country['COUNTRY_LABEL_UK_EN'];
					break;
				default:
					$label = $country['COUNTRY_LABEL_UK_EN'];
					break;
			}
			$jsonPreCarriageCountry[] = array(
				'id' => $country['COUNTRY_ID'],
				'label' => $label
			);
		}
		
		foreach ($postCarriageCountry  as $country)
		{
			$label = '';
			switch ($langue)
			{
				case 'fr_FR':
					$label = $country['COUNTRY_LABEL_FR_FR'];
					break;
				case 'en_US':
					$label = $country['COUNTRY_LABEL_UK_EN'];
					break;
				default:
					$label = $country['COUNTRY_LABEL_UK_EN'];
					break;
			}
			$jsonPostCarriageCountry[] = array(
				'id' => $country['COUNTRY_ID'],
				'label' => $label
			);
		}
		
		$this->json['preCarriageCountry'] = $jsonPreCarriageCountry;
		$this->json['postCarriageCountry'] = $jsonPostCarriageCountry;
		
		$this->_helper->json($this->json);
	}
	
	public function getRelationEnvironmentalInformationAction()
	{
		$roadNRJ = 0;
		$roadCO2 = 0;
		$navilandRailNRJ = 0;
		$navilandRailCO2 = 0;
		$navilandRoadNRJ = 0;
		$navilandRoadCO2 = 0;
		$roadNRJKPI = 0;
		$roadCO2KPI = 0;
		$navilandRailNRJKPI = 0;
		$navilandRailCO2KPI = 0;
		$navilandRoadNRJKPI = 0;
		$navilandRoadCO2KPI = 0;
		$gapNRJ = 0;
		$gapCO2 = 0;
		
		try
		{
			$hasXMLDescription = $this->_hasParam('xml_relation');
			if ($hasXMLDescription)
			{
				$relationXML = simplexml_load_string((string)$this->_getParam('xml_relation'));
				//echo 'Relation XML: '.$this->_getParam('xml_relation')."\n";
				//	Détermination de la masse réelle transportée
				$relationNetMass = 0;
				$nbUTI20 = (int)$relationXML->Freight['nb_20p'];
				$nbUTI30 = (int)$relationXML->Freight['nb_30p'];
				$nbUTI40 = (int)$relationXML->Freight['nb_40p'];
				//echo 'Nb UTI 20: '.$nbUTI20."\n";
				//echo 'Nb UTI 30: '.$nbUTI30."\n";
				//echo 'Nb UTI 40: '.$nbUTI40."\n";
				
				$containerParameters = $this->model->getContainerParameters();
				$containerMassKey = '';
				if ($relationXML->TripInformation['is_empty_trip'] == "true")
				{
					$containerMassKey = 'CONTAINER_TARE';
				}
				else
				{
					$containerMassKey = 'CONTAINER_GROSS_MASS';
				}
				//echo 'containerMassKey: '.$containerMassKey."\n";
				foreach ($containerParameters as $parameter)
				{
					switch ($parameter['CONTAINER_TYPE'])
					{
						case 'UTI20':
							$relationNetMass += $nbUTI20 * ((int)$parameter[$containerMassKey]);
							break;
						case 'UTI30':
							$relationNetMass += $nbUTI30 * ((int)$parameter[$containerMassKey]);
							break;
						case 'UTI40':
							$relationNetMass += $nbUTI40 * ((int)$parameter[$containerMassKey]);
							break;
					}
				}
				//echo 'Masse nette: '.$relationNetMass."\n";
				
				//	Détermination des gares origine et destination
				$idOriginStation = (int)$relationXML->Origin->Station['id'];
				$idDestinationStation = (int)$relationXML->Destination->Station['id'];
				$originStationParameters = $this->model->getStationParameters($idOriginStation);
				$destinationStationParameters = $this->model->getStationParameters($idDestinationStation);
				
				//	Récupération des données de	pre-acheminement
				if ($relationXML->Origin['has_pre_carriage'] == 1)
				{
					//echo 'Pre-carriage calculation'."\n";
					//	Récupération des paramètres de pre-acheminement
					$preCarriageParameters = $this->model->getPreCarriageParameters();
					$preCarriageParameter = $preCarriageParameters[0];
					//	Récupération du code postal origin
					$preCarriageZipCode = (string)$relationXML->Origin->PreCarriage['zip_code'];
					//	Récupération du code pays ISO
					$idCountry = (int)$relationXML->Origin->PreCarriage['id_country'];
					$countryCodeISO = $this->model->getCountryCodeISO($idCountry);
					$preCarriageCountryISO = $countryCodeISO[0]['COUNTRY_CODE_ISO'];
					
					if ($relationXML->TripInformation['is_way_back'] == "true")
					{
						$preCarriageParameter['EMPTY_RUN_FACTOR'] = 100;
					}
					
					$request = '';
					$request .= '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
					$request .= '<XML version="1">';
						$request .= '<Service id="1">';
							$request .= '<Data>';
								$request .= '<Split rail_traction="false" frontier="false" section="false"/>';
								$request .= '<Freight net_mass="'.$preCarriageParameter['GOODS_WEIGHT'].'" density="'.$preCarriageParameter['VOLUME_WEIGHT'].'" load_factor="'.$preCarriageParameter['LOAD_FACTOR'].'"/>';
								$request .= '<Route>';
									$request .= '<Section>';
										$request .= '<OriginNode type="ZIP">';
											$request .= '<ZIPDescription country_iso="'.$preCarriageCountryISO.'" zip_code="'.$preCarriageZipCode.'"/>';
										$request .= '</OriginNode>';
										$request .= '<DestinationNode type="UIC">';
											$request .= '<UICDescription country="'.$originStationParameters[0]['NETWORK_CODE'].'" station="'.$originStationParameters[0]['STATION_CODE'].'" country_iso="'.$originStationParameters[0]['COUNTRY_CODE_ISO'].'"/>';
										$request .= '</DestinationNode>';
										$request .= '<TransportMode mode="ROAD">';
											$request .= '<Road ferry_routing="'.$preCarriageParameter['FERRY_USE'].'" empty_run_factor="'.$preCarriageParameter['EMPTY_RUN_FACTOR'].'" lorry_class="'.$preCarriageParameter['LORRY_CLASS'].'" emission_class="'.$preCarriageParameter['EMISSION_CLASS'].'"/>';
										$request .= '</TransportMode>';
									$request .= '</Section>';
								$request .= '</Route>';
							$request .= '</Data>';
						$request .= '</Service>';
					$request .= '</XML>';
					
					//echo 'Request: '.$request."\n";
					
					$contenu = http_build_query(array('xml' => $request));
					$headers = $this->buildHeadersInformations(array(
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Content-Length' => strlen($contenu)
							)
					);
					$options = array(
						'http' => array(
							'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.8.1) Gecko/20061010 Firefox/2.0',
							'method' => 'POST',
							'content' => $contenu,
							'header' => $headers
						)
					);
					$contexte = stream_context_create($options);
					$result = file_get_contents($this->wsclienvAddress, false, $contexte);
					//echo 'Result'.$result."\n";
					$xmlResult = simplexml_load_string($result);
					
					if ($xmlResult->getName() == 'WSCliEnvError')
					{
						throw new Exception();
					}
					
					$navilandRoadNRJ += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->Energy['tank_to_wheel'] / $preCarriageParameter['GOODS_WEIGHT']) * $relationNetMass);
					$navilandRoadCO2 += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->CO2Eq['tank_to_wheel'] / $preCarriageParameter['GOODS_WEIGHT']) * $relationNetMass);
					$navilandRoadNRJ += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->Energy['well_to_tank'] / $preCarriageParameter['GOODS_WEIGHT']) * $relationNetMass);
					$navilandRoadCO2 += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->CO2Eq['well_to_tank'] / $preCarriageParameter['GOODS_WEIGHT']) * $relationNetMass);
					//echo 'navilandRoadNRJ'.$navilandRoadNRJ."\n";
					//echo 'navilandRoadCO2'.$navilandRoadCO2."\n";
				}

				//	Récupération des données de	post-acheminement
				if ($relationXML->Destination['has_post_carriage'] == 1)
				{
					//echo 'Post-carriage calculation'."\n";
					//	Récupération des paramètres de post-acheminement
					$postCarriageParameters = $this->model->getPostCarriageParameters();
					$postCarriageParameter = $postCarriageParameters[0];
					//	Récupération du code postal destination
					$postCarriageZipCode = (string)$relationXML->Destination->PostCarriage['zip_code'];
					//	Récupération du code pays ISO
					$idCountry = (int)$relationXML->Destination->PostCarriage['id_country'];
					$countryCodeISO = $this->model->getCountryCodeISO($idCountry);
					$postCarriageCountryISO = $countryCodeISO[0]['COUNTRY_CODE_ISO'];
					
					if ($relationXML->TripInformation['is_way_back'] == "true")
					{
						$postCarriageParameter['EMPTY_RUN_FACTOR'] = 100;
					}
					
					$request = '';
					$request .= '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
					$request .= '<XML version="1">';
						$request .= '<Service id="1">';
							$request .= '<Data>';
								$request .= '<Split rail_traction="false" frontier="false" section="false"/>';
								$request .= '<Freight net_mass="'.$postCarriageParameter['GOODS_WEIGHT'].'" density="'.$postCarriageParameter['VOLUME_WEIGHT'].'" load_factor="'.$postCarriageParameter['LOAD_FACTOR'].'"/>';
								$request .= '<Route>';
									$request .= '<Section>';
										$request .= '<OriginNode type="UIC">';
											$request .= '<UICDescription country="'.$destinationStationParameters[0]['NETWORK_CODE'].'" station="'.$destinationStationParameters[0]['STATION_CODE'].'" country_iso="'.$destinationStationParameters[0]['COUNTRY_CODE_ISO'].'"/>';
										$request .= '</OriginNode>';
										$request .= '<DestinationNode type="ZIP">';
											$request .= '<ZIPDescription country_iso="'.$postCarriageCountryISO.'" zip_code="'.$postCarriageZipCode.'"/>';
										$request .= '</DestinationNode>';
										$request .= '<TransportMode mode="ROAD">';
											$request .= '<Road ferry_routing="'.$postCarriageParameter['FERRY_USE'].'" empty_run_factor="'.$postCarriageParameter['EMPTY_RUN_FACTOR'].'" lorry_class="'.$postCarriageParameter['LORRY_CLASS'].'" emission_class="'.$postCarriageParameter['EMISSION_CLASS'].'"/>';
										$request .= '</TransportMode>';
									$request .= '</Section>';
								$request .= '</Route>';
							$request .= '</Data>';
						$request .= '</Service>';
					$request .= '</XML>';
					//echo 'Request'.$request."\n";
					
					$contenu = http_build_query(array('xml' => $request));
					$headers = $this->buildHeadersInformations(array(
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Content-Length' => strlen($contenu)
							)
					);
					$options = array(
						'http' => array(
							'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.8.1) Gecko/20061010 Firefox/2.0',
							'method' => 'POST',
							'content' => $contenu,
							'header' => $headers
						)
					);
					$contexte = stream_context_create($options);
					$result = file_get_contents($this->wsclienvAddress, false, $contexte);
					//echo 'Result'.$result."\n";
					$xmlResult = simplexml_load_string($result);
					
					if ($xmlResult->getName() == 'WSCliEnvError')
					{
						throw new Exception();
					}
					
					$navilandRoadNRJ += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->Energy['tank_to_wheel'] / $postCarriageParameter['GOODS_WEIGHT']) * $relationNetMass);
					$navilandRoadCO2 += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->CO2Eq['tank_to_wheel'] / $postCarriageParameter['GOODS_WEIGHT']) * $relationNetMass);
					$navilandRoadNRJ += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->Energy['well_to_tank'] / $postCarriageParameter['GOODS_WEIGHT']) * $relationNetMass);
					$navilandRoadCO2 += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->CO2Eq['well_to_tank'] / $postCarriageParameter['GOODS_WEIGHT']) * $relationNetMass);
					//echo 'Results'."\n";
					//echo 'navilandRoadNRJ: '.$navilandRoadNRJ."\n";
					//echo 'navilandRoadCO2: '.$navilandRoadCO2."\n";
				}
				
				//	Récupération des données de l'acheminement principal
				$mainCarriageParameters = $this->model->getMainCarriageParameters($idOriginStation, $idDestinationStation);
				foreach ($mainCarriageParameters as $segment)
				{
					//echo 'Main-carriage calculation'."\n";
					//print_r($segment);
					if ($relationXML->TripInformation['is_way_back'] == "true")
					{
						$segment['EMPTY_RUN_FACTOR'] = 100;
					}
					$request = '';
					$request .= '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
					$request .= '<XML version="1">';
						$request .= '<Service id="1">';
							$request .= '<Data>';
								$request .= '<Split rail_traction="false" frontier="false" section="false"/>';
								$request .= '<Freight net_mass="'.$segment['GOODS_WEIGHT'].'" density="'.$segment['VOLUME_WEIGHT'].'" load_factor="'.$segment['LOAD_FACTOR'].'"/>';
								$request .= '<Route>';
									$request .= '<Section>';
										$request .= '<OriginNode type="UIC">';
											$request .= '<UICDescription country="'.$segment['ORIGIN_NETWORK_CODE'].'" station="'.$segment['ORIGIN_STATION_CODE'].'" country_iso="'.$segment['ORIGIN_ISO_COUNTRY_CODE'].'"/>';
										$request .= '</OriginNode>';
										$request .= '<DestinationNode type="UIC">';
											$request .= '<UICDescription country="'.$segment['DESTINATION_NETWORK_CODE'].'" station="'.$segment['DESTINATION_STATION_CODE'].'" country_iso="'.$segment['DESTINATION_ISO_COUNTRY_CODE'].'"/>';
										$request .= '</DestinationNode>';
										$request .= '<TransportMode mode="RAIL">';
											$request .= '<Rail ferry_routing="'.$segment['FERRY_USE'].'" rail_traction="'.$segment['DRIVE_CLASS'].'" empty_run_factor="'.$segment['EMPTY_RUN_FACTOR'].'" train_weight="'.$segment['TRAIN_WEIGHT'].'"/>';
										$request .= '</TransportMode>';
									$request .= '</Section>';
								$request .= '</Route>';
							$request .= '</Data>';
						$request .= '</Service>';
					$request .= '</XML>';
					//echo 'Request: '.$request."\n";
					
					$contenu = http_build_query(array('xml' => $request));
					$headers = $this->buildHeadersInformations(array(
							'Content-Type' => 'application/x-www-form-urlencoded',
							'Content-Length' => strlen($contenu)
							)
					);
					$options = array(
						'http' => array(
							'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.8.1) Gecko/20061010 Firefox/2.0',
							'method' => 'POST',
							'content' => $contenu,
							'header' => $headers
						)
					);
					$contexte = stream_context_create($options);
					$result = file_get_contents($this->wsclienvAddress, false, $contexte);
					//echo 'Result: '.$result."\n";
					$xmlResult = simplexml_load_string($result);
					
					if ($xmlResult->getName() == 'WSCliEnvError')
					{
						throw new Exception();
					}
					
					$navilandRailNRJ += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->Energy['tank_to_wheel'] / $segment['GOODS_WEIGHT']) * $relationNetMass);
					$navilandRailCO2 += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->CO2Eq['tank_to_wheel'] / $segment['GOODS_WEIGHT']) * $relationNetMass);
					$navilandRailNRJ += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->Energy['well_to_tank'] / $segment['GOODS_WEIGHT']) * $relationNetMass);
					$navilandRailCO2 += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->CO2Eq['well_to_tank'] / $segment['GOODS_WEIGHT']) * $relationNetMass);
					//echo 'Section result:'."\n";
					//echo 'navilandRailNRJ: '.$navilandRailNRJ."\n";
					//echo 'navilandRailCO2: '.$navilandRailCO2."\n";
				}
				
				//	Récupération des données pour le scénario routier
				//echo 'Road scenario calculation'."\n";
				if ($relationXML->TripInformation['is_way_back'] == "true")
				{
					$roadParameters[0]['EMPTY_RUN_FACTOR'] = 100;
				}
				$roadParameters = $this->model->getRoadScenarioParameters();
				$request = '';
				$request .= '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
				$request .= '<XML version="1">';
					$request .= '<Service id="1">';
						$request .= '<Data>';
							$request .= '<Split rail_traction="false" frontier="false" section="false"/>';
							$request .= '<Freight net_mass="'.$roadParameters[0]['GOODS_WEIGHT'].'" density="'.$roadParameters[0]['VOLUME_WEIGHT'].'" load_factor="'.$roadParameters[0]['LOAD_FACTOR'].'"/>';
							$request .= '<Route>';
								$request .= '<Section>';
									if ($relationXML->Origin['has_pre_carriage'] == 1)
									{
										$request .= '<OriginNode type="ZIP">';
											$request .= '<ZIPDescription country_iso="'.$preCarriageCountryISO.'" zip_code="'.$preCarriageZipCode.'"/>';
										$request .= '</OriginNode>';
									}
									else
									{
										$request .= '<OriginNode type="UIC">';
											$request .= '<UICDescription country="'.$originStationParameters[0]['NETWORK_CODE'].'" station="'.$originStationParameters[0]['STATION_CODE'].'" country_iso="'.$originStationParameters[0]['COUNTRY_CODE_ISO'].'"/>';
										$request .= '</OriginNode>';
									}
									
									if ($relationXML->Destination['has_post_carriage'] == 1)
									{
										$request .= '<DestinationNode type="ZIP">';
											$request .= '<ZIPDescription country_iso="'.$postCarriageCountryISO.'" zip_code="'.$postCarriageZipCode.'"/>';
										$request .= '</DestinationNode>';
									}
									else
									{
										$request .= '<DestinationNode type="UIC">';
											$request .= '<UICDescription country="'.$destinationStationParameters[0]['NETWORK_CODE'].'" station="'.$destinationStationParameters[0]['STATION_CODE'].'" country_iso="'.$destinationStationParameters[0]['COUNTRY_CODE_ISO'].'"/>';
										$request .= '</DestinationNode>';
									}
									$request .= '<TransportMode mode="ROAD">';
										$request .= '<Road ferry_routing="'.$roadParameters[0]['FERRY_USE'].'" empty_run_factor="'.$roadParameters[0]['EMPTY_RUN_FACTOR'].'" lorry_class="'.$roadParameters[0]['LORRY_CLASS'].'" emission_class="'.$roadParameters[0]['EMISSION_CLASS'].'"/>';
									$request .= '</TransportMode>';
								$request .= '</Section>';
							$request .= '</Route>';
						$request .= '</Data>';
					$request .= '</Service>';
				$request .= '</XML>';
				
				//echo 'Request: '.$request."\n";
				
				$contenu = http_build_query(array('xml' => $request));
				$headers = $this->buildHeadersInformations(array(
						'Content-Type' => 'application/x-www-form-urlencoded',
						'Content-Length' => strlen($contenu)
						)
				);
				$options = array(
					'http' => array(
						'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; fr; rv:1.8.1) Gecko/20061010 Firefox/2.0',
						'method' => 'POST',
						'content' => $contenu,
						'header' => $headers
					)
				);
				$contexte = stream_context_create($options);
				$result = file_get_contents($this->wsclienvAddress, false, $contexte);
				//echo 'Result: '.$result."\n";
				$xmlResult = simplexml_load_string($result);
				
				if ($xmlResult->getName() == 'WSCliEnvError')
				{
					throw new Exception();
				}
				
				$roadNRJ += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->Energy['tank_to_wheel'] / $roadParameters[0]['GOODS_WEIGHT']) * $relationNetMass);
				$roadCO2 += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->CO2Eq['tank_to_wheel'] / $roadParameters[0]['GOODS_WEIGHT']) * $relationNetMass);
				$roadNRJ += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->Energy['well_to_tank'] / $roadParameters[0]['GOODS_WEIGHT']) * $relationNetMass);
				$roadCO2 += (((float)$xmlResult->EnvironmentalInformation->Transport->Global->CO2Eq['well_to_tank'] / $roadParameters[0]['GOODS_WEIGHT']) * $relationNetMass);
				//echo 'Road scenario results'."\n";
				//echo 'roadNRJ: '.$roadNRJ."\n";
				//echo 'roadCO2: '.$roadCO2."\n";
				
				//	Calcul des KPI
				$nbUTI = $nbUTI20 + $nbUTI30 * 1.5 + $nbUTI40 * 2;
				$roadNRJKPI = $roadNRJ / $nbUTI;
				$roadCO2KPI = $roadCO2 / $nbUTI;
				$navilandRailNRJKPI = $navilandRailNRJ / $nbUTI;
				$navilandRailCO2KPI = $navilandRailCO2 / $nbUTI;
				$navilandRoadNRJKPI = $navilandRoadNRJ / $nbUTI;
				$navilandRoadCO2KPI = $navilandRoadCO2 / $nbUTI;
				//echo 'KPI Results:'."\n";
				//echo 'nbUTI: '.$nbUTI."\n";
				//echo 'roadNRJKPI: '.$roadNRJKPI."\n";
				//echo 'roadCO2KPI: '.$roadCO2KPI."\n";
				//echo 'navilandRailNRJKPI: '.$navilandRailNRJKPI."\n";
				//echo 'navilandRailCO2KPI: '.$navilandRailCO2KPI."\n";
				//echo 'navilandRoadNRJKPI: '.$navilandRoadNRJKPI."\n";
				//echo 'navilandRoadCO2KPI: '.$navilandRoadCO2KPI."\n";
				
				//	Calcul du gap
				$gapNRJ = $roadNRJ - ($navilandRoadNRJ + $navilandRailNRJ);
				$gapCO2 = $roadCO2 - ($navilandRoadCO2 + $navilandRailCO2);
				//echo 'Gap results'."\n";
				//echo 'gapNRJ: '.$gapNRJ."\n";
				//echo 'gapCO2: '.$gapCO2."\n";
				

				//	Modification des unités
				$roadNRJ /= 1000;
				$navilandRoadNRJ /= 1000;
				$navilandRailNRJ /= 1000;
				$roadNRJKPI /= 1000;
				$navilandRoadNRJKPI /= 1000;
				$navilandRailNRJKPI /= 1000;
				$gapNRJ /= 1000;
				
				//	Préparation des données JSON
				$this->json = array(
					'state' => 'OK',
					'RoadGlobal' => array(
						'nrj' => $roadNRJ,
						'co2' => $roadCO2
					),
					'NavilandGlobal' => array(
						'nrj_prepost' => $navilandRoadNRJ,
						'co2_prepost' => $navilandRoadCO2,
						'nrj_main' => $navilandRailNRJ,
						'co2_main' => $navilandRailCO2
					),
					'RoadKPIUTI' => array(
						'nrj' => $roadNRJKPI,
						'co2' => $roadCO2KPI
					),
					'NavilandKPIUTI' => array(
						'nrj_prepost' => $navilandRoadNRJKPI,
						'co2_prepost' => $navilandRoadCO2KPI,
						'nrj_main' => $navilandRailNRJKPI,
						'co2_main' => $navilandRailCO2KPI
					),
					'Gap' => array(
						'nrj' => $gapNRJ,
						'co2' => $gapCO2
					)
				);
			}
		}
		catch (Exception $e)
		{
			$this->json = array('state' => 'KO');
		}
		$this->_helper->json($this->json);
	}
	
	private function prepareHTMLExportPDF($relationsXML, $isHTML2PDF)
	{
		$urlAssets = 'http://'.$_SERVER['HTTP_HOST'].'/medias/images/ecocalculateur/';
		$htmlPDF = '';
		$nbPages = $relationsXML['nb_relation'] + 1;
		$iPage = 1;
		$globalRoadNRJ = (float)$relationsXML->EnvironmentalInformation->Energy['road'];
		$globalRoadNRJKPI = (float)$relationsXML->EnvironmentalInformation->EnergyKPI['road'];
		$globalNavilandRailNRJ = (float)$relationsXML->EnvironmentalInformation->Energy['naviland_rail'];
		$globalNavilandRailNRJKPI = (float)$relationsXML->EnvironmentalInformation->EnergyKPI['naviland_rail'];
		$globalNavilandRoadNRJ = (float)$relationsXML->EnvironmentalInformation->Energy['naviland_road'];
		$globalNavilandRoadNRJKPI = (float)$relationsXML->EnvironmentalInformation->EnergyKPI['naviland_road'];
		$globalRoadCO2 = (float)$relationsXML->EnvironmentalInformation->CO2['road'];
		$globalRoadCO2KPI = (float)$relationsXML->EnvironmentalInformation->CO2KPI['road'];
		$globalNavilandRailCO2 = (float)$relationsXML->EnvironmentalInformation->CO2['naviland_rail'];
		$globalNavilandRailCO2KPI = (float)$relationsXML->EnvironmentalInformation->CO2KPI['naviland_rail'];
		$globalNavilandRoadCO2 = (float)$relationsXML->EnvironmentalInformation->CO2['naviland_road'];
		$globalNavilandRoadCO2KPI = (float)$relationsXML->EnvironmentalInformation->CO2KPI['naviland_road'];
		
		$dymanicImages = '';
		if ($isHTML2PDF)
			$dymanicImages = 'IMAGE_DYNAMIQUE:';
		
		foreach ($relationsXML->Relation as $relationXML)
		{
			$htmlPDF .= '<page style="font-size:8pt;">';
				$htmlPDF .= '<div style="background-color:#ffffff;padding-left:30px;">';
					//	Image Header
					$htmlPDF .= '<img src="'.$urlAssets.'header.jpg" width="700"/><br/>';
					//	Date
					$htmlPDF .= '<div style="text-align:right;width:700px;"><span style="font-size:8pt;color:#8080AD">Date de Création : '.$this->getDate_FR_FR().'</span></div>';
					//	Texte
					$htmlPDF .= '<div style="font-size:8pt;font-style:italic;color:#990099;width:700px;margin-top:15px;margin-bottom:25px;text-align:justify;">&nbsp;L’éco-calculateur Naviland Cargo s’appuie sur l’outil EcoTransIT World (Ecological Transport Information Tool), qui calcule et compare les impacts environnementaux des chaînes multimodales et mondiales. Sur la base de cet outil, Naviland Cargo y intègre les règles métiers propres à ses méthodes de production (règles métier, architecture de transport).</div>';
					
					//	Description Relation
					$trajet = '';
					if ($relationXML->Origin['has_pre_carriage'] == 1)
					{
						$trajet .= $relationXML->Origin->PreCarriage['country_label'].' '.$relationXML->Origin->PreCarriage['zip_code'].' >>> ';
					}
					$trajet .= $relationXML->Origin->Station['label'].' >>> ';
					$trajet .= $relationXML->Destination->Station['label'];
					if ($relationXML->Destination['has_post_carriage'] == 1)
					{
						$trajet .= ' >>> '.$relationXML->Destination->PostCarriage['country_label'].' '.$relationXML->Destination->PostCarriage['zip_code'];
					}
					$htmlPDF .= '<div style="background-color:#92D050;color:#FFFFFF;width:700px;height:22px;font-size:12pt;"><div style="margin-top:3px;margin-left:15px;">Relation : '.$trajet.'</div></div>';
					
					//	Marchandise
					$htmlPDF .= '<Table style="width:700px;border-size:2px;border-color:white;border-style:solid;">';
						$htmlPDF .= '<tr style="background-color:#DEE7D1;border-size:2px;border-color:white;border-style:solid;">';
							$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:227px;height:20px;font-size:10pt;color:#333333;vertical-align:middle;">Nb de 20\' : '.$relationXML->GoodsInformation['nb_20'].'</td>';
							$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:227px;height:20px;font-size:10pt;color:#333333;vertical-align:middle;">Nb de 30\' : '.$relationXML->GoodsInformation['nb_30'].'</td>';
							$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:227px;height:20px;font-size:10pt;color:#333333;vertical-align:middle;">Nb de 40\' : '.$relationXML->GoodsInformation['nb_40'].'</td>';
						$htmlPDF .= '</tr>';
					$htmlPDF .= '</Table>';
					
					//	Détails
					$detailEmpty = '';
					$detailAR = '';
					if ($relationXML->TripInformation['is_empty'] == 1)
						$detailEmpty = 'Conteneurs chargés';
					else
						$detailEmpty = 'Conteneurs vides';
					if ($relationXML->TripInformation['is_wayback'] == 1)
						$detailAR = 'Trajet aller-retour';
					else
						$detailAR = 'Trajet aller';
					$htmlPDF .= '<Table style="width:700px;border-size:2px;border-color:white;border-style:solid;">';
						$htmlPDF .= '<tr style="background-color:#DEE7D1;border-size:2px;border-color:white;border-style:solid;">';
							$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:344px;height:20px;font-size:10pt;color:#333333;vertical-align:middle;">'.$detailAR.'</td>';
							$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:343px;height:20px;font-size:10pt;color:#333333;vertical-align:middle;">'.$detailEmpty.'</td>';
						$htmlPDF .= '</tr>';
					$htmlPDF .= '</Table>';
					
					//	Resultat energie
					$gainNRJ = $relationXML->EnvironmentalInformation->Energy['road'] - $relationXML->EnvironmentalInformation->Energy['naviland_rail'] - $relationXML->EnvironmentalInformation->Energy['naviland_road'];
					$htmlPDF .= '<div style="background-color:#92D050;color:#FFFFFF;width:700px;height:22px;font-size:12pt;margin-top:15px;"><div style="margin-top:3px;margin-left:15px;">Consommation énergétique</div></div>';
					$htmlPDF .= '<Table style="width:700px;border-size:2px;border-color:white;border-style:solid;">';
						$htmlPDF .= '<tr style="background-color:#DEE7D1;border-size:2px;border-color:white;border-style:solid;">';
							$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:693px;height:20px;font-size:10pt;color:#333333;vertical-align:middle;">Cette relation représente un gain de '.$gainNRJ.' GJ par rapport à un scénario routier.</td>';
						$htmlPDF .= '</tr>';
					$htmlPDF .= '</Table>';
					$htmlPDF .= '<Table style="width:700px;border-size:2px;border-color:white;border-style:solid;">';
						$htmlPDF .= '<tr style="background-color:#DEE7D1;border-size:2px;border-color:white;border-style:solid;">';
							$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:344px;height:230px;;font-size:10pt;color:#333333;vertical-align:middle;text-align:center;">';
								$htmlPDF .= '<img src="'.$dymanicImages.$urlAssets.'chart.php?Road='.$relationXML->EnvironmentalInformation->EnergyKPI['road'].'&NavilandRail='.$relationXML->EnvironmentalInformation->EnergyKPI['naviland_rail'].'&NavilandRoad='.$relationXML->EnvironmentalInformation->EnergyKPI['naviland_road'].'"/>';
								$htmlPDF .= '<br/><span style="font-size:15pt;color:#333333;font-variant:small-caps;">Par UTI (GJ)</span>';
							$htmlPDF .= '</td>';
							$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:343px;height:230px;;font-size:10pt;color:#333333;vertical-align:middle;text-align:center;">';
								$htmlPDF .= '<img src="'.$dymanicImages.$urlAssets.'chart.php?Road='.$relationXML->EnvironmentalInformation->Energy['road'].'&NavilandRail='.$relationXML->EnvironmentalInformation->Energy['naviland_rail'].'&NavilandRoad='.$relationXML->EnvironmentalInformation->Energy['naviland_road'].'"/>';
								$htmlPDF .= '<br/><span style="font-size:15pt;color:#333333;font-variant:small-caps;">Global (GJ)</span>';
							$htmlPDF .= '</td>';
						$htmlPDF .= '</tr>';
					$htmlPDF .= '</Table>';
					
					//	Resultat CO2
					$gainCO2 = $relationXML->EnvironmentalInformation->CO2['road'] - $relationXML->EnvironmentalInformation->CO2['naviland_rail'] - $relationXML->EnvironmentalInformation->CO2['naviland_road'];
					$htmlPDF .= '<div style="background-color:#92D050;color:#FFFFFF;width:700px;height:22px;font-size:12pt;margin-top:15px;"><div style="margin-top:3px;margin-left:15px;">Émissions de CO<sub>2</sub> équivalent</div></div>';
					$htmlPDF .= '<Table style="width:700px;border-size:2px;border-color:white;border-style:solid;">';
						$htmlPDF .= '<tr style="background-color:#DEE7D1;border-size:2px;border-color:white;border-style:solid;">';
							$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:693px;height:20px;font-size:10pt;color:#333333;vertical-align:middle;">Cette relation représente un gain de '.$gainCO2.' t CO<sub>2 éq.</sub> par rapport à un scénario routier.</td>';
						$htmlPDF .= '</tr>';
					$htmlPDF .= '</Table>';
					$htmlPDF .= '<Table style="width:700px;border-size:2px;border-color:white;border-style:solid;">';
						$htmlPDF .= '<tr style="background-color:#DEE7D1;border-size:2px;border-color:white;border-style:solid;">';
							$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:344px;height:230px;;font-size:10pt;color:#333333;vertical-align:middle;text-align:center;">';
								$htmlPDF .= '<img src="'.$dymanicImages.$urlAssets.'chart.php?Road='.$relationXML->EnvironmentalInformation->CO2KPI['road'].'&NavilandRail='.$relationXML->EnvironmentalInformation->CO2KPI['naviland_rail'].'&NavilandRoad='.$relationXML->EnvironmentalInformation->CO2KPI['naviland_road'].'"/>';
								$htmlPDF .= '<br/><span style="font-size:15pt;color:#333333;font-variant:small-caps;">Par UTI (t CO<sub>2 éq.</sub>)</span>';
							$htmlPDF .= '</td>';
							$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:343px;height:230px;;font-size:10pt;color:#333333;vertical-align:middle;text-align:center;">';
								$htmlPDF .= '<img src="'.$dymanicImages.$urlAssets.'chart.php?Road='.$relationXML->EnvironmentalInformation->CO2['road'].'&NavilandRail='.$relationXML->EnvironmentalInformation->CO2['naviland_rail'].'&NavilandRoad='.$relationXML->EnvironmentalInformation->CO2['naviland_road'].'"/>';
								$htmlPDF .= '<br/><span style="font-size:15pt;color:#333333;font-variant:small-caps;">Global (t CO<sub>2 éq.</sub>)</span>';
							$htmlPDF .= '</td>';
						$htmlPDF .= '</tr>';
					$htmlPDF .= '</Table>';
					
					//	Légende
					$htmlPDF .= '<div style="background-color:#92D050;color:#FFFFFF;width:700px;height:22px;font-size:12pt;margin-top:15px;"><div style="margin-top:3px;margin-left:15px;">Légende</div></div>';
					$htmlPDF .= '<Table style="width:700px;border-size:2px;border-color:white;border-style:solid;">';
						$htmlPDF .= '<tr style="background-color:#DEE7D1;border-size:2px;border-color:white;border-style:solid;">';
							$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:693px;height:20px;font-size:10pt;color:#333333;vertical-align:middle;text-align:center;"><img src="'.$urlAssets.'legende.jpg"/></td>';
						$htmlPDF .= '</tr>';
					$htmlPDF .= '</Table>';
					
					//	Footer
					$htmlPDF .= '<Table style="margin-top:25px;">';
						$htmlPDF .= '<tr>';
							$htmlPDF .= '<td>';
								$htmlPDF .= '<span style="font-size:12pt;font-weight:bold;color:#808080;">Pour obtenir plus d’informations :</span>';
								$htmlPDF .= '<br/><span style="font-size:10pt;color:#808080;">Website & Information : </span><span style="color:#0000ff;text-decoration:underline;">www.naviland-cargo.com</span>';
								$htmlPDF .= '<br/><span style="font-size:10pt;color:#808080;">Méthodologie : </span><span style="color:#0000ff;text-decoration:underline;">www.naviland-cargo.com/developpementdurable/methodo.pdf</span>';
								$htmlPDF .= '<br/><span style="font-size:10pt;color:#808080;">Contact : </span><span style="color:#0000ff;text-decoration:underline;">mndoye@naviland-cargo.com</span>';
								$htmlPDF .= '<br/><br/><img src="'.$urlAssets.'footer_line.jpg" width="500"/><br/>';
								$htmlPDF .= '<span style="font-size:9pt;color:#909090;">Page : '.$iPage.'|'.$nbPages.'</span>';
								$htmlPDF .= '<span style="font-size:9pt;color:#909090;margin-left:100px;	">Ces informations sont non-contractuelles</span>';
							$htmlPDF .= '</td>';
							$htmlPDF .= '<td>';
								$htmlPDF .= '<div style="margin-left:100px;margin-top:25px;"><img src='.$urlAssets.'afaq.jpg"/></div>';
							$htmlPDF .= '</td>';
						$htmlPDF .= '</tr>';
					$htmlPDF .= '</Table>';
					
				$htmlPDF .= '</div>';
			$htmlPDF .='</page>';
			$iPage++;
		}
		
		//	Page Globale
		$htmlPDF .= '<page style="font-size:8pt;">';
			$htmlPDF .= '<div style="background-color:#ffffff;padding-left:30px;">';
				//	Image Header
				$htmlPDF .= '<img src="'.$urlAssets.'header.jpg" width="700"/><br/>';
				//	Date
				$htmlPDF .= '<div style="text-align:right;width:700px;"><span style="font-size:8pt;color:#8080AD">Date de Création : '.$this->getDate_FR_FR().'</span></div>';
				//	Texte
				$htmlPDF .= '<div style="font-size:8pt;font-style:italic;color:#990099;width:700px;margin-top:15px;margin-bottom:25px;text-align:justify;">&nbsp;L’éco-calculateur Naviland Cargo s’appuie sur l’outil EcoTransIT World (Ecological Transport Information Tool), qui calcule et compare les impacts environnementaux des chaînes multimodales et mondiales. Sur la base de cet outil, Naviland Cargo y intègre les règles métiers propres à ses méthodes de production (règles métier, architecture de transport).</div>';
				
				//	Resultat energie
				$gainNRJ = $globalRoadNRJ - $globalNavilandRailNRJ - $globalNavilandRoadNRJ;
				$htmlPDF .= '<div style="background-color:#92D050;color:#FFFFFF;width:700px;height:22px;font-size:12pt;margin-top:15px;"><div style="margin-top:3px;margin-left:15px;">Consommation énergétique globale</div></div>';
				$htmlPDF .= '<Table style="width:700px;border-size:2px;border-color:white;border-style:solid;">';
					$htmlPDF .= '<tr style="background-color:#DEE7D1;border-size:2px;border-color:white;border-style:solid;">';
						$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:693px;height:20px;font-size:10pt;color:#333333;vertical-align:middle;">L\'ensemble des relations représentent un gain de '.$gainNRJ.' GJ par rapport à un scénario routier.</td>';
					$htmlPDF .= '</tr>';
				$htmlPDF .= '</Table>';
				$htmlPDF .= '<Table style="width:700px;border-size:2px;border-color:white;border-style:solid;">';
					$htmlPDF .= '<tr style="background-color:#DEE7D1;border-size:2px;border-color:white;border-style:solid;">';
						$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:344px;height:230px;;font-size:10pt;color:#333333;vertical-align:middle;text-align:center;">';
							$htmlPDF .= '<img src="'.$dymanicImages.$urlAssets.'chart.php?Road='.$globalRoadNRJKPI.'&NavilandRail='.$globalNavilandRailNRJKPI.'&NavilandRoad='.$globalNavilandRoadNRJKPI.'"/>';
							$htmlPDF .= '<br/><span style="font-size:15pt;color:#333333;font-variant:small-caps;">Par UTI (GJ)</span>';
						$htmlPDF .= '</td>';
						$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:343px;height:230px;;font-size:10pt;color:#333333;vertical-align:middle;text-align:center;">';
							$htmlPDF .= '<img src="'.$dymanicImages.$urlAssets.'chart.php?Road='.$globalRoadNRJ.'&NavilandRail='.$globalNavilandRailNRJ.'&NavilandRoad='.$globalNavilandRoadNRJ.'"/>';
							$htmlPDF .= '<br/><span style="font-size:15pt;color:#333333;font-variant:small-caps;">Global (GJ)</span>';
						$htmlPDF .= '</td>';
					$htmlPDF .= '</tr>';
				$htmlPDF .= '</Table>';
				
				//	Resultat CO2
				$gainCO2 = $globalRoadCO2 - $globalNavilandRailCO2 - $globalNavilandRoadCO2;
				$htmlPDF .= '<div style="background-color:#92D050;color:#FFFFFF;width:700px;height:22px;font-size:12pt;margin-top:15px;"><div style="margin-top:3px;margin-left:15px;">Émissions de CO<sub>2</sub> équivalent</div> globale</div>';
				$htmlPDF .= '<Table style="width:700px;border-size:2px;border-color:white;border-style:solid;">';
					$htmlPDF .= '<tr style="background-color:#DEE7D1;border-size:2px;border-color:white;border-style:solid;">';
						$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:693px;height:20px;font-size:10pt;color:#333333;vertical-align:middle;">L\'ensemble des relations représentent un gain de '.$gainCO2.' t CO<sub>2 éq.</sub> par rapport à un scénario routier.</td>';
					$htmlPDF .= '</tr>';
				$htmlPDF .= '</Table>';
				$htmlPDF .= '<Table style="width:700px;border-size:2px;border-color:white;border-style:solid;">';
					$htmlPDF .= '<tr style="background-color:#DEE7D1;border-size:2px;border-color:white;border-style:solid;">';
						$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:344px;height:230px;;font-size:10pt;color:#333333;vertical-align:middle;text-align:center;">';
							$htmlPDF .= '<img src="'.$dymanicImages.$urlAssets.'chart.php?Road='.$globalRoadCO2KPI.'&NavilandRail='.$globalNavilandRailCO2KPI.'&NavilandRoad='.$globalNavilandRoadCO2KPI.'"/>';
							$htmlPDF .= '<br/><span style="font-size:15pt;color:#333333;font-variant:small-caps;">Par UTI (t CO<sub>2 éq.</sub>)</span>';
						$htmlPDF .= '</td>';
						$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:343px;height:230px;;font-size:10pt;color:#333333;vertical-align:middle;text-align:center;">';
							$htmlPDF .= '<img src="'.$dymanicImages.$urlAssets.'chart.php?Road='.$globalRoadCO2.'&NavilandRail='.$globalNavilandRailCO2.'&NavilandRoad='.$globalNavilandRoadCO2.'"/>';
							$htmlPDF .= '<br/><span style="font-size:15pt;color:#333333;font-variant:small-caps;">Global (t CO<sub>2 éq.</sub>)</span>';
						$htmlPDF .= '</td>';
					$htmlPDF .= '</tr>';
				$htmlPDF .= '</Table>';
				
				//	Légende
				$htmlPDF .= '<div style="background-color:#92D050;color:#FFFFFF;width:700px;height:22px;font-size:12pt;margin-top:15px;"><div style="margin-top:3px;margin-left:15px;">Légende</div></div>';
				$htmlPDF .= '<Table style="width:700px;border-size:2px;border-color:white;border-style:solid;">';
					$htmlPDF .= '<tr style="background-color:#DEE7D1;border-size:2px;border-color:white;border-style:solid;">';
						$htmlPDF .= '<td style="border-size:2px;border-color:white;border-style:solid;width:693px;height:20px;font-size:10pt;color:#333333;vertical-align:middle;text-align:center;"><img src="'.$urlAssets.'legende.jpg"/></td>';
					$htmlPDF .= '</tr>';
				$htmlPDF .= '</Table>';
				
				//	Footer
				$htmlPDF .= '<Table style="margin-top:25px;">';
					$htmlPDF .= '<tr>';
						$htmlPDF .= '<td>';
							$htmlPDF .= '<span style="font-size:12pt;font-weight:bold;color:#808080;">Pour obtenir plus d’informations :</span>';
							$htmlPDF .= '<br/><span style="font-size:10pt;color:#808080;">Website & Information : </span><span style="color:#0000ff;text-decoration:underline;">www.naviland-cargo.com</span>';
							$htmlPDF .= '<br/><span style="font-size:10pt;color:#808080;">Méthodologie : </span><span style="color:#0000ff;text-decoration:underline;">www.naviland-cargo.com/developpementdurable/methodo.pdf</span>';
							$htmlPDF .= '<br/><span style="font-size:10pt;color:#808080;">Contact : </span><span style="color:#0000ff;text-decoration:underline;">mndoye@naviland-cargo.com</span>';
							$htmlPDF .= '<br/><br/><img src="'.$urlAssets.'footer_line.jpg" width="500"/><br/>';
							$htmlPDF .= '<span style="font-size:9pt;color:#909090;">Page : '.$iPage.'|'.$nbPages.'</span>';
							$htmlPDF .= '<span style="font-size:9pt;color:#909090;margin-left:100px;	">Ces informations sont non-contractuelles</span>';
						$htmlPDF .= '</td>';
						$htmlPDF .= '<td>';
							$htmlPDF .= '<div style="margin-left:100px;margin-top:25px;"><img src="'.$urlAssets.'afaq.jpg"/></div>';
						$htmlPDF .= '</td>';
					$htmlPDF .= '</tr>';
				$htmlPDF .= '</Table>';
				
			$htmlPDF .= '</div>';
		$htmlPDF .='</page>';
		return $htmlPDF;
	}
	
	public function printPdfExportAction()
	{
		require_once('lib/EcoCalculateur/pdf/html2pdf.class.php');
		$this->_helper->layout()->disableLayout(); 
		$this->_helper->viewRenderer->setNoRender(true);
		try
		{
			if ($this->_hasParam('xml_relations'))
			{
				$relationsXML = simplexml_load_string($this->_getParam('xml_relations'));
				$html2pdf = new HTML2PDF('P','A4','fr');
				$html2pdf->setTestIsImage(false);
				$html2pdf->WriteHTML($this->prepareHTMLExportPDF($relationsXML, true));
				$html2pdf->Output('Naviland_EcoCalculateur.pdf');
			}
			else
			{
				throw new Exception();
			}
			
		}
		catch (Exception $e)
		{
		}
	}
	
	public function getPdfExportAction()
	{
		require_once('lib/EcoCalculateur/pdf/html2pdf.class.php');
		$this->_helper->layout()->disableLayout(); 
		$this->_helper->viewRenderer->setNoRender(true);
		try
		{
			if ($this->_hasParam('xml_relations'))
			{
				$relationsXML = simplexml_load_string($this->_getParam('xml_relations'));
				$html2pdf = new HTML2PDF('P','A4','fr');
				$html2pdf->setTestIsImage(false);
				$html2pdf->WriteHTML($this->prepareHTMLExportPDF($relationsXML, true));
				$html2pdf->Output('Naviland_EcoCalculateur.pdf');
			}
			else
			{
				throw new Exception();
			}
			
		}
		catch (Exception $e)
		{
		}
	}
	
	private function buildHeadersInformations($headers)
	{
		$headers_brut = '';

		foreach ($headers as $nom => $valeur)
		{
			$headers_brut .= $nom . ': ' . $valeur . "\r\n";
		}

		return $headers_brut;
	}
	
	private function getDate_FR_FR()
	{
		$date = '';
		$date .= date('d');
		$date .= ' ';
		switch (intval(date('n')))
		{
			case 1:
				$date .= 'Janvier';
				break;
			case 2:
				$date .= 'Février';
				break;
			case 3:
				$date .= 'Mars';
				break;
			case 4:
				$date .= 'Avril';
				break;
			case 5:
				$date .= 'Mai';
				break;
			case 6:
				$date .= 'Juin';
				break;
			case 7:
				$date .= 'Juillet';
				break;
			case 8:
				$date .= 'Août';
				break;
			case 9:
				$date .= 'Septembre';
				break;
			case 10:
				$date .= 'Octobre';
				break;
			case 11:
				$date .= 'Novembre';
				break;
			case 12:
				$date .= 'Décembre';
				break;
		}
		$date .= ' ';
		$date .= date('Y');
		return $date;
	}
}