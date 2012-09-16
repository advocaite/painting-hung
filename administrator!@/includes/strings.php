<?php //strings.php :: Funciones variadas para dar formato al texto
//
//  Simple coloreo de numeros.
//
function colorNumber($n, $s = '') {
	
	if ($n > 0) {
		if ($s != '') {
			$s = colorGreen ( $s );
		} else {
			$s = colorGreen ( $n );
		}
	} elseif ($n < 0) {
		if ($s != '') {
			$s = colorRed ( $s );
		} else {
			$s = colorRed ( $n );
		}
	} else {
		if ($s != '') {
			$s = $s;
		} else {
			$s = $n;
		}
	}
	return $s;
}

function colorRed($n) {
	return '<font color="#ff0000">' . $n . '</font>';
}

function colorGreen($n) {
	return '<font color="#00ff00">' . $n . '</font>';
}

function pretty_number($n, $floor = true) {
	if ($floor) {
		$n = floor ( $n );
	}
	return number_format ( $n, 0, ",", "." );

}

?>