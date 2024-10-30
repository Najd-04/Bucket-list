<?php

namespace App\Helper;

class Censurator
{
private array $motsCensured =['chauve','con','putain','salop','salope','rebeu'];
  public function purify(string $string):string
  {
foreach ($this->motsCensured as $word){

  $string = preg_replace('/\b' . preg_quote($word, '/') . '\b/i', str_repeat('*', mb_strlen($word)), $string);
}


return $string;
  }
}