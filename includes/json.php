<?php
// All of this is custom, as this was blank when I retrieved it from the site.
function cleanup($array, $bool = false) {
	return($bool === FALSE ? $array : array_values($array));
}
?>