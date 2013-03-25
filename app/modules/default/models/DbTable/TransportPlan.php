<?php
class Model_DbTable_TransportPlan extends Zend_Db_Table_Abstract
{
	/**
	* table name
	* @var string
	*/
	protected $_name = 'RELATION';

	/**
	* Name of the primary key
	* @var string
	*/
	protected $_primary = 'RLT_PK';
	
	static public $DIRECTION_OUTWARD_TRIP = 1;
	static public $DIRECTION_WAYBACK_TRIP = 2;
	
	/**
	 * Setup database adapter
	 *
	 * @return void
	 */
	protected function _setupDatabaseAdapter()
	{
		$this->_db = Zend_Registry::get('db')->getDb('naviland_webservice');
		parent::_setupDatabaseAdapter();
	}
	
	public function getOriginStations()
	{
		$sql = '';
		$sql .= 'select STT_PK as "ID", STT_LABEL as "LABEL" from naviland_webservice.STATION stt';
		$sql .= ' inner join naviland_webservice.RELATION rlt';
		$sql .= ' on rlt.RLT_ORIGIN_STT_FK = stt.STT_PK';
		$sql .= ' group by stt.STT_PK, stt.STT_LABEL';
		
		return $this->_db->query($sql)->fetchAll();
	}

	public function getOriginStationsRelated($idDestinationStation)
	{
		$sql = '';
		$sql .= 'select STT_PK as "ID", STT_LABEL as "LABEL" from naviland_webservice.STATION stt';
		$sql .= ' inner join naviland_webservice.RELATION rlt';
		$sql .= ' on rlt.RLT_ORIGIN_STT_FK = stt.STT_PK';
		$sql .= ' where rlt.RLT_DESTINATION_STT_FK = ' . $idDestinationStation;
		$sql .= ' group by stt.STT_PK, stt.STT_LABEL';
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getDestinationStations()
	{
		$sql = '';
		$sql .= 'select STT_PK as "ID", STT_LABEL as "LABEL" from naviland_webservice.STATION stt';
		$sql .= ' inner join naviland_webservice.RELATION rlt';
		$sql .= ' on rlt.RLT_DESTINATION_STT_FK = stt.STT_PK';
		$sql .= ' group by stt.STT_PK, stt.STT_LABEL';
		
		return $this->_db->query($sql)->fetchAll();
	}

	public function getDestinationStationsRelated($idOriginStation)
	{
		$sql = '';
		$sql .= 'select STT_PK as "ID", STT_LABEL as "LABEL" from naviland_webservice.STATION stt';
		$sql .= ' inner join naviland_webservice.RELATION rlt';
		$sql .= ' on rlt.RLT_DESTINATION_STT_FK = stt.STT_PK';
		$sql .= ' where rlt.RLT_ORIGIN_STT_FK = ' . $idOriginStation;
		$sql .= ' group by stt.STT_PK, stt.STT_LABEL';
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getRelation($idOriginStation, $idDestinationStation)
	{
		$sql = '';
		$sql .= 'select RLT_ENV_FK as "ID_ENVIRONMENTAL_DATA", RLT_RLTMDT_FK as "ID_META_DATA"';
		$sql .= ' from naviland_webservice.RELATION';
		$sql .= ' where RLT_ORIGIN_STT_FK = '.$idOriginStation;
		$sql .= ' and RLT_DESTINATION_STT_FK = '.$idDestinationStation;
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getRelationMetaData($idMetaData)
	{
		$sql = '';
		$sql .= 'select RLTMDT_CLOSING_BOOK_ORIGIN_FR_FR as "CLOSING_BOOK_ORIGIN_FR_FR", RLTMDT_CLOSING_BOOK_ORIGIN_UK_EN as "CLOSING_BOOK_ORIGIN_UK_EN",';
		$sql .= ' RLTMDT_CLOSING_BOOK_DESTINATION_FR_FR as "CLOSING_BOOK_DESTINATION_FR_FR", RLTMDT_CLOSING_BOOK_DESTINATION_UK_EN as "CLOSING_BOOK_DESTINATION_UK_EN",';
		$sql .= ' RLTMDT_CLOSING_DOCUMENTATION_ORIGIN_FR_FR as "CLOSING_DOCUMENTATION_ORIGIN_FR_FR", RLTMDT_CLOSING_DOCUMENTATION_ORIGIN_UK_EN as "CLOSING_DOCUMENTATION_ORIGIN_UK_EN",';
		$sql .= ' RLTMDT_CLOSING_DOCUMENTATION_DESTINATION_FR_FR as "CLOSING_DOCUMENTATION_DESTINATION_FR_FR", RLTMDT_CLOSING_DOCUMENTATION_DESTINATION_UK_EN as "CLOSING_DOCUMENTATION_DESTINATION_UK_EN",';
		$sql .= ' RLTMDT_MAD_ORIGIN_FR_FR as "MAD_ORIGIN_FR_FR", RLTMDT_MAD_ORIGIN_UK_EN as "MAD_ORIGIN_UK_EN",';
		$sql .= ' RLTMDT_CLOSING_ORIGIN_FR_FR as "CLOSING_ORIGIN_FR_FR", RLTMDT_CLOSING_ORIGIN_UK_EN as "CLOSING_ORIGIN_UK_EN"';
		$sql .= ' from naviland_webservice.RELATION_META_DATA';
		$sql .= ' where RLTMDT_PK = '.$idMetaData;
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getRelationEnvironmentalData($idEnvData)
	{
		$sql = '';
		$sql .= 'select ENV_GAP_CO2 as "GAP_CO2", ENV_GAP_NRJ as "GAP_NRJ"';
		$sql .= ' from naviland_webservice.ENVIRONMENTAL_DATA';
		$sql .= ' where ENV_PK = ' . $idEnvData;
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getScheduleData($idMetaData)
	{
		$sql = '';
		$sql .= 'select TSD_HLR_DAY as "HLR_DAY", TSD_HLR_HOUR as "HLR_HOUR", TSD_HLR_MINUTE as "HLR_MINUTE",';
		$sql .= ' TSD_MAD_DAY as "MAD_DAY", TSD_MAD_HOUR as "MAD_HOUR", TSD_MAD_MINUTE as "MAD_MINUTE",';
		$sql .= ' TSD_DELAY as "DELAY"';
		$sql .= ' from naviland_webservice.TRIP_SCHEDULE';
		$sql .= ' where TSD_RLTMDT_FK = ' . $idMetaData;
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getEccDestinationStation($idStation = null, $idCountry = null, $zipCode = null)
	{
		$hasStation = true;
		$hasPostCarriage = true;
		
		if ($idCountry == null || $zipCode == null)
		{
			$hasPostCarriage = false;
		}
		if ($idStation == null)
		{
			$hasStation = false;
		}
		
		if ($hasStation)
		{
			if ($hasPostCarriage)
			{
				return $this->getEccDestinationStationRelatedStationPostCarriage($idStation, $idCountry, $zipCode);
			}
			else
			{
				return $this->getEccDestinationStationRelatedStation($idStation);
			}
		}
		else
		{
			if ($hasPostCarriage)
			{
				return $this->getEccDestinationStationRelatedPostCarriage($idCountry, $zipCode);
			}
			else
			{
				return $this->getEccDestinationStationNoRelated();
			}
		}
	}
	
	private function getEccDestinationStationRelatedStationPostCarriage($idStation, $idCountry, $zipCode)
	{
		$result = null;
		
		$sql = '';
		$sql .= 'select stt.STT_PK as "STATION_ID", stt.STT_LABEL as "STATION_LABEL", zip.ZIPSTTCNC_IS_DEFAULT as "IS_DEFAULT"';
		$sql .= ' from naviland_webservice.STATION stt';
		$sql .= ' inner join naviland_webservice.ZIP_STATION_CONNECTION zip';
		$sql .= ' on stt.STT_PK = zip.ZIPSTTCNC_STT_FK';
		$sql .= ' inner join naviland_webservice.RELATION rlt';
		$sql .= ' on stt.STT_PK = rlt.RLT_DESTINATION_STT_FK';
		$sql .= ' where rlt.RLT_ORIGIN_STT_FK = '.$idStation;
		$sql .= ' and zip.ZIPSTTCNC_CTR_FK = '.$idCountry;
		$sql .= ' and zip.ZIPSTTCNC_ZIP_CODE = "'.$zipCode.'"';
		$sql .= ' group by stt.STT_PK, stt.STT_LABEL, zip.ZIPSTTCNC_IS_DEFAULT';
		
		$result = $this->_db->query($sql)->fetchAll();
		
		if (count($result) <= 0)
		{
			$sql = '';
			$sql .= 'select stt.STT_PK as "STATION_ID", stt.STT_LABEL as "STATION_LABEL", ctr.CTRSTTCNC_IS_DEFAULT as "IS_DEFAULT"';
			$sql .= ' from naviland_webservice.STATION stt';
			$sql .= ' inner join naviland_webservice.COUNTRY_STATION_CONNECTION ctr';
			$sql .= ' on stt.STT_PK = ctr.CTRSTTCNC_STT_FK';
			$sql .= ' inner join naviland_webservice.RELATION rlt';
			$sql .= ' on stt.STT_PK = rlt.RLT_DESTINATION_STT_FK';
			$sql .= ' where rlt.RLT_ORIGIN_STT_FK = '.$idStation;
			$sql .= ' and ctr.CTRSTTCNC_CTR_FK = '.$idCountry;
			$sql .= ' group by stt.STT_PK, stt.STT_LABEL, ctr.CTRSTTCNC_IS_DEFAULT';
			
			$result = $this->_db->query($sql)->fetchAll();
		}
		
		return $result;
	}
	
	private function getEccDestinationStationRelatedStation($idStation)
	{
		$sql = '';
		$sql .= 'select stt.STT_PK as "STATION_ID", stt.STT_LABEL as "STATION_LABEL", 0 as "IS_DEFAULT"';
		$sql .= ' from naviland_webservice.STATION stt';
		$sql .= ' inner join naviland_webservice.RELATION rlt';
		$sql .= ' on stt.STT_PK = rlt.RLT_DESTINATION_STT_FK';
		$sql .= ' where rlt.RLT_ORIGIN_STT_FK = '.$idStation;
		$sql .= ' group by stt.STT_PK, stt.STT_LABEL';
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	private function getEccDestinationStationRelatedPostCarriage($idCountry, $zipCode)
	{
		$result = null;
		$sql = '';
		$sql .= 'select stt.STT_PK as "STATION_ID", stt.STT_LABEL as "STATION_LABEL", zip.ZIPSTTCNC_IS_DEFAULT as "IS_DEFAULT"';
		$sql .= ' from naviland_webservice.STATION stt';
		$sql .= ' inner join naviland_webservice.ZIP_STATION_CONNECTION zip';
		$sql .= ' on stt.STT_PK = zip.ZIPSTTCNC_STT_FK';
		$sql .= ' inner join naviland_webservice.RELATION rlt';
		$sql .= ' on stt.STT_PK = rlt.RLT_DESTINATION_STT_FK';
		$sql .= ' where zip.ZIPSTTCNC_CTR_FK = '.$idCountry;
		$sql .= ' and zip.ZIPSTTCNC_ZIP_CODE = "'.$zipCode.'"';
		$sql .= ' group by stt.STT_PK, stt.STT_LABEL, zip.ZIPSTTCNC_IS_DEFAULT';
		
		$result = $this->_db->query($sql)->fetchAll();
		
		if (count($result) <= 0)
		{
			$sql = '';
			$sql .= 'select stt.STT_PK as "STATION_ID", stt.STT_LABEL as "STATION_LABEL", ctr.CTRSTTCNC_IS_DEFAULT as "IS_DEFAULT"';
			$sql .= ' from naviland_webservice.STATION stt';
			$sql .= ' inner join naviland_webservice.COUNTRY_STATION_CONNECTION ctr';
			$sql .= ' on stt.STT_PK = ctr.CTRSTTCNC_STT_FK';
			$sql .= ' inner join naviland_webservice.RELATION rlt';
			$sql .= ' on stt.STT_PK = rlt.RLT_DESTINATION_STT_FK';
			$sql .= ' where ctr.CTRSTTCNC_CTR_FK = '.$idCountry;
			$sql .= ' group by stt.STT_PK, stt.STT_LABEL, ctr.CTRSTTCNC_IS_DEFAULT';
			
			$result = $this->_adapter->fetchAll($sql);
		}
		
		return $result;
	}
	
	private function getEccDestinationStationNoRelated()
	{
		$sql = '';
		$sql .= 'select stt.STT_PK as "STATION_ID", stt.STT_LABEL as "STATION_LABEL", 0 as "IS_DEFAULT"';
		$sql .= ' from naviland_webservice.STATION stt';
		$sql .= ' inner join naviland_webservice.RELATION rlt';
		$sql .= ' on stt.STT_PK = rlt.RLT_DESTINATION_STT_FK';
		$sql .= ' group by stt.STT_PK, stt.STT_LABEL';
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getEccOriginStation($idStation = null, $idCountry = null, $zipCode = null)
	{
		$hasStation = true;
		$hasPreCarriage = true;
		
		if ($idCountry == null || $zipCode == null)
		{
			$hasPreCarriage = false;
		}
		if ($idStation == null)
		{
			$hasStation = false;
		}
		
		if ($hasStation)
		{
			if ($hasPostCarriage)
			{
				return $this->getEccOriginStationRelatedStationPostCarriage($idStation, $idCountry, $zipCode);
			}
			else
			{
				return $this->getEccOriginStationRelatedStation($idStation);
			}
		}
		else
		{
			if ($hasPreCarriage)
			{
				return $this->getEccOriginStationRelatedPostCarriage($idCountry, $zipCode);
			}
			else
			{
				return $this->getEccOriginStationNoRelated();
			}
		}
	}
	
	private function getEccOriginStationRelatedStationPostCarriage($idStation, $idCountry, $zipCode)
	{
		$result = null;
		
		$sql = '';
		$sql .= 'select stt.STT_PK as "STATION_ID", stt.STT_LABEL as "STATION_LABEL", zip.ZIPSTTCNC_IS_DEFAULT as "IS_DEFAULT"';
		$sql .= ' from naviland_webservice.STATION stt';
		$sql .= ' inner join naviland_webservice.ZIP_STATION_CONNECTION zip';
		$sql .= ' on stt.STT_PK = zip.ZIPSTTCNC_STT_FK';
		$sql .= ' inner join naviland_webservice.RELATION rlt';
		$sql .= ' on stt.STT_PK = rlt.RLT_ORIGIN_STT_FK';
		$sql .= ' where rlt.RLT_DESTINATION_STT_FK = '.$idStation;
		$sql .= ' and zip.ZIPSTTCNC_CTR_FK = '.$idCountry;
		$sql .= ' and zip.ZIPSTTCNC_ZIP_CODE = "'.$zipCode.'"';
		$sql .= ' group by stt.STT_PK, stt.STT_LABEL, zip.ZIPSTTCNC_IS_DEFAULT';
		
		$result = $this->_db->query($sql)->fetchAll();
		
		if (count($result) <= 0)
		{
			$sql = '';
			$sql .= 'select stt.STT_PK as "STATION_ID", stt.STT_LABEL as "STATION_LABEL", ctr.CTRSTTCNC_IS_DEFAULT as "IS_DEFAULT"';
			$sql .= ' from naviland_webservice.STATION stt';
			$sql .= ' inner join naviland_webservice.COUNTRY_STATION_CONNECTION ctr';
			$sql .= ' on stt.STT_PK = ctr.CTRSTTCNC_STT_FK';
			$sql .= ' inner join naviland_webservice.RELATION rlt';
			$sql .= ' on stt.STT_PK = rlt.RLT_ORIGIN_STT_FK';
			$sql .= ' where rlt.RLT_DESTINATION_STT_FK = '.$idStation;
			$sql .= ' and ctr.CTRSTTCNC_CTR_FK = '.$idCountry;
			$sql .= ' group by stt.STT_PK, stt.STT_LABEL, ctr.CTRSTTCNC_IS_DEFAULT';
			
			$result = $this->_db->query($sql)->fetchAll();
		}
		
		return $result;
	}
	
	private function getEccOriginStationRelatedStation($idStation)
	{
		$sql = '';
		$sql .= 'select stt.STT_PK as "STATION_ID", stt.STT_LABEL as "STATION_LABEL", 0 as "IS_DEFAULT"';
		$sql .= ' from naviland_webservice.STATION stt';
		$sql .= ' inner join naviland_webservice.RELATION rlt';
		$sql .= ' on stt.STT_PK = rlt.RLT_ORIGIN_STT_FK';
		$sql .= ' where rlt.RLT_DESTINATION_STT_FK = '.$idStation;
		$sql .= ' group by stt.STT_PK, stt.STT_LABEL';
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	private function getEccOriginStationRelatedPostCarriage($idCountry, $zipCode)
	{
		$result = null;
		
		$sql = '';
		$sql .= 'select stt.STT_PK as "STATION_ID", stt.STT_LABEL as "STATION_LABEL", zip.ZIPSTTCNC_IS_DEFAULT as "IS_DEFAULT"';
		$sql .= ' from naviland_webservice.STATION stt';
		$sql .= ' inner join naviland_webservice.ZIP_STATION_CONNECTION zip';
		$sql .= ' on stt.STT_PK = zip.ZIPSTTCNC_STT_FK';
		$sql .= ' inner join naviland_webservice.RELATION rlt';
		$sql .= ' on stt.STT_PK = rlt.RLT_ORIGIN_STT_FK';
		$sql .= ' where zip.ZIPSTTCNC_CTR_FK = '.$idCountry;
		$sql .= ' and zip.ZIPSTTCNC_ZIP_CODE = "'.$zipCode.'"';
		$sql .= ' group by stt.STT_PK, stt.STT_LABEL, zip.ZIPSTTCNC_IS_DEFAULT';
		
		$result = $this->_db->query($sql)->fetchAll();
		
		if (count($result) <= 0)
		{
			$sql = '';
			$sql .= 'select stt.STT_PK as "STATION_ID", stt.STT_LABEL as "STATION_LABEL", ctr.CTRSTTCNC_IS_DEFAULT as "IS_DEFAULT"';
			$sql .= ' from naviland_webservice.STATION stt';
			$sql .= ' inner join naviland_webservice.COUNTRY_STATION_CONNECTION ctr';
			$sql .= ' on stt.STT_PK = ctr.CTRSTTCNC_STT_FK';
			$sql .= ' inner join naviland_webservice.RELATION rlt';
			$sql .= ' on stt.STT_PK = rlt.RLT_ORIGIN_STT_FK';
			$sql .= ' where ctr.CTRSTTCNC_CTR_FK = '.$idCountry;
			$sql .= ' group by stt.STT_PK, stt.STT_LABEL, ctr.CTRSTTCNC_IS_DEFAULT';
			
			$result = $this->_db->query($sql)->fetchAll();
		}
		
		return $result;
	}
	
	private function getEccOriginStationNoRelated()
	{
		$sql = '';
		$sql .= 'select stt.STT_PK as "STATION_ID", stt.STT_LABEL as "STATION_LABEL", 0 as "IS_DEFAULT"';
		$sql .= ' from naviland_webservice.STATION stt';
		$sql .= ' inner join naviland_webservice.RELATION rlt';
		$sql .= ' on stt.STT_PK = rlt.RLT_ORIGIN_STT_FK';
		$sql .= ' group by stt.STT_PK, stt.STT_LABEL';
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getPreCarriageCountry()
	{
		$sql = '';
		$sql .= 'select ctr.CTR_PK as "COUNTRY_ID", ctr.CTR_LBL_FR_FR as "COUNTRY_LABEL_FR_FR", ctr.CTR_LBL_UK_EN as "COUNTRY_LABEL_UK_EN"';
		$sql .= ' from naviland_webservice.RELATION rlt';
		$sql .= ' inner join naviland_webservice.STATION stt';
		$sql .= ' on stt.STT_PK = rlt.RLT_ORIGIN_STT_FK';
		$sql .= ' inner join naviland_webservice.COUNTRY_STATION_CONNECTION ctrcnc';
		$sql .= ' on ctrcnc.CTRSTTCNC_STT_FK = stt.STT_PK';
		$sql .= ' inner join naviland_webservice.COUNTRY ctr';
		$sql .= ' on ctr.CTR_PK = ctrcnc.CTRSTTCNC_CTR_FK';
		$sql .= ' union';
		$sql .= ' select ctr.CTR_PK as "COUNTRY_ID", ctr.CTR_LBL_FR_FR as "COUNTRY_LABEL_FR_FR", ctr.CTR_LBL_UK_EN as "COUNTRY_LABEL_UK_EN"';
		$sql .= ' from naviland_webservice.RELATION rlt';
		$sql .= ' inner join naviland_webservice.STATION stt';
		$sql .= ' on stt.STT_PK = rlt.RLT_ORIGIN_STT_FK';
		$sql .= ' inner join naviland_webservice.ZIP_STATION_CONNECTION zipcnc';
		$sql .= ' on zipcnc.ZIPSTTCNC_STT_FK = stt.STT_PK';
		$sql .= ' inner join naviland_webservice.COUNTRY ctr';
		$sql .= ' on ctr.CTR_PK = zipcnc.ZIPSTTCNC_CTR_FK';
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getPostCarriageCountry()
	{
		$sql = '';
		$sql .= 'select ctr.CTR_PK as "COUNTRY_ID", ctr.CTR_LBL_FR_FR as "COUNTRY_LABEL_FR_FR", ctr.CTR_LBL_UK_EN as "COUNTRY_LABEL_UK_EN"';
		$sql .= ' from naviland_webservice.RELATION rlt';
		$sql .= ' inner join naviland_webservice.STATION stt';
		$sql .= ' on stt.STT_PK = rlt.RLT_DESTINATION_STT_FK';
		$sql .= ' inner join naviland_webservice.COUNTRY_STATION_CONNECTION ctrcnc';
		$sql .= ' on ctrcnc.CTRSTTCNC_STT_FK = stt.STT_PK';
		$sql .= ' inner join naviland_webservice.COUNTRY ctr';
		$sql .= ' on ctr.CTR_PK = ctrcnc.CTRSTTCNC_CTR_FK';
		$sql .= ' union';
		$sql .= ' select ctr.CTR_PK as "COUNTRY_ID", ctr.CTR_LBL_FR_FR as "COUNTRY_LABEL_FR_FR", ctr.CTR_LBL_UK_EN as "COUNTRY_LABEL_UK_EN"';
		$sql .= ' from naviland_webservice.RELATION rlt';
		$sql .= ' inner join naviland_webservice.STATION stt';
		$sql .= ' on stt.STT_PK = rlt.RLT_DESTINATION_STT_FK';
		$sql .= ' inner join naviland_webservice.ZIP_STATION_CONNECTION zipcnc';
		$sql .= ' on zipcnc.ZIPSTTCNC_STT_FK = stt.STT_PK';
		$sql .= ' inner join naviland_webservice.COUNTRY ctr';
		$sql .= ' on ctr.CTR_PK = zipcnc.ZIPSTTCNC_CTR_FK';
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getContainerParameters()
	{
		$sql = '';
		$sql .= 'select CTN_PK as "CONTAINER_TYPE", CTN_GROSS_MASS as "CONTAINER_GROSS_MASS", CTN_TARE as "CONTAINER_TARE"';
		$sql .= ' from naviland_webservice.CONTAINER';
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getPreCarriageParameters()
	{
		$sql = '';
		$sql .= 'select road.ECTWROAD_FERRY_USE as "FERRY_USE", road.ECTWROAD_EMISSION_CLASS as "EMISSION_CLASS", road.ECTWROAD_LORRY_CLASS as "LORRY_CLASS",';
		$sql .= ' road.ECTWROAD_EMPTY_RUN_FACTOR as "EMPTY_RUN_FACTOR", road.ECTWROAD_VOLUME_WEIGHT as "VOLUME_WEIGHT", road.ECTWROAD_LOAD_FACTOR as "LOAD_FACTOR",';
		$sql .= ' road.ECTWROAD_GOODS_WEIGHT as "GOODS_WEIGHT"';
		$sql .= ' from naviland_webservice.ROAD_MODELISATION ppc';
		$sql .= ' inner join naviland_webservice.ECTW_ROAD_MODEL road';
		$sql .= ' on road.ECTWROAD_PK = ppc.ROADMDL_ECTWROAD_FK';
		$sql .= ' where ppc.ROADMDL_CRGTYP_FK = "PRE"';
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getPostCarriageParameters()
	{
		$sql = '';
		$sql .= 'select road.ECTWROAD_FERRY_USE as "FERRY_USE", road.ECTWROAD_EMISSION_CLASS as "EMISSION_CLASS", road.ECTWROAD_LORRY_CLASS as "LORRY_CLASS",';
		$sql .= ' road.ECTWROAD_EMPTY_RUN_FACTOR as "EMPTY_RUN_FACTOR", road.ECTWROAD_VOLUME_WEIGHT as "VOLUME_WEIGHT", road.ECTWROAD_LOAD_FACTOR as "LOAD_FACTOR",';
		$sql .= ' road.ECTWROAD_GOODS_WEIGHT as "GOODS_WEIGHT"';
		$sql .= ' from naviland_webservice.ROAD_MODELISATION ppc';
		$sql .= ' inner join naviland_webservice.ECTW_ROAD_MODEL road';
		$sql .= ' on road.ECTWROAD_PK = ppc.ROADMDL_ECTWROAD_FK';
		$sql .= ' where ppc.ROADMDL_CRGTYP_FK = "POS"';
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getRoadScenarioParameters()
	{
		$sql = '';
		$sql .= 'select road.ECTWROAD_FERRY_USE as "FERRY_USE", road.ECTWROAD_EMISSION_CLASS as "EMISSION_CLASS", road.ECTWROAD_LORRY_CLASS as "LORRY_CLASS",';
		$sql .= ' road.ECTWROAD_EMPTY_RUN_FACTOR as "EMPTY_RUN_FACTOR", road.ECTWROAD_VOLUME_WEIGHT as "VOLUME_WEIGHT", road.ECTWROAD_LOAD_FACTOR as "LOAD_FACTOR",';
		$sql .= ' road.ECTWROAD_GOODS_WEIGHT as "GOODS_WEIGHT"';
		$sql .= ' from naviland_webservice.ROAD_MODELISATION ppc';
		$sql .= ' inner join naviland_webservice.ECTW_ROAD_MODEL road';
		$sql .= ' on road.ECTWROAD_PK = ppc.ROADMDL_ECTWROAD_FK';
		$sql .= ' where ppc.ROADMDL_CRGTYP_FK = "RSC"';
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getCountryCodeISO($idCountry)
	{
		$sql = '';
		$sql .= 'select CTR_ISO_COUNTRY_CODE as "COUNTRY_CODE_ISO"';
		$sql .= ' from naviland_webservice.COUNTRY';
		$sql .= ' where CTR_PK = '.$idCountry;
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getStationParameters($idStation)
	{
		$sql = '';
		$sql .= 'select STT_ISO_COUNTRY_CODE as "COUNTRY_CODE_ISO", STT_NETWORK_CODE as "NETWORK_CODE", STT_STATION_CODE as "STATION_CODE"';
		$sql .= ' from naviland_webservice.STATION';
		$sql .= ' where STT_PK = '.$idStation;
		
		return $this->_db->query($sql)->fetchAll();
	}
	
	public function getMainCarriageParameters($idOrigin, $idDestination)
	{
		$sql = '';
		$sql .= 'select sttorigin.STT_ISO_COUNTRY_CODE as "ORIGIN_ISO_COUNTRY_CODE", sttorigin.STT_NETWORK_CODE as "ORIGIN_NETWORK_CODE", sttorigin.STT_STATION_CODE as "ORIGIN_STATION_CODE",';
		$sql .= ' sttdestination.STT_ISO_COUNTRY_CODE as "DESTINATION_ISO_COUNTRY_CODE", sttdestination.STT_NETWORK_CODE as "DESTINATION_NETWORK_CODE", sttdestination.STT_STATION_CODE as "DESTINATION_STATION_CODE",';
		$sql .= ' ectwrail.ECTWRAIL_DRIVE_CLASS as "DRIVE_CLASS", ectwrail.ECTWRAIL_FERRY_USE as "FERRY_USE", ectwrail.ECTWRAIL_EMPTY_RUN_FACTOR as "EMPTY_RUN_FACTOR", ectwrail.ECTWRAIL_TRAIN_WEIGHT as "TRAIN_WEIGHT", ectwrail.ECTWRAIL_VOLUME_WEIGHT as "VOLUME_WEIGHT", ectwrail.ECTWRAIL_LOAD_FACTOR as "LOAD_FACTOR", ectwrail.ECTWRAIL_GOODS_WEIGHT as "GOODS_WEIGHT"';
		$sql .= ' from naviland_webservice.RELATION rlt';
		$sql .= ' inner join naviland_webservice.RELATION_SEGMENT rltsgm';
		$sql .= ' on rltsgm.RLTSGM_RLT_FK_PK = rlt.RLT_PK';
		$sql .= ' inner join naviland_webservice.SEGMENT sgm';
		$sql .= ' on sgm.SGM_PK = rltsgm.RLTSGM_SGM_FK_PK';
		$sql .= ' inner join naviland_webservice.STATION sttorigin';
		$sql .= ' on sttorigin.STT_PK = sgm.SGM_ORIGIN_STT_FK';
		$sql .= ' inner join naviland_webservice.STATION sttdestination';
		$sql .= ' on sttdestination.STT_PK = sgm.SGM_DESTINATION_STT_FK';
		$sql .= ' inner join naviland_webservice.ECTW_RAIL_MODEL ectwrail';
		$sql .= ' on ectwrail.ECTWRAIL_PK = sgm.SGM_ECTWRAIL_FK';
		$sql .= ' where rlt.RLT_ORIGIN_STT_FK = '.$idOrigin;
		$sql .= ' and rlt.RLT_DESTINATION_STT_FK = '.$idDestination;
		
		return $this->_db->query($sql)->fetchAll();
	}
}