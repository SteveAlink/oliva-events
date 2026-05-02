<?php
/**
 * oliva-events - Events plugin for WonderCMS.
 * Prepared by Steve Alink for Oliva Solutions
 *
 * Shows one or more dates with events or availability
 */

if (!defined('VERSION')) {
    die('Direct access denied');
}

require_once __DIR__ . '/class.oliva-events.php';

$olivaEvents = new OlivaEvents($Wcms);

if (isset($Wcms)) {
    $Wcms->addListener('css', 'olivaEventsCss');
    $Wcms->addListener('js', 'olivaEventsJs');
    $Wcms->addListener('settings', [$olivaEvents, 'handleSettings']);
    $Wcms->addListener('footer', [$olivaEvents, 'renderCalendar']);
    $Wcms->addListener('page', [$olivaEvents, 'replacePlaceholder']);
} elseif (class_exists('wCMS')) {
    wCMS::addListener('css', 'olivaEventsCss');
    wCMS::addListener('js', 'olivaEventsJs');
    wCMS::addListener('settings', [$olivaEvents, 'handleSettings']);
    wCMS::addListener('footer', [$olivaEvents, 'renderCalendar']);
    wCMS::addListener('page', [$olivaEvents, 'replacePlaceholder']);
}

function olivaEventsPluginBasePath()
{
    return 'plugins/oliva-events/';
}

function olivaEventsCss($args)
{
    $css = '<link rel="stylesheet" href="' . olivaEventsPluginBasePath() . 'css/style.css?v=0.5.0">' . PHP_EOL;

    if (isset($args[0]) && is_array($args[0])) {
        $args[0][] = $css;
    } else {
        $args[0] = ($args[0] ?? '') . $css;
    }

    return $args;
}

function olivaEventsJs($args)
{
    $js = '<script src="' . olivaEventsPluginBasePath() . 'js/oliva-events.js?v=0.5.0"></script>' . PHP_EOL;

    if (isset($args[0]) && is_array($args[0])) {
        $args[0][] = $js;
    } else {
        $args[0] = ($args[0] ?? '') . $js;
    }

    return $args;
}
