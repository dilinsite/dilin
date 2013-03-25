<?php
//	Bouchon
class EcoCalculateurBouchonController extends Zend_Controller_Action
{
	public function init()
	{
	}
	
	public function getMainCarriageDestinationStationAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$hasRelatedStation = $this->_hasParam('related_origin_station_id');
		$hasRelatedCountry = $this->_hasParam('related_id_country');
		$hasRelatedZip = $this->_hasParam('related_zip_code');
		
		if ($hasRelatedStation && $hasRelatedCountry && $hasRelatedZip)
		{
			echo '{';
				echo 'state:"OK",';
				echo 'stations:';
				echo '[';
					echo '{id:0, label:"--Plateforme--", is_default:0},';
					echo '{id:1, label:"Paris", is_default:0}';
				echo ']';
			echo '}';
		}
		else if ($hasRelatedStation)
		{
			echo '{';
				echo 'state:"OK",';
				echo 'stations:';
				echo '[';
					echo '{id:0, label:"--Plateforme--", is_default:0},';
					echo '{id:1, label:"Paris", is_default:0}';
				echo ']';
			echo '}';
		}
		else if ($hasRelatedCountry && $hasRelatedZip)
		{
			echo '{';
				echo 'state:"OK",';
				echo 'stations:';
				echo '[';
					echo '{id:0, label:"--Plateforme--", is_default:0},';
					echo '{id:1, label:"Paris", is_default:0},';
					echo '{id:2, label:"Marseille", is_default:1}';
				echo ']';
			echo '}';
		}
		else
		{
			echo '{';
				echo 'state:"OK",';
				echo 'stations:';
				echo '[';
					echo '{id:0, label:"--Plateforme--", is_default:0},';
					echo '{id:1, label:"Paris", is_default:0},';
					echo '{id:2, label:"Marseille", is_default:0},';
					echo '{id:3, label:"Lyon", is_default:0},';
					echo '{id:4, label:"Bordeaux", is_default:0}';
				echo ']';
			echo '}';
		}
	}
	
	public function getMainCarriageOriginStationAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		$hasRelatedStation = $this->_hasParam('related_destination_station_id');
		$hasRelatedCountry = $this->_hasParam('related_id_country');
		$hasRelatedZip = $this->_hasParam('related_zip_code');
		
		if ($hasRelatedStation && $hasRelatedCountry && $hasRelatedZip)
		{
			echo '{';
				echo 'state:"NO_PLATEFORME_FOUND",';
				echo 'message:"L\'itinéraire demandé n\'existe pas."';
			echo '}';
		}
		else if ($hasRelatedStation)
		{
			echo '{';
				echo 'state:"OK",';
				echo 'stations:';
				echo '[';
					echo '{id:0, label:"--Plateforme--", is_default:0},';
					echo '{id:1, label:"Paris", is_default:0}';
				echo ']';
			echo '}';
		}
		else if ($hasRelatedCountry && $hasRelatedZip)
		{
			echo '{';
				echo 'state:"OK",';
				echo 'stations:';
				echo '[';
					echo '{id:0, label:"--Plateforme--", is_default:0},';
					echo '{id:1, label:"Paris", is_default:0},';
					echo '{id:2, label:"Marseille", is_default:1}';
				echo ']';
			echo '}';
		}
		else
		{
			echo '{';
				echo 'state:"OK",';
				echo 'stations:';
				echo '[';
					echo '{id:0, label:"--Plateforme--", is_default:0},';
					echo '{id:1, label:"Paris", is_default:0},';
					echo '{id:2, label:"Marseille", is_default:0},';
					echo '{id:3, label:"Lyon", is_default:0},';
					echo '{id:4, label:"Bordeaux", is_default:0}';
				echo ']';
			echo '}';
		}
	}
	
	public function getPrePostCarriageCountryAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		echo '{';
			echo 'preCarriageCountry:';
			echo '[';
				echo '{id: 0, label: "--Pays--"},';
				echo '{id: 1, label: "Allemange"},';
				echo '{id: 2, label: "France"},';
				echo '{id: 3, label: "Italie"}';
			echo '],';
			echo 'postCarriageCountry:';
			echo '[';
				echo '{id: 0, label: "--Pays--"},';
				echo '{id: 1, label: "Allemange"},';
				echo '{id: 2, label: "France"},';
				echo '{id: 3, label: "Italie"},';
				echo '{id: 4, label: "Portugal"}';
			echo ']';
		echo '}';
	}
	
	public function getRelationEnvironmentalInformationAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		
		echo '{';
			echo 'RoadGlobal:';
			echo '{';
				echo 'co2: "300",';
				echo 'nrj: "200"';
			echo '},';
			echo 'NavilandGlobal:';
			echo '{';
				echo 'co2_prepost: "50",';
				echo 'nrj_prepost: "20",';
				echo 'co2_main: "121",';
				echo 'nrj_main: "60"';
			echo '},';
			echo 'RoadKPIUTI:';
			echo '{';
				echo 'co2: "30",';
				echo 'nrj: "20"';
			echo '},';
			echo 'NavilandKPIUTI:';
			echo '{';
				echo 'co2_prepost: "5",';
				echo 'nrj_prepost: "2",';
				echo 'co2_main: "12",';
				echo 'nrj_main: "6"';
			echo '},';
			echo 'Gap:';
			echo '{';
				echo 'co2: "12",';
				echo 'nrj: "10"';
			echo '}';
		echo '}';
	}
}