<?

function abbreviateName($name) {
      $words = explode(',', $name);
      $lastName = trim($words[0]);
      $firstName = trim($words[1]);
      $initial = substr($firstName, 0, 1);
      return $lastName . ', ' . $initial . '.';
}

?>