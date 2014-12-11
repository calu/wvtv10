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
		@foreach($lijst AS $item)
			<?php $url = url('volledigelijst', $parameters = array('rubriek' => $rubriek, 'title' => $item)) ?>
			<li><a href="{{ $url }}">{{$item}}</a></li>
		@endforeach
	</ul>
	<div style='clear:both'>
		{{-- link toon de volledige lijst --}}

		<?php $url = url('volledigelijst',$parameters = array('rubriek' => $rubriek, 'title' => 'leeg')); ?>
		<a href='{{ $url }}' class='groen'>toon de volledige lijst</a>
	</div>	
</div>

