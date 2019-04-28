<?php


function getORCIDPapers($orcid_input){
	
	// me: "0000-0002-1334-3059";
	
	//https://arxiv.org/a/0000-0002-1334-3059.json
	
	$numfound = preg_match("/\\d{4}-\\d{4}-\\d{4}-\\d{4}/",$orcid_input, $matches);
	$orcid = $matches[0];
	if ($orcid == ""){
		print ("Error with OCRID");
		return;
	}
	
	$orcidPapersDirect = getORCIDPapersDirect($orcid);
	
	$orcidPapersArxiv = getORCIDPapersArxiv($orcid);
	
	$results = mergeResults(array($orcidPapersArxiv, $orcidPapersDirect));
	
	//print_r($results);
	
	return $results;
}



function getORCIDPapersDirect($orcid){

	$html = cachedWebRequest("orcid",$orcid,"http://orcid.org/".$orcid);

	$numfound = preg_match("/workIds = JSON\\.parse\\(\\\"\\[(.*)\\]/",$html, $matches);
	
	$wordIds = explode(",",$matches[1]);
	
	//print_r($wordIds);
	
	$toreturn = array();
	for ($i = 0; $i < sizeof($wordIds); $i++) {
	
		$wordId = $wordIds[$i];
		
		$html = cachedWebRequest("orcid",$orcid.$wordId,"http://orcid.org/".$orcid."/getWorkInfo.json?workId=".$wordId);
	
		$work = json_decode($html);
		
		$doi = $work->workExternalIdentifiers[0]->workExternalIdentifierId->value;
		
		//print_r($work);
		
		if ($doi != ""){
			$paper = getPaper($doi);
			$toreturn[] = $paper;
		}
		
		
		
		
	
	}
	return $toreturn;
}




function getORCIDPapersArxiv($orcid){

	//print_r($orcid);
	
	$toreturn = array();
	$json = cachedWebRequest("arxiv",$orcid,"https://arxiv.org/a/".$orcid.".json");

	
	if (!isset($json)) return $toreturn;
	
	$jsonp = json_decode($json);

	
	for ($i = 0; $i < sizeof($jsonp->entries); $i++) {

		$entry = $jsonp->entries[$i];

		$numfound = preg_match("/[\\/]+(\\d*\\.\\d*)/",$entry->id, $matches);
		
		$id = $matches[0];

		$id = str_replace("/","", $id);
		
		//print_r($id."\n");
		
		if ($id != ""){
		
			$paper = getPaper($id);
			$toreturn[] = $paper;
		}

		//print_r($paper);

	}
	return $toreturn;
}


?>