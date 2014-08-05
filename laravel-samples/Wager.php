<?php
/**
 * This class should handle the wagers table...
 */
class Wager extends Eloquent {

    protected $table = 'fu_wager';
    
    
    public static function createWager($data)
    {
        
        $id = DB::table('fu_wager')->insertGetId($data);
        
        return $id;
    }
    
    
    public static function getPlayersInMatch($match_id)
    {
        //returns object not array
        $players_in_match = DB::table('fu_wager')->where('match_id', $match_id)->distinct()->get();
        $player_id = ''; //default value
        
        foreach($players_in_match as $player)
        {
            //now we glew it up....
            $player_id .= $player->player_id . '|';
        }
        
        return $player_id;
    }
    
    /**
     * 
     * @param string $match_id the match id
     * @return float The total wager pool
     */
    public static function getTotalWagerPoolAfterRake($match_id)
    {
        $total = DB::table('fu_wager')
                ->where('match_id',$match_id)
                ->sum('final_wager_after_rakes');
    
        return $total;
    }
    
    /**
     * 
     * @param string $match_id the match id
     * @return float The total wager pool
     */
    public static function getTotalWagerPoolCompanyRake($match_id)
    {
        $total = DB::table('fu_wager')
                ->where('match_id',$match_id)
                ->sum('company_rake_amount');
    
        return $total;
    }
    
    /**
     * 
     * @param string $match_id the match id
     * @return float The total wager pool
     */
    public static function getTotalWagerPoolClientRake($match_id)
    {
        $total = DB::table('fu_wager')
                ->where('match_id',$match_id)
                ->sum('client_rake_amount');
    
        return $total;
    }
    
    public static function isPlayerInMatch($wager_key, $app_id, $player_id)
    {
        $players = self::getPlayersInMatch($wager_key, $app_id);
    
        if(in_array($player_id, $players))
        {
            return true;
        }else{
            return false;
        }
    }
    
    public static function getTotalWagerAfterRake($wager_key, $app_id)
    {
        $wager = DB::table('fu_wager')->where('wager_key', $wager_key)->where('app_id', $app_id)->pluck('total_wager_after_rake');
        return $wager;
    }
    
    public static function updateWagerData($data)
    {
        //now lets update the wager table in our databse...
        DB::table('fu_wagers')
                ->where('app_id', $data['app_id'])
                ->where('wager_key',$data['wager_key'])
                ->update(array('dt_ended' => Config::get('sitesettings.mysql_dt_format'),'ended' => 1));
    }
}