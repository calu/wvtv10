<?php

class AppHelper {

    public static function getShortlist($rubriek)
    {
    	switch ($rubriek)
		{
			case 'bestuur' :
				/*
				// Haal uit de tabel "bestuurs" de 3 eerste items
				$tabel = DB::table('bestuurs')->orderBy('sortnr', 'asc')->get();
				for ($i = 0; $i < 4; $i++)
				{
					$item = $tabel[$i];
					$user_array = DB::table('users')->where('id', '=', $item->user_id)->get();
					// en maak er de string : voornaam naam ( functie ) 
					if (sizeof($user_array) == 1)
					{
						$user = $user_array[0];
						$line = $user->first_name." ".$user->last_name;
					}
					
					$functie = $item->bestuursfunctie;
					if (strlen($functie) > 0) $line .= " ({$functie})";
					$ret[] = $line;				
				}
				*/
				$ret = null;
				break;
			case 'profiel' :
				$ret = null;
				break;
			case 'navorming' :
				/*
				$tabel = DB::select("SELECT DISTINCT(title) FROM Documents WHERE type='navorming' ORDER BY sortnr");
				for ($i = 0; $i < 4; $i++)
				{
					$ret[] = $tabel[$i]->title;
				}
				 * 
				 */
				$ret[] = "een"; $ret[] = "twee"; $ret[] = "drie";
				break;
			default :
				print("<br /> deze rubriek {$rubriek} is nog niet geïmplementeerd");
				die(" ##### tot hier");
		}
    	return $ret;
    }
}

?>