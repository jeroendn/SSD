<?php
/**
 * Authenticate if the user is allowed to view the content.
 * @return bool
 */
function authorized(): bool
{
  return (isset($_GET[URL_SECRET_KEY]) && $_GET[URL_SECRET_KEY] === URL_SECRET_VALUE);
}

/**
 * @param string $string
 * @return string
 */
function h(string $string): string
{
  return htmlspecialchars($string);
}

/**
 * @param mixed $var
 * @return void
 */
function dd(mixed $var): void
{
  var_dump($var);
  die;
}

/**
 * Sort an array of objects by a property of the object.
 * Does not work on multidimensional arrays.
 * @param array $array
 * @param string $property
 * @param bool $reverse
 * @return void
 */
function sortArrayByProperty(array &$array, string $property, bool $reverse = false): void
{
  if ($reverse) {
    usort($array, function ($a, $b) use ($property) {
      return $b->$property <=> $a->$property;
    });
  }
  else {
    usort($array, function ($a, $b) use ($property) {
      return $a->$property <=> $b->$property;
    });
  }
}

/**
 * @param string $integration
 * @param string $widgetName
 * @return void
 */
function getWidget(string $integration, string $widgetName): void
{
  include __DIR__ . '/../views/widgets/' . strtolower($integration) . '/' . $widgetName . '.php';
}