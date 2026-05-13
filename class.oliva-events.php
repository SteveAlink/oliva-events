<?php
/**
 * oliva-events - Events plugin for WonderCMS.
 * Prepared by Steve Alink for Oliva Solutions
 *
 * Shows one or more dates with events or availability
 */

class OlivaEvents
{
    private $Wcms;
    private $translations = [];

    public function __construct($Wcms)
    {
        $this->Wcms = $Wcms;
        $this->loadTranslations();
        $this->populateDefaultValues();
    }

    private function loadTranslations()
    {
        $adminLang = $this->Wcms->get('config', 'adminLang');

        $map = [
            'en' => 'en_US',
            'nl' => 'nl_NL',
            'es' => 'es_ES',
            'de' => 'de_DE',
            'fr' => 'fr_FR',
            'it' => 'it_IT'
        ];

        $langCode = $map[$adminLang] ?? 'en_US';
        $file = __DIR__ . '/languages/' . $langCode . '.ini';

        if (file_exists($file)) {
            $this->translations = parse_ini_file($file);
        } else {
            $this->translations = parse_ini_file(__DIR__ . '/languages/en_US.ini');
        }
    }

    private function t($key)
    {
        return $this->translations[$key] ?? '[[' . $key . ']]';
    }

    private function cleanText($value)
    {
        return trim((string) $value);
    }

    private function getDefaultVisitorLanguage()
    {
        $siteLang = $this->Wcms->get('config', 'siteLang');

        $map = [
            'en' => 'en_US',
            'nl' => 'nl_NL',
            'es' => 'es_ES',
            'de' => 'de_DE',
            'fr' => 'fr_FR',
            'it' => 'it_IT'
        ];

        return $map[$siteLang] ?? 'en_US';
    }

    public function populateDefaultValues()
    {
        $defaults = [
            'olivaEventsCalendarTitle' => $this->t('defaultCalendarTitle'),
            'olivaEventsUnavailableDates' => $this->t('defaultUnavailableDates'),
            'olivaEventsAvailableLabel' => $this->t('defaultAvailableLabel'),
            'olivaEventsUnavailableLabel' => $this->t('defaultUnavailableLabel'),
            'olivaEventsVisitorLanguage' => $this->getDefaultVisitorLanguage(),
            'olivaEventsDisplayMode' => 'unavailable',
            'olivaEventsHidePastDates' => 'no',
            'olivaEventsPlacementMode' => 'footer'
        ];

        foreach ($defaults as $key => $value) {
            $current = $this->Wcms->get('config', $key);

            if (empty($current) || is_object($current)) {
                $this->Wcms->set('config', $key, $value);
            }
        }
    }

    public function getCalendarTitle()
    {
        return $this->Wcms->get('config', 'olivaEventsCalendarTitle');
    }

    public function getUnavailableDates()
    {
        return $this->Wcms->get('config', 'olivaEventsUnavailableDates');
    }

    public function getAvailableLabel()
    {
        return $this->Wcms->get('config', 'olivaEventsAvailableLabel');
    }

    public function getUnavailableLabel()
    {
        return $this->Wcms->get('config', 'olivaEventsUnavailableLabel');
    }

    public function getVisitorLanguage()
    {
        return $this->Wcms->get('config', 'olivaEventsVisitorLanguage');
    }

    public function getDisplayMode()
    {
        $mode = $this->Wcms->get('config', 'olivaEventsDisplayMode');

        if ($mode !== 'available' && $mode !== 'unavailable') {
            return 'unavailable';
        }

        return $mode;
    }

    public function getHidePastDates()
    {
        $value = $this->Wcms->get('config', 'olivaEventsHidePastDates');

        return $value === 'yes' ? 'yes' : 'no';
    }

    public function getPlacementMode()
    {
        $mode = $this->Wcms->get('config', 'olivaEventsPlacementMode');

        if ($mode !== 'footer' && $mode !== 'placeholder') {
            return 'footer';
        }

        return $mode;
    }

