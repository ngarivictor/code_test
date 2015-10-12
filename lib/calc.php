	
	<?php
	
	define("USAGE", "ndisha test");
	define("RATE", 100);
	class calc {

    public static function Calculate($json) {
        $counts = array();
        $community_arrayx = array(); //non-functional water points
		$community_array = array(); //water points per community
        $ranking_array = array(); //ranking  by percentage
        $counter = 0;
        
        foreach ($json as $data => $value) {
            if (isset($value['water_functioning'])) {
                $stat = $value['water_functioning'];
                $community = $value['communities_villages'];
                if (!in_array($community_arrayx, $community)) {
                    array_push($community, $community_arrayx);
                }                
                if (strcasecmp($stat, "yes") == 0) {
                    ++$counter;
                }
            }
        }

        $cc_arr=array();
        
        for ($y = 0; $y < count($community_arrayx); ++$y) {
            $comm = $community_arrayx[$y];
            $community_array[$comm] 
                    = calc::getWaterPointsToTal($json, $comm);
            
            $cc_arr[$comm] 
                    = calc::getBrokenWaterPointsToTal($json, $comm);
            $ranking_array = $cc_arr;
        }
        
                
        $counts['functional_water_points'] = $counter;
        $counts['number_of_water_points'] = json_encode($community_array);
        $counts['community_ranking'] = calc::getRanking($ranking_array);

        return $counts;
    }
    
    /* Count the number of broken_water_points.
     */
   public static function getBrokenWaterPoints($json, $community) {
        $counter = 0;
        foreach ($json as $data => $value) {
            if (isset($value['communities_villages'])) {
                $community_cmp = $value['communities_villages'];
                if (strcasecmp($community, $community_cmp) == 0) {
                    if(isset($value['water_point_condition'])){
                        $stat = $value['water_point_condition'];
                        if (strcasecmp($stat, "broken") == 0) {
                            ++$counter;
                        }
                    }
                }
            }
        }
        return $counter;
    }

    /**
     * Get n0 of total water_points
     
     */
   public static function getTotalWaterPoints($json, $community,
            $functioning = 'yes') {
        $counter = 0;
        foreach ($json as $data => $value) {
            if (isset($value['communities_villages'])) {
                $community_cmp = $value['communities_villages'];
                if (strcasecmp($community, $community_cmp) == 0) {
                    if ($functioning == NULL || empty($functioning)) {
                        ++$counter;
                    } else {
                        $stat = $value['water_functioning'];
                        if (strcasecmp($functioning, $stat) == 0) {
                            ++$counter;
                        }
                    }
                }
            }
        }
        return $counter;
		//int
    }
    
    /**
     * Calculate the ranking by community in ascending Order.
     */
    public static function getRanking($data) {
        $values = array();
        $total=0;
        foreach ($data as $broken_water_points=>$community){
            $total=$total+$broken_water_points;
        }
        //Calculate the percentages
        $perc = array();
        foreach ($data as $community=>$broken_water_points){
            $p = ($broken_water_points*RATE)/$total;
            $perc[$community]=  sprintf(FORMAT, $p);
        }
        //sort percentages
        array_multisort($perc);
        
        return json_encode($perc);
    }

   
}
