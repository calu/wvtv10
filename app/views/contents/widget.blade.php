<?php
/*
 * @file : widget.blade.php
 * @author : dr. Johan Calu
 * @date : 3.11.14
 * 
 * Elke widget bevat een lijstje met de eerste 3 gegevens (inhoud varieert naargelang rubriek)
 * daarna wordt het gevolgd door een tekst "toon volledige lijst"
 * 
 */
 
// We beginnen met het ophalen van een shortlist
$lijst = AppHelper::getShortlist($rubriek);
 
?>

<div>
	<ul class='shorttable'>
		<li>een</li>
		<li>twee</li>
		<li>drie</li>
	</ul>
</div>

