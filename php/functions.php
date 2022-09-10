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
 * @param string $integration
 * @param string $widgetName
 * @return void
 */
function getWidget(string $integration, string $widgetName): void
{
  include __DIR__ . '/../views/widgets/' . strtolower($integration) . '/' . $widgetName . '.php';
}