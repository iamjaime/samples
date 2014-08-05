<?php

class Match extends Eloquent {

    protected $table = 'fu_match';

    /**
     * 
     * @param string $match_id the match_id that we want to get.
     * @param string $client_id the client_id
     * @param boolean $closed do we want a closed match
     * @param boolean $ended do we want a match that hase ended
     * @return int the record id for the match_id.
     */
    public static function getMatchId($match_id, $client_id=null, $closed=FALSE, $ended=FALSE) {
        
        if($client_id != NULL)
        {
            $id = DB::table('fu_match')
                ->where('match_id', $match_id)
                ->where('client_id', $client_id)
                ->where('closed', $closed)
                ->where('ended', $ended)
                ->pluck('id');
        }else{
        
            $id = DB::table('fu_match')
                ->where('match_id', $match_id)
                ->where('closed', $closed)
                ->where('ended', $ended)
                ->pluck('id');
        }
        if(!$id){
            return false;
        }
        
        return $id;
    }

    
    
    /**
     * @param string $client_id The Client ID that this match belongs to.
     * @param string $rake_type The rake type (per_player or total)
     * @return int the record id that was created.
     */
    public static function createNewMatch($client_id, $rake_type)
    {
        $data['rake_type'] = $rake_type;
        $data['client_id'] = $client_id;
        $data['created_at'] = Config::get('sitesettings.mysql_dt_format');
        $data['updated_at'] = Config::get('sitesettings.mysql_dt_format');
        
        $id = DB::table('fu_match')->insertGetId($data);
        
        $hash_id = sha1($id . ':' . $client_id);
        
        $update = DB::table('fu_match')
                ->where('id', $id)
                ->update(array('match_id' => $hash_id, 'updated_at' => Config::get('sitesettings.mysql_dt_format')));
        
        return $hash_id;
    }
    
    /**
     * 
     * @param string $match_id the match_id
     * @return string the rake_type
     */
    public static function getRakeType($match_id, $closed=FALSE, $ended=FALSE) {
        $rake_type = DB::table('fu_match')
                ->where('match_id', $match_id)
                ->where('closed', $closed)
                ->where('ended', $ended)
                ->pluck('rake_type');
        
        if(!$rake_type){
            return false;
        }
        
        return $rake_type;
    }
    
    /**
     * 
     * @param string $match_id the match_id
     * @return boolean
     */
    public static function checkMatchClosed($match_id) {
        $match = DB::table('fu_match')
                ->where('match_id', $match_id)
                ->pluck('closed');
        
        if(!$match){
            return false;
        }else{
            return true;
        }
        
    }
    
    
     /**
     * 
     * @param string $match_id the match_id
     * @return array the players in the match
     */
    public static function getPlayersInMatch($match_id, $closed=FALSE, $ended=FALSE) {
        $players = DB::table('fu_match')
                ->where('match_id', $match_id)
                ->where('closed', $closed)
                ->where('ended', $ended)
                ->pluck('players_in_match');
        
        if(!$players){
            return false;
        }
        
        //lets blow them up into an array
        $players = explode('|', $players);
        
        
        return $players;
    }
    
    
    public static function updateMatch($data){
        
        $update = DB::table('fu_match')
                ->where('match_id', $data['match_id'])
                ->update($data);
    }
    
}