    private function parseEvents()
    {
        $raw = $this->getUnavailableDates();
        $items = preg_split('/[\r\n,]+/', $raw);
        $events = [];
        $today = date('Y-m-d');

        foreach ($items as $item) {
            $item = trim($item);

            if ($item === '') {
                continue;
            }

            $parts = explode('|', $item, 2);
            $date = trim($parts[0]);
            $description = isset($parts[1]) ? trim($parts[1]) : '';

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                continue;
            }

            if ($this->getHidePastDates() === 'yes' && $date < $today) {
                continue;
            }

            $events[$date] = [
                'date' => $date,
                'description' => $description
            ];
        }

        ksort($events);

        return array_values($events);
    }

    private function getVisitorTranslations()
    {
        $lang = $this->getVisitorLanguage();
        $file = __DIR__ . '/languages/' . $lang . '.ini';

        if (file_exists($file)) {
            return parse_ini_file($file);
        }

        return parse_ini_file(__DIR__ . '/languages/en_US.ini');
    }

    private function getMonths()
    {
        return [
            'en_US' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'nl_NL' => ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december'],
            'es_ES' => ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'],
            'de_DE' => ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
            'fr_FR' => ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
            'it_IT' => ['gennaio', 'febbraio', 'marzo', 'aprile', 'maggio', 'giugno', 'luglio', 'agosto', 'settembre', 'ottobre', 'novembre', 'dicembre']
        ];
    }

    private function formatDate($date)
    {
        $timestamp = strtotime($date);

        if (!$timestamp) {
            return $date;
        }

        $lang = $this->getVisitorLanguage();
        $months = $this->getMonths();

        $monthNumber = (int) date('n', $timestamp);
        $monthName = $months[$lang][$monthNumber - 1] ?? $months['en_US'][$monthNumber - 1];

        return date('j', $timestamp) . ' ' . $monthName . ' ' . date('Y', $timestamp);
    }

    private function getMonthHeading($date)
    {
        $timestamp = strtotime($date);

        if (!$timestamp) {
            return '';
        }

        $lang = $this->getVisitorLanguage();
        $months = $this->getMonths();

        $monthNumber = (int) date('n', $timestamp);
        $monthName = $months[$lang][$monthNumber - 1] ?? $months['en_US'][$monthNumber - 1];

        return ucfirst($monthName) . ' ' . date('Y', $timestamp);
    }

    private function groupEventsByMonth($events)
    {
        $grouped = [];

        foreach ($events as $event) {
            $timestamp = strtotime($event['date']);

            if (!$timestamp) {
                continue;
            }

            $key = date('Y-m', $timestamp);

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'heading' => $this->getMonthHeading($event['date']),
                    'events' => []
                ];
            }

            $grouped[$key]['events'][] = $event;
        }

        return $grouped;
    }

    private function createInput($doc, $name, $value)
    {
        $input = $doc->createElement('input');
        $input->setAttribute('type', 'text');
        $input->setAttribute('name', $name);
        $input->setAttribute('class', 'editText');
        $input->setAttribute('style', 'width: 100%; border: 1px solid #ccc;');
        $input->setAttribute('value', $value);

        return $input;
    }

    private function createTextarea($doc, $name, $value)
    {
        $textarea = $doc->createElement('textarea');
        $textarea->setAttribute('name', $name);
        $textarea->setAttribute('class', 'editText');
        $textarea->setAttribute('style', 'width: 100%; border: 1px solid #ccc;');
        $textarea->setAttribute('rows', '6');
        $textarea->nodeValue = $value;

        return $textarea;
    }

    private function createLabel($doc, $text)
    {
        return $doc->createElement('label', $text);
    }

    private function createSelect($doc, $name, $options, $currentValue)
    {
        $select = $doc->createElement('select');
        $select->setAttribute('name', $name);
        $select->setAttribute('class', 'editText');
        $select->setAttribute('style', 'width: 100%; border: 1px solid #ccc;');

        foreach ($options as $value => $label) {
            $option = $doc->createElement('option', $label);
            $option->setAttribute('value', $value);

            if ($currentValue === $value) {
                $option->setAttribute('selected', 'selected');
            }

            $select->appendChild($option);
        }

        return $select;
    }

    public function alterAdmin(array $args): array
    {
        $this->loadTranslations();
        $this->populateDefaultValues();

        $doc = new DOMDocument();
        @$doc->loadHTML(mb_convert_encoding($args[0], 'HTML-ENTITIES', 'UTF-8'));

        $currentPage = $doc->getElementById('currentPage');

        if (!$currentPage) {
            return $args;
        }

        $menuList = $currentPage
            ->parentNode
            ->parentNode
            ->childNodes
            ->item(1);

        $menuItem = $doc->createElement('li');
        $menuItem->setAttribute('class', 'nav-item');

        $menuItemA = $doc->createElement('a');
        $menuItemA->setAttribute('href', '#olivaEventsSettings');
        $menuItemA->setAttribute('aria-controls', 'olivaEventsSettings');
        $menuItemA->setAttribute('role', 'tab');
        $menuItemA->setAttribute('data-toggle', 'tab');
        $menuItemA->setAttribute('class', 'nav-link');
        $menuItemA->nodeValue = $this->t('OlivaEvents');

        $menuItem->appendChild($menuItemA);
        $menuList->appendChild($menuItem);

        $wrapper = $doc->createElement('div');
        $wrapper->setAttribute('role', 'tabpanel');
        $wrapper->setAttribute('class', 'tab-pane');
        $wrapper->setAttribute('id', 'olivaEventsSettings');

        $form = $doc->createElement('form');
        $form->setAttribute('method', 'post');
        $form->setAttribute('action', '');

        $title = $doc->createElement('p');
        $title->setAttribute('class', 'subTitle');
        $title->nodevalue = $this->t('headingEventsSettings');
        $form->appendChild($title);

        $form->appendChild($this->createLabel($doc, $this->t('labelCalendarTitle')));
        $form->appendChild($this->createInput($doc, 'oliva_events_calendar_title', $this->getCalendarTitle()));

        $form->appendChild($this->createLabel($doc, $this->t('labelPlacementMode')));
        $form->appendChild($this->createSelect($doc, 'oliva_events_placement_mode', [
            'footer' => $this->t('optionPlacementFooter'),
            'placeholder' => $this->t('optionPlacementPlaceholder')
        ], $this->getPlacementMode()));

        $placeholderHelp = $doc->createElement('p', $this->t('helpPlacementMode'));
        $placeholderHelp->setAttribute('class', 'small text-muted');
        $form->appendChild($placeholderHelp);

        $form->appendChild($this->createLabel($doc, $this->t('labelDisplayMode')));
        $form->appendChild($this->createSelect($doc, 'oliva_events_display_mode', [
            'unavailable' => $this->t('optionShowUnavailableDates'),
            'available' => $this->t('optionShowAvailableDates')
        ], $this->getDisplayMode()));

        $form->appendChild($this->createLabel($doc, $this->t('labelHidePastDates')));
        $form->appendChild($this->createSelect($doc, 'oliva_events_hide_past_dates', [
            'no' => $this->t('optionNo'),
            'yes' => $this->t('optionYes')
        ], $this->getHidePastDates()));

        $form->appendChild($this->createLabel($doc, $this->t('labelDates')));
        $form->appendChild($this->createTextarea($doc, 'oliva_events_unavailable_dates', $this->getUnavailableDates()));

        $help = $doc->createElement('p', $this->t('helpDates'));
        $help->setAttribute('class', 'small text-muted');
        $form->appendChild($help);

        $form->appendChild($this->createLabel($doc, $this->t('labelAvailableLabel')));
        $form->appendChild($this->createInput($doc, 'oliva_events_available_label', $this->getAvailableLabel()));

        $form->appendChild($this->createLabel($doc, $this->t('labelUnavailableLabel')));
        $form->appendChild($this->createInput($doc, 'oliva_events_unavailable_label', $this->getUnavailableLabel()));

        $form->appendChild($this->createLabel($doc, $this->t('labelVisitorLanguage')));

        $languagesDir = __DIR__ . '/languages';
        $languageFiles = glob($languagesDir . '/*.ini');
        $currentLang = $this->getVisitorLanguage();

        $languageOptions = [];

        foreach ($languageFiles as $file) {
            $languageCode = basename($file, '.ini');
            $languageOptions[$languageCode] = $languageCode;
        }

        $form->appendChild($this->createSelect($doc, 'oliva_events_visitor_language', $languageOptions, $currentLang));

        $saveButton = $doc->createElement('button');
        $saveButton->setAttribute('type', 'submit');
        $saveButton->setAttribute('name', 'saveOlivaEventsSettings');
        $saveButton->setAttribute('class', 'btn btn-primary');
        $saveButton->nodeValue = $this->t('saveButton');

        $form->appendChild($saveButton);

        $wrapper->appendChild($form);
        $currentPage->parentNode->appendChild($wrapper);

        $args[0] = preg_replace(
            '~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i',
            '',
            $doc->saveHTML()
        );

        return $args;
    }

    public function handleSettings(array $args): array
    {
        if (!$this->Wcms->loggedIn) {
            return $args;
        }

        if (isset($_POST['saveOlivaEventsSettings'])) {
            $displayMode = $this->cleanText($_POST['oliva_events_display_mode'] ?? 'unavailable');

            if ($displayMode !== 'available' && $displayMode !== 'unavailable') {
                $displayMode = 'unavailable';
            }

            $hidePastDates = $this->cleanText($_POST['oliva_events_hide_past_dates'] ?? 'no');

            if ($hidePastDates !== 'yes' && $hidePastDates !== 'no') {
                $hidePastDates = 'no';
            }

            $placementMode = $this->cleanText($_POST['oliva_events_placement_mode'] ?? 'footer');

            if ($placementMode !== 'footer' && $placementMode !== 'placeholder') {
                $placementMode = 'footer';
            }

            $this->Wcms->set('config', 'olivaEventsCalendarTitle', $this->cleanText($_POST['oliva_events_calendar_title'] ?? $this->t('defaultCalendarTitle')));
            $this->Wcms->set('config', 'olivaEventsPlacementMode', $placementMode);
            $this->Wcms->set('config', 'olivaEventsDisplayMode', $displayMode);
            $this->Wcms->set('config', 'olivaEventsHidePastDates', $hidePastDates);
            $this->Wcms->set('config', 'olivaEventsUnavailableDates', $this->cleanText($_POST['oliva_events_unavailable_dates'] ?? ''));
            $this->Wcms->set('config', 'olivaEventsAvailableLabel', $this->cleanText($_POST['oliva_events_available_label'] ?? $this->t('defaultAvailableLabel')));
            $this->Wcms->set('config', 'olivaEventsUnavailableLabel', $this->cleanText($_POST['oliva_events_unavailable_label'] ?? $this->t('defaultUnavailableLabel')));
            $this->Wcms->set('config', 'olivaEventsVisitorLanguage', $this->cleanText($_POST['oliva_events_visitor_language'] ?? 'en_US'));
        }

        return $this->alterAdmin($args);
    }

    private function buildCalendarHtml()
    {
        $visitorTranslations = $this->getVisitorTranslations();

        $title = htmlspecialchars($this->getCalendarTitle(), ENT_QUOTES, 'UTF-8');
        $availableLabel = htmlspecialchars($this->getAvailableLabel(), ENT_QUOTES, 'UTF-8');
        $unavailableLabel = htmlspecialchars($this->getUnavailableLabel(), ENT_QUOTES, 'UTF-8');
        $todayLabel = htmlspecialchars($visitorTranslations['todayLabel'] ?? 'today', ENT_QUOTES, 'UTF-8');
        $emptyDatesMessage = htmlspecialchars($visitorTranslations['emptyDatesMessage'] ?? 'No dates to show.', ENT_QUOTES, 'UTF-8');
        
        $displayMode = $this->getDisplayMode();

        if ($displayMode === 'available') {
            $activeLabel = $availableLabel;
            $activeClass = 'oliva-events-date-available';
            $sectionClass = 'oliva-events-mode-available';
        } else {
            $activeLabel = $unavailableLabel;
            $activeClass = 'oliva-events-date-unavailable';
            $sectionClass = 'oliva-events-mode-unavailable';
        }

        $events = $this->parseEvents();
        $groupedEvents = $this->groupEventsByMonth($events);
        $today = date('Y-m-d');

        $html = PHP_EOL;
        $html .= '<section id="oliva-events" class="oliva-events ' . $sectionClass . '">' . PHP_EOL;
        $html .= '  <h2>' . $title . '</h2>' . PHP_EOL;

        $html .= '  <div class="oliva-events-legend">' . PHP_EOL;
        $html .= '    <span class="oliva-events-legend-item oliva-events-available">' . $availableLabel . '</span>' . PHP_EOL;
        $html .= '    <span class="oliva-events-legend-item oliva-events-unavailable">' . $unavailableLabel . '</span>' . PHP_EOL;
        $html .= '  </div>' . PHP_EOL;

        if (empty($groupedEvents)) {
            $html .= '  <p class="oliva-events-empty">' . $emptyDatesMessage . '</p>' . PHP_EOL;
        } else {
            foreach ($groupedEvents as $month) {
                $html .= '  <div class="oliva-events-month">' . PHP_EOL;
                $html .= '    <h3>' . htmlspecialchars($month['heading'], ENT_QUOTES, 'UTF-8') . '</h3>' . PHP_EOL;
                $html .= '    <ul class="oliva-events-list">' . PHP_EOL;

                foreach ($month['events'] as $event) {
                    $safeDate = htmlspecialchars($event['date'], ENT_QUOTES, 'UTF-8');
                    $formattedDate = htmlspecialchars($this->formatDate($event['date']), ENT_QUOTES, 'UTF-8');
                    $description = htmlspecialchars($event['description'], ENT_QUOTES, 'UTF-8');

                    $classes = 'oliva-events-date ' . $activeClass;

                    if ($event['date'] === $today) {
                        $classes .= ' oliva-events-date-today';
                    }

                    $html .= '      <li class="' . $classes . '" data-date="' . $safeDate . '">' . PHP_EOL;
                    $html .= '        <div class="oliva-events-date-main">' . PHP_EOL;
                    $html .= '          <span class="oliva-events-date-value">' . $formattedDate . '</span>' . PHP_EOL;

                    if ($description !== '') {
                        $html .= '          <span class="oliva-events-date-description">' . $description . '</span>' . PHP_EOL;
                    }

                    $html .= '        </div>' . PHP_EOL;
                    $html .= '        <span class="oliva-events-date-status">' . $activeLabel;

                    if ($event['date'] === $today) {
                        $html .= ' <small>(' . $todayLabel . ')</small>';
                    }

                    $html .= '</span>' . PHP_EOL;
                    $html .= '      </li>' . PHP_EOL;
                }

                $html .= '    </ul>' . PHP_EOL;
                $html .= '  </div>' . PHP_EOL;
            }
        }

        $html .= '</section>' . PHP_EOL;

        return $html;
    }

    public function renderCalendar(array $args): array
    {
        if ($this->getPlacementMode() !== 'footer') {
            return $args;
        }

        $args[0] .= $this->buildCalendarHtml();

        return $args;
    }

    public function replacePlaceholder(array $args): array
    {
        if ($this->getPlacementMode() !== 'placeholder') {
            return $args;
        }

        if (!isset($args[1]) || $args[1] !== 'content') {
            return $args;
        }

        if (strpos($args[0], '{{oliva-events}}') === false) {
            return $args;
        }

        $args[0] = str_replace('{{oliva-events}}', $this->buildCalendarHtml(), $args[0]);

        return $args;
    }
}
