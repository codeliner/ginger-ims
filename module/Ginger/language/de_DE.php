<?php
return array(
    //general
    'HEADLINE::MODULES'          => 'Module',
    'BUTTON::BACK'               => 'zurück',
    'BUTTON::SAVE'               => 'speichern',
    'BUTTON::OK'                 => 'ok',
    'BUTTON::CANCEL'             => 'abbrechen',
    'BUTTON::NEXT'               => 'weiter',
    'BUTTON::ACTIVATE_OPTIONS'   => 'Einstellungen aktivieren',
    'BUTTON::EXPORT'             => 'exportieren',
    'BUTTON::IMPORT'             => 'importieren',
    'BUTTON::SEARCH_FILE'        => 'durchsuchen',
    'GENERAL::GENERAL'           => 'Allgemein',
    'GENERAL::CONFIGURATION'     => 'Einstellungen',
    'GENERAL::NAME'              => 'Name',
    'GENERAL::SOURCE'            => 'Quelle',
    'GENERAL::MAPPER'            => 'Mapping',
    'GENERAL::MAPPERS'           => 'Mapping',
    'GENERAL::TARGET'            => 'Ziel',
    'GENERAL::FEATURE'           => 'Option',
    'GENERAL::FEATURES'          => 'Zusatzoptionen',
    'GENERAL::FILTER'            => 'Filter',
    'GENERAL::FILTERS'           => 'Filter',
    'GENERAL::VALIDATOR'         => 'Validator',
    'GENERAL::VALIDATORS'        => 'Validatoren',
    'GENERAL::MANIPULATOR'       => 'Manipulator',
    'GENERAL::MANIPULATORS'      => 'Manipulatoren',
    'GENERAL::OTHERS'            => 'Andere',
    'GENERAL::SOURCE_ATTRIBUTE'  => 'Quell-Attribut',
    'GENERAL::TARGET_ATTRIBUTE'  => 'Ziel-Attribut',
    'GENERAL::START'             => 'Start',
    'GENERAL::DURATION'          => 'Dauer',
    'GENERAL::STATUS'            => 'Status',
    'GENERAL::SUCCESSFUL'        => 'erfolgreich',
    'GENERAL::ERRORFUL'          => 'fehlerhaft',
    'GENERAL::FAILED'            => 'fehlgeschlagen',
    'GENERAL::TIME::AM'          => 'Uhr',
    'GENERAL::TIME::PM'          => 'Uhr',
    'GENERAL::HISTORY'           => 'Historie',
    'GENERAL::TIMESTAMP'         => 'Zeitpunkt',
    'GENERAL::ALL'               => 'Alle',
    'GENERAL::INFO'              => 'Info',
    'GENERAL::WARNING'           => 'Warnung',
    'GENERAL::ERROR'             => 'Fehler',
    'GENERAL::DESCRIPTION'       => 'Beschreibung',
    'GENERAL::PERMISSIONS'       => 'Berechtigungen',
    'GENERAL::TIME_SCHEDULE'     => 'Zeitplan',
    'GENERAL::CONFIG_JSON_STYLE' => 'Die Angabe erfolgt im JSON-Format: "Schlüssel1" : "Wert1", "Schlüssel2" : "Wert2", ...',
    'GENERAL::FILE_PATTERN'      => 'File Pattern',
    'GENERAL::FILE_NAME'         => 'Dateiname',
    'GENERAL::SOURCE_DIRECTORY'  => 'Quell-Verzeichnis',
    'GENERAL::TARGET_DIRECTORY'  => 'Ziel-Verzeichnis',
    'GENERAL::USER_DEFINED'      => 'benutzerdefiniert',
    'GENERAL::DIRECTORY'         => 'Verzeichnis',
    //Dialogs
    'DIALOG::CHOOSE_IMPORT_FILE'    => 'Import-Datei wählen',
    //Forms
    'FORM::LABEL::DESCRIPTION' => 'Beschreibung',
    'FORM::BUTTON::SAVE'       => 'speichern',
    //Form Error Msgs
    'Value is empty!' => 'Bitte geben Sie einen Wert ein!',
    //Jobs
    'HEADLINE::JOBS'                         => 'Jobs',
    'HEADLINE::JOBS::ADD'                    => 'Job hinzufügen',
    'HEADLINE::JOBS::EDIT'                   => 'Einstellungen',
    'HEADLINE::JOBS::CONFIGURATION'          => 'Aufgabe',
    'HEADLINE::JOBS::CONFIGURATIONS'         => 'Aufgaben',
    'HEADLINE::JOBS::CONFIGURATION::ADD'     => 'Aufgabe hinzufügen',
    'HEADLINE::JOBS::CONFIGURATION::EDIT'    => 'Aufgabe bearbeiten',
    'HEADLINE::JOBS::ITEMS'                  => 'Datensätze',
    'HEADLINE::JOBS::JOBRUNS'                => 'Durchläufe',
    'HEADLINE::JOBS::LAST_RUN'               => 'Protokoll des letzten Durchlaufs',
    'JOBS::NO_JOBS'                          => 'Sie haben noch keine Jobs erstellt.',
    'JOBS::BUTTON::ADD'                      => 'Job hinzufügen',
    'JOBS::BUTTON::CONFIGURATION::ADD'       => 'Aufgabe hinzufügen',
    'JOBS::BUTTON::START'                    => 'Job starten',
    'JOBS::BUTTON::DELETE'                   => 'Job löschen',
    'JOBS::FORM::LABEL::JOBNAME'             => 'Jobname',
    'JOBS::LABEL::SUCCESSFUL_ACTIONS'        => 'erfolgreiche Aktionen',
    'JOBS::LABEL::SUCCESSFUL_CONFIGURATIONS' => 'erfolgreiche Aufgaben',
    'JOBS::LABEL::EDIT'                      => 'Job bearbeiten',
    'JOBS::HISTORY'                          => 'Historie des Jobs ":jobname"',
    'JOBS::HEADLINE::ERROR_HANDLING'         => 'Fehlerbehandlung',
    'JOBS::LABEL::BREAK_ON_FAILURE'          => 'abbrechen bei Fehlern',
    'JOBS::LABEL::NOT_BREAK_ON_FAILURE'      => 'fortsetzen bei Fehlern',
    //jobruns
    'JOBS::JOBRUNS::NORUNS'                     => 'Es wurde noch kein Durchlauf gestartet.',
    'JOBS::JOBRUNS::LOG'                        => 'Protokoll',
    'JOBS::JOBRUNS::COUNT'                      => 'gesamt',
    'JOBS::JOBRUNS::COUNT_SUCCESS'              => 'erfolgreich',
    'JOBS::JOBRUNS::COUNT_FAILED'               => 'fehlgeschlagen',
    'JOBS::JOBRUN::STARTED'                     => 'Job ":jobname" erfolgreich gestartet.',
    'JOBS::JOBRUN::CONFIG::STARTED'             => ':number. Aufgabe gestartet.',
    'JOBS::JOBRUN::CONFIG::END'                 => ':number. Aufgabe :status beendet.',
    'JOBS::JOBRUN::CONFIG::END_ITEMS'           => 'Es wurden :insertedItems / :totalItems :pluralItemName :pastAction.',
    'JOBS::JOBRUN::CONTINUE_AFTER_FAILURE'      => 'Der Job wird nach einer fehlgeschlagenen Aufgabe fortgesetzt.',
    'JOBS::JOBRUN::END'                         => 'Der Job ":jobname" wurde :status beendet. Es wurden :insertedItems / :totalItems Aktionen erfolgreich ausgeführt.',
    'JOBS::JOBRUN::IS_RUNNING'                  => 'Der Job ist noch aktiv. Es werden weitere Meldungen abgerufen...',
    //jobrun errors
    'JOBS::JOBRUN::ERROR::START_CONFIGURATION'  => "Die Aufgabe mit der id: \"%s\" wurde nach dem Start abgebrochen.\n%s",
    'JOBS::JOBRUN::ERROR::JOB_ABORTED'          => 'Der Job wurde abgebrochen, da eine Aufgabe fehlgeschlagen ist.',
    //job errors
    'Job already exists'                  => 'Es gibt bereits einen Job mit diesem Namen',
    'The job name is invalid'             => 'Der Jobname ist ungültig. Bitte benutzen Sie nur Buchstaben, Zahlen, Leerzeichen, Unterstriche und das Minus für den Namen.',
    //configuration / tasks
    'CONFIGURATION::SOURCE::SELECTONE'          => 'Quelle auswählen',
    'CONFIGURATION::TARGET::SELECTONE'          => 'Ziel auswählen',
    'CONFIGURATION::MAPPER::HEADLINE'           => 'Attribut-Zuordnung',
    'CONFIGURATION::SAVED_SUCCESSFUL'           => 'Aufgabe erfolgreich gespeichert!',
    'CONFIGURATION::ITEM_NAME::dataset'         => array(
                                                        'Datensätze',
                                                        'Datensatz'
                                                    ),
    'CONFIGURATION::ITEM_NAME::file'            => array(
                                                        'Dateien',
                                                        'Datei'
                                                    ),
    'CONFIGURATION::TARGET_ACTION::import'      => 'importieren',
    'CONFIGURATION::TARGET_ACTION_PAST::import' => 'importiert',
    'CONFIGURATION::TARGET_ACTION::move'        => 'verschieben',
    'CONFIGURATION::TARGET_ACTION_PAST::move'   => 'verschoben',
    'CONFIGURATION::TARGET_ACTION::write'        => 'schreiben',
    'CONFIGURATION::TARGET_ACTION_PAST::write'   => 'geschrieben',
    //errors
    'ERROR:ERROR' => 'Es ist ein Fehler aufgetreten.',
    'ERROR:SORRY' => 'Sollte es erneut zu Problemen kommen, wenden Sie sich bitte an unseren Support.',
    'ERROR::INVALID_DATA_TYPE' => 'Der Datentyp ist inkorrekt.',
    'ERROR::ATTRIBUTE_VALIDATION_FAILED'  => 'Die Validierung des Attributes "%s" mit dem Wert "%s" ist fehlgeschlagen.',
    'ERROR::ATTRIBUTE_MAP_NOT_GIVEN'      => 'Für das Attribut "%s" ist kein Ziel-Attribut definiert.',
    'ERROR::JSON_PARSE'                   => 'Ihre Eingabe entspricht keinem gültigen JSON-Format. Bitte überprüfen Sie Ihre Angaben.',
    //Zend validation messages
    'notDigits'                           => 'Der Wert darf nur Zahlen enthalten.',
    //DataStructure Table
    'HEADLINE::TABLE::COLUMN'   => 'Tabellenspalten',
    'TABLE::COLUMN::NAME'       => 'Name',
    'TABLE::COLUMN::TYPE'       => 'Typ',
    'TABLE::COLUMN::DEFAULT'    => 'Standardwert',
    'TABLE::COLUMN::NOTNULL'    => 'Nicht Null',
    'TABLE::COLUMN::LENGTH'     => 'Länge',
    'TABLE::COLUMN::PRECISION'  => 'Vorkommastellen',
    'TABLE::COLUMN::SCALE'      => 'Nachkommastellen',
    'TABLE::COLUMN::AUTOINC'    => 'Autoincrement',
    //Features
    'FEATURE::TH'                           => 'Option',
    'FEATURE::ADD'                          => 'Option hinzufügen',
    'FEATURES::CHOOSE_FEATURE'              => 'Option wählen',
    'FEATURES::INFO::CHOOSE_SITE'           => 'Legen Sie fest, ob die Option für die Quelle oder das Ziel aktiviert werden soll.',
    'FEATURES::INFO::CHOOSE_ATTRIBUTES'     => 'Legen Sie fest, für welche Attribute die Option aktiviert werden soll.',
    'FEATURES::INFO::CHOOSE_ERROR_BEHAVIOR' => 'Was soll bei einer fehlerhaften Validierung passieren?',
    'FEATURES::ACTIVATE_SOURCE'             => 'für Quell-Attribute aktivieren',
    'FEATURES::ACTIVATE_TARGET'             => 'für Ziel-Attribute aktivieren',
    'FEATURES::LABEL::TYPE'                 => 'Kategorie',
    'FEATURES::STATIC_VALUE::HELP::FEATURE' => 'Mit dem StaticValue Manipulator können Sie für ein oder mehrere Attribute einen festen Wert definieren, der immer für das Attribute verwendet wird. Diese Option ist vorallem für Ziel-Attribute wichtig, für die keine passenden Quell-Attribute verfügbar sind.',
    'FEATURES::STATIC_VALUE::LABEL::VALUE'  => 'statischer Wert',
    'FEATURES::STATIC_VALUE::HELP::VALUE'   => 'Verwenden Sie "TRUE" und "FALSE" für Wahrheitswerte.',
    //Validators
    'VALIDATORS::ERROR_HANDLING::BREAK'      => 'Aufgabe abbrechen',
    'VALIDATORS::ERROR_HANDLING::SKIP'       => 'Datensatz überspringen',
    'VALIDATORS::ERROR_HANDLING::WARN'       => 'warnen, aber Datensatz verarbeiten',
    //Attribute Map
    'ATTRIBUTEMAP::HEADLINE'                 => 'Attribute verknüpfen',
    'ATTRIBUTEMAP::HELP'                     => 'Mit der "AttributeMap"-Option können Sie bereits zugewiesene Quell-Attribute weiteren Ziel-Attributen zuordnen. Z.B. kann die Anforderung bestehen, einen Artikelnamen einmal als Namen und einmal als Link zu importieren. Solche Doppelverknüpfungen lassen sich hier einstellen.',
    //Directory
    'DIRECTORY::LABEL::READ_DATA'            => 'Daten der Dateien auslesen',
    //SourceScript
    'SCRIPT::LABEL::SCRIPT_NAME'             => 'Scriptname',
    'SCRIPT::LABEL::LOG_FILE_NAME'           => 'Name der Log-Datei',
    //Users
    'HEADLINE::USERS'                        => 'Benutzerverwaltung',
    'USERS::HEADLINE::ADD_USER'              => 'Benutzer hinzufügen',
    //User Form
    'USERS::FORM::LABEL::USERNAME'           => 'Username',
    'USERS::FORM::LABEL::LASTNAME'           => 'Nachname',
    'USERS::FORM::LABEL::FIRSTNAME'          => 'Vorname',
    'USERS::FORM::LABEL::EMAIL'              => 'eMail',
    'USERS::FORM::LABEL::PASSWORD'           => 'Passwort',
);