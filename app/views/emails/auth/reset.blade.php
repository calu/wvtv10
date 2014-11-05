<!DOCTYPE html>
<html lang="nl">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Wachtwoord opnieuw instellen</h2>
		
		<p>Om je wachtwoord opnieuw in te stellen, <a href="{{ URL::to('users') }}/{{ $userId }}/reset/{{ urlencode($resetCode) }}">klik hier</a>.
		Als je dit niet gevraagd hebt, kan je deze mail gewoon verwijderen zonder probleem - er wordt niets gewijzigd.</p>
		
		<p>Je kan ook in je browser dit adres invullen : <br /> {{ URL::to('users') }}/{{ $userId }}/reset/{{ urlencode($resetCode) }}</p>
		
		<p>Hartelijk dank,<br />de webbeheerder</p>
	</body>
</html>