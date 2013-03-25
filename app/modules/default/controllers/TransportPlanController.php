<?php
class TransportPlanController extends Zend_Controller_Action
{
	private $model = null;
	private $json = null;
	private $currentLanguage = '';
	
	public function init()
	{
		$this->model = new Model_DbTable_TransportPlan();
		$this->currentLanguage = $_COOKIE['locale'];
	}
	
	public function getOriginStationsAction()
	{
		$idLeafDestination = null;
		$hasLeafDestination = $this->_hasParam('id_leaf_destination');
		$stations = null;
		
		if ($hasLeafDestination)
		{
			$idLeafDestination = (int)$this->_getParam('id_leaf_destination');
			$stations = $this->model->getOriginStationsRelated($idLeafDestination);
		}
		else
		{
			$stations = $this->model->getOriginStations();
		}
		
		$this->json = Array();
		foreach ($stations as $station)
		{
			$this->json[] = Array('id' => $station['ID'], 'label' => $station['LABEL']);
		}
		
		$this->_helper->json(Array('OriginLeaves' => $this->json));
	}
	
	public function getDestinationStationsAction()
	{
		$idLeafOrigin = null;
		$hasLeafOrigin = $this->_hasParam('id_leaf_origin');
		$stations = null;
		
		if ($hasLeafOrigin)
		{
			$idLeafOrigin = (int)$this->_getParam('id_leaf_origin');
			$stations = $this->model->getDestinationStationsRelated($idLeafOrigin);
		}
		else
		{
			$stations = $this->model->getDestinationStations();
		}
		
		$this->json = Array();
		foreach ($stations as $station)
		{
			$this->json[] = Array('id' => $station['ID'], 'label' => $station['LABEL']);
		}
		
		$this->_helper->json(Array('DestinationLeaves' => $this->json));
	}
	
