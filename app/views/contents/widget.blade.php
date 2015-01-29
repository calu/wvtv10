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
//var_dump($lijst);print("<br />");
//if (!($rubriek == 'navorming' || $rubriek == 'bestuur')) die("einde");
?>

<div>
	<ul class='shorttable'>
		@foreach($lijst AS $item)
			{{-- Als dit link of wetgeving, dan op nieuw blad de link openen --}}
			@if ($rubriek == 'links' || $rubriek == 'wetgeving')
				<li>
				<a href="{{ $item['url'] }}" target='_new'>{{ $item['title'] }}</a>
					
				</li>
			@else
				<?php $url = url('volledigelijst', $parameters = array('rubriek' => $rubriek, 'title' => $item));  ?>
				<li><a href="{{ $url }}">{{$item}}</a></li>
			@endif

		@endforeach
	</ul>
	<div style='clear:both'>
		{{-- link toon de volledige lijst --}}

		<?php $url = url('volledigelijst',$parameters = array('rubriek' => $rubriek, 'title' => 'leeg')); ?>
		<a href='{{ $url }}' class='groen'>toon de volledige lijst</a>
	</div>	
</div>

