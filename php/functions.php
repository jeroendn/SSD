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
 * Returns the current URL query secret.
 * @return string
 */
function getSecretQuery(): string
{
    $value = $_GET[URL_SECRET_KEY];

    return '?' . URL_SECRET_KEY . '=' . $value;
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
 * @param string $integration
 * @param string $widgetName
 * @return void
 */
function getWidget(string $integration, string $widgetName): void
{
    include __DIR__ . '/../views/widgets/' . strtolower($integration) . '/' . $widgetName . '.php';
}