	public function getRelationMetaDataAction()
	{
		$this->json = Array();
		
		//	Récupération des identifiants de gare origine/destination
		$hasParam = $this->_hasParam('id_leaf_origin') && $this->_hasParam('id_leaf_destination');
		if ($hasParam)
		{
			$idOriginStation = (int)$this->_getParam('id_leaf_origin');
			$idDestinationStation = (int)$this->_getParam('id_leaf_destination');
			
			//	Récupération de la relation
			$relations = $this->model->getRelation($idOriginStation, $idDestinationStation);
			if (count($relations) > 0)
			{
				$relation = $relations[0];
				
				//	Récupération de la relation retour
				$hasWaybackRelation = false;
				$waybackRelation = null;
				$waybackRelations = $this->model->getRelation($idDestinationStation, $idOriginStation);
				
				if (count($waybackRelations) > 0)
				{
					$hasWaybackRelation = true;
					$waybackRelation = $waybackRelations[0];
				}
				
				//	Récupération des méta-données
				$metadatas = $this->model->getRelationMetaData($relation['ID_META_DATA']);
				if (count($metadatas) > 0)
				{
					$metadata = $metadatas[0];
					
					//	Récupération des données environnementales
					$envdatas = $this->model->getRelationEnvironmentalData($relation['ID_ENVIRONMENTAL_DATA']);
					if (count($envdatas) > 0)
					{
						$envdata = $envdatas[0];
						
						//	Récupération du planning aller
						$outwardSchedule = $this->model->getScheduleData($relation['ID_META_DATA']);
						//	Récupération du planning retour
						
						if ($hasWaybackRelation)
						{
							$waybackSchedule = $this->model->getScheduleData($waybackRelation['ID_META_DATA']);
						}
						
						//	Fabrication des données JSON
						$this->json[] = Array('return_code' => 'OK');
						switch ($this->currentLanguage)
						{
							case 'fr_FR':
								$this->json[] = Array('ClosingBookingOrigin' => $metadata['CLOSING_BOOK_ORIGIN_FR_FR']);
								$this->json[] = Array('ClosingBookingDestination' => $metadata['CLOSING_BOOK_DESTINATION_FR_FR']);
								$this->json[] = Array('ClosingDocumentationOrigin' => $metadata['CLOSING_DOCUMENTATION_ORIGIN_FR_FR']);
								$this->json[] = Array('ClosingDocumentationDestination' => $metadata['CLOSING_DOCUMENTATION_DESTINATION_FR_FR']);
								$this->json[] = Array('MADOrigin' => $metadata['MAD_ORIGIN_FR_FR']);
								$this->json[] = Array('ClosingOrigin' => $metadata['CLOSING_ORIGIN_FR_FR']);
								break;
							case 'en_US':
								$this->json[] = Array('ClosingBookingOrigin' => $metadata['CLOSING_BOOK_ORIGIN_UK_EN']);
								$this->json[] = Array('ClosingBookingDestination' => $metadata['CLOSING_BOOK_DESTINATION_UK_EN']);
								$this->json[] = Array('ClosingDocumentationOrigin' => $metadata['CLOSING_DOCUMENTATION_ORIGIN_UK_EN']);
								$this->json[] = Array('ClosingDocumentationDestination' => $metadata['CLOSING_DOCUMENTATION_DESTINATION_UK_EN']);
								$this->json[] = Array('MADOrigin' => $metadata['MAD_ORIGIN_UK_EN']);
								$this->json[] = Array('ClosingOrigin' => $metadata['CLOSING_ORIGIN_UK_EN']);
								break;
							default:
								$this->json[] = Array('ClosingBookingOrigin' => $metadata['CLOSING_BOOK_ORIGIN_UK_EN']);
								$this->json[] = Array('ClosingBookingDestination' => $metadata['CLOSING_BOOK_DESTINATION_UK_EN']);
								$this->json[] = Array('ClosingDocumentationOrigin' => $metadata['CLOSING_DOCUMENTATION_ORIGIN_UK_EN']);
								$this->json[] = Array('ClosingDocumentationDestination' => $metadata['CLOSING_DOCUMENTATION_DESTINATION_UK_EN']);
								$this->json[] = Array('MADOrigin' => $metadata['MAD_ORIGIN_UK_EN']);
								$this->json[] = Array('ClosingOrigin' => $metadata['CLOSING_ORIGIN_UK_EN']);
								break;
						}
						
						$this->json[] = Array('EnvironmentalInformation' => Array(
								'co2_saving' => $envdata['GAP_CO2'],
								'nrj_saving' => $envdata['GAP_NRJ']
							)
						);
						
						$outwardTripSchedule = Array();
						foreach ($outwardSchedule as $trip)
						{
							$outwardTripSchedule[] = Array(
								'HRL' => Array(
									'day' => $trip['HLR_DAY'],
									'hour' => $trip['HLR_HOUR'],
									'minute' => $trip['HLR_MINUTE']
								),
								'MAD' => Array(
									'day' => $trip['MAD_DAY'],
									'hour' => $trip['MAD_HOUR'],
									'minute' => $trip['MAD_MINUTE']
								),
								'delay' => $trip['DELAY']
							);
						}
						$this->json[] = Array('OutwardTripSchedule' => $outwardTripSchedule);
						
						$waybackTripSchedule = Array();
						foreach ($waybackSchedule as $trip)
						{
							$waybackTripSchedule[] = Array(
								'HRL' => Array(
									'day' => $trip['HLR_DAY'],
									'hour' => $trip['HLR_HOUR'],
									'minute' => $trip['HLR_MINUTE']
								),
								'MAD' => Array(
									'day' => $trip['MAD_DAY'],
									'hour' => $trip['MAD_HOUR'],
									'minute' => $trip['MAD_MINUTE']
								),
								'delay' => $trip['DELAY']
							);
						}
						$this->json[] = Array('WaybackTripSchedule' => $waybackTripSchedule);
					}
					else
					{
						$this->json[] = Array('return_code' => 'NO_RELATION_FOUND');
					}
				}
				else
				{
					$this->json[] = Array('return_code' => 'NO_RELATION_FOUND');
				}
			}
			else
			{
				$this->json[] = Array('return_code' => 'NO_RELATION_FOUND');
			}
		}
		else
		{
			$this->json[] = Array('return_code' => 'NO_RELATION_FOUND');
		}
		
		$this->_helper->json($this->json);
	}
}